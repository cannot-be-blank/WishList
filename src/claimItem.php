<?php
include './core/database/pdo.php';
include './core/classes/WishList.php';

$wishList = new WishList($pdo);
$itemID = $_GET['id'];

$wishList->claimItem($itemID);

header("Location: ./index.php");