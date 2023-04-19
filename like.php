<?php

session_start();
require("connect.php");
require("infos.php");


if (!isset($_SESSION['pseudo'])) {
    header("Location: login.php");
    exit();
}




if (isset($_POST['like']) || isset($_POST['dislike'])) {

    $image_id = $_POST['image_id'];
    $pseudo = $_SESSION['pseudo'];
    $like = isset($_POST['like']) ? 1 : 0;
    $dislike = isset($_POST['dislike']) ? 1 : 0;

    // Vérifie si l'utilisateur a déjà aimé ou disliké cette image
    $sql = "SELECT * FROM images WHERE id = ? AND (likes LIKE ? OR dislikes LIKE ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$image_id, "%,$pseudo,%", "%,$pseudo,%"]);
    $result = $stmt->fetch();


    if ($like) {
        // Récupérez le nombre actuel de likes
        $sql = "SELECT likes FROM images WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$image_id]);
        $result = $stmt->fetch();
        $likes = intval($result['likes']);

        // Incrémentez le nombre de likes et mettez à jour la base de données
        $likes++;
        $sql = "UPDATE images SET likes = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$likes, $image_id]);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();


    } else if ($dislike) {
     // Récupérez le nombre actuel de likes
     $sql = "SELECT dislikes FROM images WHERE id = ?";
     $stmt = $pdo->prepare($sql);
     $stmt->execute([$image_id]);
     $result = $stmt->fetch();
     $dislikes = intval($result['dislikes']);
 
     // Incrémentez le nombre de dislikes et mettez à jour la base de données
     $dislikes++;
     $sql = "UPDATE images SET dislikes = ? WHERE id = ?";
     $stmt = $pdo->prepare($sql);
     $stmt->execute([$dislikes, $image_id]);
     header("Location: " . $_SERVER['HTTP_REFERER']);
     exit();
    }

} 




  echo '<script type="text/javascript"> window.location="homepage.php";</script>';

?>
