<?php
     require __DIR__ . "/include/default.php";
     try {
         if ($_POST['csrf'] !== $_SESSION['csrf']) {
             header('Location: index.php');
             die();
         }
         if ($auth -> cancellaUtente()) {
             header("Location: index.php");
         }
     } catch(Execption $e) {
         echo $e -> getMessage();
     }
  
   