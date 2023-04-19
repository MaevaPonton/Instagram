<?php

require("connect.php");
require("infos.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Récupérer les valeurs envoyées par le formulaire
  $date_naissance = $_POST["date_de_naissance"];
  $description = $_POST["description"];

  // Vérifier que l'utilisateur est connecté
  if (isset($_SESSION["pseudo"])) {
    // Mettre à jour la table utilisateurs avec les nouvelles valeurs
    $stmt = $pdo->prepare("UPDATE utilisateurs SET date_naissance = :date_naissance, description = :description WHERE pseudo = :pseudo");
    $stmt->bindParam(":date_naissance", $date_naissance);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":pseudo", $_SESSION["pseudo"]);
    $stmt->execute();

    // Mettre à jour les variables de session avec les nouvelles valeurs
    $_SESSION["date_naissance"] = $date_naissance;
    $_SESSION["description"] = $description;
  }
}


// importer la photo de profil de l'utilisateur et la changer automatiquement en cas de modification dans le formulaire edit_profil :

$image_path = "./avatars/";

if (isset($_SESSION["pseudo"])) {
  $stmt = $pdo->prepare("SELECT avatar FROM utilisateurs WHERE pseudo = :pseudo");
  $stmt->execute(array(':pseudo' => $_SESSION["pseudo"]));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row && !empty($row["avatar"])) { // Vérifiez si l'image stockée n'est pas vide
    $imageAvatar = $row["avatar"];
  } else {
    $imageAvatar = "../images/avatar-defaut.jpg";
  }
}



// importer la description de l'utilisateur et la changer automatiquement en cas de modification dans le formulaire edit_profil :

$stmt = $pdo->prepare("SELECT description FROM utilisateurs WHERE pseudo = :pseudo");
$stmt->execute(array(':pseudo' => $_SESSION["pseudo"]));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row && !empty($row["description"])) {
  $description = $row["description"];
} else {
  $description = "Ajoute une description de toi !";
}





?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./css/header2.css">

</head>

<body>







  <button class='buttondeconnexion'><a href="/logout.php">Deconnexion</a> </button>
  <button class='buttonprofil'> <a href="/profil.php">Voir mon profil</a></button>



  <div class="profile">


    <div class="profile-image">
      <?php
      $image_path = "./avatars/";
      $avatar = isset($_SESSION["avatar"]) ? $_SESSION["avatar"] : "";
      if (file_exists($image_path . $imageAvatar)) {
        echo '<img src="' . $image_path . $imageAvatar . '" alt="">';
      } else {
        echo "L'image n'existe pas";
      }
      ?>
    </div>

    <div class="profile-bio">
      <h4 class="profile-user-name"> Bienvenue sur ta page d'actu,<br> <?php echo $_SESSION["pseudo"]; ?></h4>
      <h4><?php echo $description; ?> </h4>
    </div>

  </div>








</body>

</html>