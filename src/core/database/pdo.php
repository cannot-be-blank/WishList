<?php
//YOUR DB CONNECTION INFO HERE/////////
$dbHost = 'localhost';               //
$dbName = 'DatabaseyMcDatabaseface'; //
$dbUsername = 'foo';                 //
$dbPassword = 'bar';                 //
///////////////////////////////////////
try
{
   $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){ error_log($e->getMessage()); }