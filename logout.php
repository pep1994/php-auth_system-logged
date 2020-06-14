<?php 
    require __DIR__ . "/include/default.php";
    try {
        // se il logout va a buon fine reindirizzo l'utente alla home
        if ($auth -> logout())  {
            header("Location: index.php");
            die();
        }
    } catch (Exception $e) {
        echo $e -> getMessage();
    }
?>