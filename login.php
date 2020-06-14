<?php 
    require __DIR__ . "/include/default.php";
    if ($_POST) {
        try {
            // se il login è andato a buon fine (restituisce true), reindirizzo l'utente al suo profilo
           if ($auth -> login($_POST['username'], $_POST['password'])) {
              header("Location: profilo.php");
              die();
           } 
        } catch (Exception $e) {
            echo $e -> getMessage();
        }
    }
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
    <a type="button" class ="btn btn-info" href="index.php">Home</a>
    <a type="button" class ="btn btn-info" href="registrati.php">Registrati</a>
    <a type="button" class ="btn btn-info" href="login.php">Login</a>
    <hr>

    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Inserisci Username">
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Inserisci Password">
        </div>
        <div class="form-group">
            <input type="submit" value="Login" class="btn btn-info">
        </div>      
    </form>
</body>
</html>