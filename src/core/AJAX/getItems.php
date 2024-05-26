<?php
include '../database/pdo.php';
include '../classes/WishList.php';
include '../classes/Sellers.php';

$wishList = new WishList($pdo);
$sellers = new Sellers($pdo);

$items = $wishList->getItems();

foreach($items as $item) //converts the data of each item into the format the datatable expects
{
    if(!is_null($item['seller_id']))
    {
        $seller = $sellers->getSeller($item['seller_id']);
        $sellerID = $seller['id'];
        $sellerName = $seller['name'];
        $btn_color = $seller['btn_color'];
        $txt_color = $seller['txt_color'];
    }
    else
    {
        $sellerID = '';
        $sellerName = '';
        $btn_color = 'white';
        $txt_color = 'white';
    }
    $data[]=
    [
        'id' => $item['id'],
        'name' => $item['name'],
        'link' => $item['link'],
        'purchased' => $item['purchased'],
        'preference' => $item['preference'],
        'seller_id' => $sellerID,
        'seller_name' => $sellerName,
        'seller_btn_color' => $btn_color,
        'seller_txt_color' => $txt_color
    ];
}

print json_encode([$_REQUEST['dataSrc'] ?: 'data' => $data]);