<?php
session_start();
require("connect.php");
require("infos.php");
   
if(isset($_POST['submit'])) {

	$pseudo = $_SESSION['pseudo'];
  
    // Count total files
    $countfiles = count($_FILES['files']['name']);
   
    // Prepared statement
    $query = "INSERT INTO images (name,image,pseudo,publication) VALUES(?,?,?,?)";
  
    $statement = $pdo->prepare($query);
  
    // Loop all files
    for($i = 0; $i < $countfiles; $i++) {
  
        // File name
        $filename = $_FILES['files']['name'][$i];
      
        // Location
        $target_file = './uploads/'.$filename;
      
        // file extension
        $file_extension = pathinfo(
            $target_file, PATHINFO_EXTENSION);
             
        $file_extension = strtolower($file_extension);
      
        // Valid image extension
        $valid_extension = array("png","jpeg","jpg");

				$publication = $_POST['publication'];
      
        if(in_array($file_extension, $valid_extension)) {
  
            // Upload file
            if(move_uploaded_file(
                $_FILES['files']['tmp_name'][$i],
                $target_file)
            ) {
 
                // Execute query
                $statement->execute(
                    array($filename,$target_file,$pseudo,$publication));
            }
        }
    }
     
    echo '<script type="text/javascript"> window.location="profil.php";</script>';
}

