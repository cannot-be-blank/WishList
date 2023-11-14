<?php
include './core/database/pdo.php';
include './core/classes/WishList.php';

$wishList = new WishList($pdo);
$itemID = $_GET['id'];

$wishList->deleteItem($itemID);

header("Location: ./dashboard.php");