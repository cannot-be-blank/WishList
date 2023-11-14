<?php
include './core/database/pdo.php';
include './core/classes/Sellers.php';

$sellers = new Sellers($pdo);

//FORM HANDLER/////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['submit']))
{
    //follwing accounts for sellers either with or without a colors for the button
    if(empty($_POST['btnColor'])){ $btnColor = 'green'; }
    else $btnColor = $_POST['btnColor'];

    if(empty($_POST['txtColor'])){ $txtColor = 'white'; }
    else $txtColor = $_POST['txtColor'];

    //add seller to db, then return to add-item.php or edit-item.php, depending on the value of the `source` GET superglobal (depends on which page the user came from), with the remembered form input values as GET superglobals to re-populate the form inputs
    $newSeller = $sellers->insertSeller($_POST['name'], $btnColor, $txtColor);

    $name = $_GET['name'];
    $link = $_GET['link'];
    if($_GET['source'] == 'add'){ $source = 'add-item'; }
    if($_GET['source'] == 'edit'){ $source = 'edit-item'; }

    header("Location: ./$source.php?name=$name&itemID=$itemID&link=$link&sellerID=$newSeller");
    die();
}
//END FORM HANDLER/////////////////////////////////////////////////////////////////////////////////

$pageTitle = 'Add Seller';

include './imports/header.php';
?>
<!-- HTML BODY ----------------------------------------------------------------------------------->
<button class="btn btn-secondary mt-2 mb-2" onclick="history.back()">< Back</button>
<h2>Add a new seller:</h2>
<form action="" method="post">
    <div class="mb-3 mt-3">
        <input type="text" name="name" class="form-control" placeholder="Seller Name" required>
    </div>
    <div class="mb-3">
        <input type="text" name="btnColor" class="form-control" placeholder="Button Color (optional)">
    </div>
    <div class="mb-3">
        <input type="text" name="txtColor" class="form-control" placeholder="Button Text Color (optional)">
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="Add Seller">
</form>
<!-- END HTML BODY ------------------------------------------------------------------------------->
<?php include './imports/footer.php'; ?>
