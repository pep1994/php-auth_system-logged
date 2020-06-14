<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
    session_start();
    require __DIR__ . "/db.php";
    require __DIR__ . "/authSys.php";
    $auth = new AuthSys($PDOconn);
?>