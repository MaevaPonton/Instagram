<?php
session_start();
require("connect.php");
require("infos.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./css/edit_profil.css">
  
</head>
<body>

<button class ="deconnexion" ><a href="/logout.php">Deconnexion</a> </button>
<button class ="deconnexion" ><a href="/profil.php">Retour à mon profil</a> </button>


<div class = "modification" >
  


<h1 class="profile-user-name"><?php echo $_SESSION["pseudo"]; ?></h1>
<br>
<br>



<form action="process_profil.php" method="POST" enctype="multipart/form-data">

  <label for="avatar">Modifie ta photo de profil :</label>
  <br>
  <br>
  <input type="file" name="avatar" id="avatar"><br><br>

  <label for="date_de_naissance">Modifie ta date de naissance :</label>
  <br>
  <br>
  <input type="date" name="date_de_naissance" id="date_de_naissance"><br><br>

  <label for="description">Modifie ta description :</label>
  <br>
  <br>
  <textarea name="description" id="description" placeholder = "décris toi en quelques lignes" style="height: 200 px"></textarea><br><br>
  <br>
  <br>
  <input type="submit" value="Enregistrer les modifications">

</form>  
</div>


</body>
</html>














