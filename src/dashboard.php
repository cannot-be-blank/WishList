<?php
$pageTitle = "[NAME]'s Wish List Dashboard"; //add your name here

include './imports/header-datatables.php';
?>
<!-- DATATABLE CONFIGURATION --------------------------------------------------------------------->
<script>
    $(document).ready( function () {
        $('#items').DataTable({
            paging: false,
            ordering: false,
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
                    title: 'Preference',
                    data: null,
                    render: function(data)
                    {
                        return(
                            '<a href="preference-down.php?itemID='+data.id+'&itemPreference='+data.preference+'" title="Move item up"><i class="bi bi-caret-up-fill" style="color: #6c757d"></i></a>'+
                            '<br>'+
                            '<a href="preference-up.php?itemID='+data.id+'&itemPreference='+data.preference+'" title="Move item down"><i class="bi bi-caret-down-fill" style="color: #6c757d"></i></a>'
                        );
                    }
                },
                {
                    title: 'Name',
                    data: null,
                    render: function(data)
                    {
                        return data.preference+'. '+data.name;
                    }
                },
                {
                    title: 'View Store Page',
                    data: null,
                    render: function(data)
                    {
                        return '<a href="'+data.link+'" class="btn" target="_blank" style="background-color: '+data.seller_btn_color+'; color: '+data.seller_txt_color+'">'+data.seller_name+'</a>';
                    }
                },
                {
                    title: 'Edit',
                    data: null,
                    render: function(data)
                    {
                        return '<a href="edit-item.php?itemID='+data.id+'&sellerID='+data.seller_id+'" class="btn btn-warning">Edit</a>';
                    }
                },
                {
                    title: 'Delete',
                    data: null,
                    render: function(data)
                    {
                        return '<a href="delete-item.php?id='+data.id+'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete '+data.name+' from your wish list? This cannot be undone.\')">Delete</a>';
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
    <a href="add-item.php"title="Add item"><i class="bi bi-plus-square-fill" style="font-size: 2rem; color: #6c757d"></i></a>
    <br>
    <table id="items"></table>
</div>
<!-- END HTML BODY ------------------------------------------------------------------------------->
<?php include './imports/footer.php'; ?>