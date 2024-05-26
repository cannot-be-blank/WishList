<?php
include './core/database/pdo.php';
include './core/classes/WishList.php';

$wishList = new WishList($pdo);
$itemID = $_GET['itemID'];
$itemPreference = $_GET['itemPreference'];

$wishList->itemPreferenceDown($itemID, $itemPreference);

header("Location: ./dashboard.php");