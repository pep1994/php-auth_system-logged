<?php 
    require __DIR__ . "/include/default.php";
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="include/css/style.css">
</head>
<body>
    <?php 
        if ($auth -> utenteLoggato()) {
            echo '<a type="button" class ="btn btn-info" href="profilo.php">Profilo</a>';
        } else {
            echo '<a type="button" class ="btn btn-info" href="registrati.php">Registrati</a>' . ' <a type="button" class ="btn btn-info" href="login.php">Login</a>';
        }
    ?>
    <hr>
</body>
</html>