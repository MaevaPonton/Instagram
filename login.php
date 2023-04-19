<?php
session_start();
require("infos.php");
require("connect.php");
@$valider = $_POST["login"];
$erreur = "";


if (isset($valider)) {
    if (empty($pseudo)) $erreur = "Le champ pseudo est obligatoire!";
    else {
        require("connect.php");
        $verify_pseudo = $pdo->prepare("select * from utilisateurs where pseudo=? limit 1");
        $verify_pseudo->execute(array($pseudo));
        $user_pseudo = $verify_pseudo->fetchAll();

        if (count($user_pseudo) > 0) {
            $_SESSION["pseudo"] = ucfirst(strtoupper($user_pseudo[0]["pseudo"]));
            $_SESSION["connecter"] = "yes";
            header("location:homepage.php");
        } else {
            $ins = $pdo->prepare("insert into utilisateurs(pseudo) values(?)");
            if ($ins->execute(array($pseudo))) {
                $_SESSION["pseudo"] = ucfirst(strtoupper($pseudo));
                $_SESSION["connecter"] = "yes";
                header("location:homepage.php");
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <title>Projet Instagram
    </title>
</head>

<body>

<div class="container">
    <img src="./images/logo_transparent.png" class="logo">

    <form method="post" id="container">
        <h3 id="Heading">Identifie-toi, métalleux du dimanche !!!!</h3>
        <label>Pseudo:</label>
        <div class="row">
            <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="100%">
                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                </svg></div><input type="text" name="pseudo" required>
        </div>


        <button class="input-button" type="submit" name="login" value="Login">Login</button>

    </form>

    </div>





</body>

</html>