<?php
session_start();
require("connect.php");
require("infos.php");
include("header2.php");
   


?>
 
<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content=
        "width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href="./css/homepage.css">
              
    <title></title>
</head>
 
<body >

<div id="body-el">
  
    

  

<div class="publication">

    <div class ="mapubli">
	<h1><?php echo $_SESSION["pseudo"]; ?> , partage une photo avec tes amis : </h1>
    <br>
    <br>
    <form method='post' action='postimage.php' enctype='multipart/form-data'>
        <textarea name="publication" rows="3" cols="60" placeholder="Donne un titre à ta photo !"></textarea>
        <br>
        <br>
        <input class="input-file" type='file' name='files[]' multiple />
        <br>
        <br>
        <input class="input-submit" type='submit' value='Submit' name='submit' />
        <br>
        <br>
    </form>
    </div>
 
    
		<?php

// Get images from the database
$query = $pdo->query("SELECT * FROM images ORDER BY id DESC");
if ($query->rowCount() > 0) {
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $imageURL = $row["image"];
        $author = $row["pseudo"];
        $publication = $row['publication'];
        $likes = $row['likes'];
        $dislikes = $row['dislikes'];
?>
    <div class='publication-image'>
        <div class = 'publiintro'>
            <p class='publi'>Photo publiée par : <?php echo $author; ?><br><div class="posthomepage"> Titre : <?php echo $publication; ?> </div></p>
        </div>
            <img class="imagehomepage " src="<?php echo $imageURL; ?>" alt="" />
        <br>
        <form method='post' action='formcomment.php' class='comment-form'>
            <input type='hidden' name='image_id' value='<?php echo $row["id"]; ?>' />
            <input class='pseudoname'type='hidden' name='pseudo' value='<?php echo $_SESSION["pseudo"]; ?>' >
            <textarea class ='comments' name='comment' rows='5' cols='80' placeholder='Donne nous ton avis !' required></textarea>
            <br>
            <input class='buttoncomment center' type='submit' value='Envoyer' name='submit_comment' />

        </form>
        
        
        <?php
        // Récupérer les commentaires pour cette image depuis la base de données
        $stmt = $pdo->prepare("SELECT author, comment_text FROM comments WHERE image_id = :image_id");
        $stmt->bindValue(':image_id', $row['id'], PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll();?>
        <br>
<!-- ///////////////// -->

<!-- Partie des likes et dislikes : -->
        <div class="like-buttons">
        <?php if (isset($_SESSION['pseudo'])) {
                // Récupération du like et du dislike de l'utilisateur pour cette image
                $stmt = $pdo->prepare('SELECT * FROM images WHERE pseudo = :pseudo AND id = :image_id');
                $stmt->execute(['pseudo' => $_SESSION['pseudo'], 'image_id' => $row['id']]);
                $vote = $stmt->fetch();

                // Affichage du bouton like si l'utilisateur n'a pas déjà voté pour cette image ou s'il a voté dislike
                if (!$vote || $vote['dislikes'] == 1) {
                    if (!$vote) {
                        echo '<form method="post" action="like.php">';
                        echo '<input type="hidden" name="image_id" value="' . $row['id'] . '">';
                        echo '<button class="bouton-like like-size center-text my-class-like" type="submit" name="like" >' . $likes . '</button>';

                    } else {
                        echo '<button class="bouton-like like-size center-text disabled" disabled>' . $likes . '</button>';
                    }
                    echo '</form>';
                }

                // Affichage du bouton dislike si l'utilisateur n'a pas déjà voté pour cette image ou s'il a voté like
                if (!$vote || $vote['likes'] == 1) {
                    if (!$vote) {
                    echo '<form method="post" action="like.php">';
                    echo '<input type="hidden" name="image_id" value="' . $row['id'] . '">';
                    echo '<button class="bouton-dislike dislike-size center-text my-class-dislike" type="submit" name="dislike"> ' . $dislikes . '</button>';
                    } else {
                        echo '<button class="bouton-dislike dislike-size center-text disabled" disabled>' . $dislikes . '</button>';
                    }  
                    echo '</form>';
                } 
            } else {
                // Affichage d'un message invitant l'utilisateur à se connecter s'il n'est pas connecté
                echo 'Connectez-vous pour voter';
            }
           
?>
 </div>



        <div id="comm">

        <?php
        // Afficher les commentaires
        foreach ($comments as $comment) {
            echo "<div class='commentext '>";
            echo "<span class='commentauthor' >"  . $comment['author'] . ":  </span>"."<span class='commenttext'>" . $comment['comment_text'] . "</span>" ;
            echo "</div>";
        }
        ?>
        </div>
        <?php
    }
} else {
    echo "<p>No image(s) found...</p>";
}
?>
</div>
</div>



</div>
</body>
 
</html>
