<?php
$pageTitle = "[NAME]'s Wish List"; //add your name here

include './imports/header-datatables.php';
?>
<!-- DATATABLE CONFIGURATION --------------------------------------------------------------------->
<script>
    $(document).ready( function () {
        $('#items').DataTable({
            paging: false,
            ajax:
            {
                url: './core/AJAX/getItems.php',
                type: 'GET',
                data: { dataSrc: 'data' },
                dataSrc: 'data'
            },
            columns:
            [
                {
                    title: 'Name',
                    data: null,
                    render: function(data)
                    {
                        if(data.purchased == 1)
                        {
                            $("#"+data.id).css("text-decoration", "line-through");
                        }
                        return data.name;
                    }
                },
                { //button to allow user to open store page of an item if a link is given
                    title: 'View Store Page',
                    data: null,
                    render: function(data)
                    {
                        return '<a href="'+data.link+'" class="btn" target="_blank" style="background-color: '+data.seller_btn_color+'; color: '+data.seller_txt_color+'">'+data.seller_name+'</a>';
                    }
                },
                { //button to allow user to mark an item as claimed so that others who view the list will know someone has already purchased it, but the owner will not see that the item has been removed or claimed
                    title: 'Claim',
                    data: null,
                    render: function(data)
                    {
                        if(data.purchased == 1){ return 'Already Claimed'; }
                        else return '<a href="claimItem.php?id='+data.id+'" class="btn btn-danger" style="white-space: nowrap;" onclick="return confirm(\'Are you sure you want to commit to purchasing this item? This cannot be undone.\')">I\'ll get this</a>';
                    }
                }
            ],
            rowId: 'id',
            liveAjax: { interval: 10000 } //checks db every 10 seconds
        });
    });
</script>
<!-- END DATATABLE CONFIGURATION ----------------------------------------------------------------->

<!-- HTML BODY ----------------------------------------------------------------------------------->
<div>
    <h1 style="text-align: center;"><?=$pageTitle?></h1>
    <table id="items"></table>
</div>
<!-- END HTML BODY ------------------------------------------------------------------------------->
<?php include './imports/footer.php'; ?>