<?php 
    $dsn = "mysql:host=localhost;dbname=auth_system;charset=utf8";
    try {
        $PDOconn = new PDO($dsn, 'root', 'root');
        $PDOconn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch (PDOException $e) {
        echo $e -> getMessage();       
    }


?>