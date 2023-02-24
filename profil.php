<?php
session_start();
require("connect.php");
require("infos.php");
include("header.php");


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
                if ($row) {
                        $imageAvatar = $row["avatar"];
                } else {
                        $imageAvatar = ""; // mettre une valeur par défaut si l'utilisateur n'a pas d'avatar
                }
    }


// importer la description de l'utilisateur et la changer automatiquement en cas de modification dans le formulaire edit_profil :

        $stmt = $pdo->prepare("SELECT description FROM utilisateurs WHERE pseudo = :pseudo");
        $stmt->execute(array(':pseudo' => $_SESSION["pseudo"]));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                        $description = $row["description"];
                } else {
                        $description = ""; // mettre une valeur par défaut si l'utilisateur n'a pas de description
                }





?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./css/profil.css">
  
</head>

<body>







<div class="comments">
    <h3> Publie une photo et commente la :</h3>
    <form method='post' action='postimage.php' enctype='multipart/form-data'>
        <input type='file' name='files[]' multiple />
        <br>
        <br>
        <textarea name="publication" rows="5" cols="35" placeholder="Commente ta photo"></textarea><br>
        <input type='submit' value='Submit' name='submit' />
    </form>
    
<main>

<div class="container">

  <div class="gallery">
 
    
  <?php

  // Get images from the database for current user

$query = $pdo->prepare("SELECT * FROM images WHERE pseudo = :pseudo ORDER BY id ASC");
$query->execute(array(':pseudo' => $_SESSION["pseudo"]));
if ($query->rowCount() > 0) {
    echo '<div class="image-grid">';
    $count = 0;
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $imageURL = $row["image"];
        $author = $row["pseudo"];
        $publication = $row['publication'];
        $likes = $row['likes'];
        $dislikes = $row['dislikes'];
?>
        <div class="image-container">
                <img class="imagehomepage" src="<?php echo $imageURL; ?>" alt="" />
                <p><div class="posthomepage"> <?php echo $publication; ?> </div></p>
               <!--  <div class="like-buttons">
                <form method="post" action="like.php">
                    <input type="hidden" name="image_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="like"><i class="fa fa-thumbs-up"></i> <?php echo $likes; ?></button>
                </form>
                <form method="post" action="like.php">
                    <input type="hidden" name="image_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="dislike"><i class="fa fa-thumbs-down"></i> <?php echo $dislikes; ?></button>
                </form>
            </div> -->
        </div>
<?php
        $count++;
        if ($count % 3 == 0) {
            echo '</div><div class="image-grid">';
        }
    }
    echo '</div>';
} else {
    echo "No images found for user ".$_SESSION["pseudo"];
}

?>
    </div>


  </div>
  <!-- End of gallery -->


  <div class="loader"></div>

</div>
<!-- End of container -->

</main>


</body>
</html>