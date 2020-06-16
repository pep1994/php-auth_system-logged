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
    <?php 
    
        if ($auth -> utenteLoggato()) {
            echo '<a type="button" class ="btn btn-info" href="index.php">Home</a>';
        } 
    ?>
    <?php 
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upl'])) {       
            $auth -> uploadFile($_FILES);
        }      
    ?>
    <hr>
    <form action="profilo.php" method="post" enctype="multipart/form-data">
        <input type="file"  name="upl[]" multiple>
        <input type="submit" name="file-subm" type="button" class="btn btn-warning" value="Carica">
    </form>
    <form action="cancella-account.php" method="post">
        <div class="form-group">
            <textarea name="motivazione" cols="50" rows="5"></textarea>      
        </div>
        <input type="hidden" name="cancella" value="1">
        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
        <div class="form-group">
            <input type="submit" class="btn btn-danger" name="submitForm" value="Cancella il mio account">
        </div>
    </form>
</body>
</html>