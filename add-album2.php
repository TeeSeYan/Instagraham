<?php
session_start();
include 'db_connection.php'; // Your database connection file

// Assuming the session variable 'user_id' is always set
$idcreator = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the album title from the form data
    $albumTitle = $_POST['albumTitle'];
    
    // Initialize variables
    $target_dir = "album_cover/";
    $uploadOk = 1;
    $newFileName = NULL; // Default value if no file is uploaded

    // Check if a file was actually uploaded
    if ($_FILES["image"]["error"] == 0) {
        // File was uploaded
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "jfif" ) {
            echo "Sorry, only JPG, JPEG, PNG, JFIF & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Try to upload file if no errors
        if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File uploaded successfully, set new file name
            $newFileName = basename($_FILES["image"]["name"]);
        } else {
            echo "Sorry, there was an error uploading your file.";
            $uploadOk = 0; // Mark as error to prevent database insertion
        }
    }
    
    // Proceed with database insertion if no upload errors or no file was uploaded
    if ($uploadOk == 1) {
        $stmt = $conn->prepare("INSERT INTO album (title, imageurl, idcreator) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $albumTitle, $newFileName, $idcreator);
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            // Insertion successful
            header("Location: gallery.php");
        } else {
            echo "Error inserting album.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
