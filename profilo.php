<?php 
    require __DIR__ . "/include/default.php";
    $_SESSION['csrf'] = $auth -> getToken();
    // la pagina profilo deve essere protetta perciò controllo che l'utente sia loggato
    if (!$auth -> utenteLoggato()) {
        header("Location: index.php");
        die();
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

    <h2>Sei nel tuo profilo</h2>
    <a type="button" class ="btn btn-info" href="index.php">Home</a>
    <a type="button" class ="btn btn-info" href="registrati.php">Registrati</a>
    <a type="button" class ="btn btn-info" href="login.php">Login</a>
    <hr>

    <form action="cancella-account.php" method="post">
        <textarea name="motivazione" cols="50" rows="5"></textarea>
        <input type="hidden" name="cancella" value="1">
        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
        <input type="submit" class="btn btn-danger" name="submitForm" value="Cancella il mio account">
    </form>
</body>
</html>