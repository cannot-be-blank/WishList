<?php
include './core/database/pdo.php';
include './core/classes/WishList.php';
include './core/classes/Sellers.php';

$wishList = new WishList($pdo);
$sellers = new Sellers($pdo);

$itemID = $_GET['itemID'];
$item = $wishList->getItem($itemID);

//FORM HANDLER/////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['submit']))
{
    //follwing accounts for items either with or without a seller and link
    if(empty($_POST['sellerID'])){ $sellerID = null; }
    else $sellerID = $_POST['sellerID'];

    if(empty($_POST['link'])){ $link = '#'; }
    else $link = $_POST['link'];

    //update item in db, then return to dashboard
    $wishList->editItem($itemID, $_POST['name'], $link, $sellerID);
    header("Location: ./dashboard.php");
    die();
}
//END FORM HANDLER/////////////////////////////////////////////////////////////////////////////////

$pageTitle = 'Edit Item';
$stores = $sellers->getSellers(); //needed to populate seller form select

include './imports/header.php';
?>
<script>
    /**
     * handles sending user to add-seller.php AND remember current values of the add-item.php inputs for when the user returns
     */
    function addSeller()
    {
        var name = $('#input_name').val();
        var link = $('#input_link').val();

        window.location.href = './add-seller.php?source=edit&itemID='+<?=$itemID?>+'&name='+name+'&link='+link;
    }
</script>
<!-- HTML BODY ----------------------------------------------------------------------------------->
<button class="btn btn-secondary mt-2 mb-2" onclick="history.back()">< Back</button>
<h2>Make Changes to <?=$item['name']?>:</h2>
<form action="" method="post">
    <div class="mb-3 mt-3">
        <?php //if returning from add-seller.php, name will be set as a GET superglobal, and this populates the form input with that remembered value. otherwise populates form input with stored name
        if(!empty($_GET['name'])){ $name = $_GET['name']; }
        else $name = $item['name'];
        ?>
        <input type="text" id="input_name" name="name" class="form-control" value="<?=$name?>" placeholder="Item Name" required>
    </div>
    <div class="mb-2">
        <?php //if returning from add-seller.php, link will be set as a GET superglobal, and this populates the form input with that remembered value. otherwise populates form input with stored link
        if(!empty($_GET['link'])){ $link = $_GET['link']; }
        else $link = $item['link'];
        ?>
        <input type="text" id="input_link" name="link" class="form-control" value="<?=$link?>" placeholder="Link (optional)">
    </div>
    <div class="mb-3">
        <select class="form-select" name="sellerID" placeholder="Seller Name (optional if no link)" style="display: inline; width: 80%; margin-right: 5px">
            <option id="emptySeller" value="" selected>Seller (optional if no link)</option>
            <?php
            foreach($stores as $store)
            { ?>
                <option id="<?=$store['id']?>" value="<?=$store['id']?>"><?=$store['name']?></option>
                <?php
            }
            ?>
        </select>
        <!-- the following script checks if the `sellerID` GET superglobal is set. if so, this selects that sellers option in the form input -->
        <script>
            const queryString = window.location.search;
            const urlSearchParams = new URLSearchParams(queryString);
            sellerID = urlSearchParams.get('sellerID')
            if(sellerID)
            {
                $('#emptySeller').removeAttr('selected');
                $('#'+sellerID).attr('selected', 'true');
            }
        </script>
        <i class="bi bi-plus-square-fill" style="font-size: 2rem; color: #6c757d" onclick="addSeller()" title="Add seller"></i>
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="Update Item">
</form>
<!-- END HTML BODY ------------------------------------------------------------------------------->
<?php include './imports/footer.php'; ?>
