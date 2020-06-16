<?php 
    require __DIR__ . "/include/default.php";
    $ris = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST) {
        $ris = $auth -> registraNuovoUtente($_POST);      
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

    <?php echo $ris; ?>

    <form action="<?php  echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <input type="text" name="uname" class="form-control" placeholder="Username">
        </div>
        <div class="form-group">
            <input type="password" name="pwd" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
            <input type="password" name="re_pwd" class="form-control" placeholder="RePassword">
        </div>
        <div class="form-group">
            <input type="text" name="nome" class="form-control" placeholder="Nome">
        </div>
        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
            <input type="submit" value="Invia" class="btn btn-info" >
        </div>
    </form>
</body>
</html>