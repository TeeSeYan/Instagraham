<?php
session_start();
include 'db_connection.php'; // Include your database connection file

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $comment = $_POST['comment'];
    $album_id = isset($_POST['album_id']) ? $_POST['album_id'] : 0;
    
    // Ensure album_id is an integer to prevent SQL injection
    $album_id = filter_var($album_id, FILTER_SANITIZE_NUMBER_INT);

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $newFileName = time() . '_' . basename($_FILES["image"]["name"]); // Using time() to avoid file name duplication
    $uploadOk = 1;

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $message .= "\\nSorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $newFileName)) {
            $idcreator = $_SESSION['user_id']; // Assuming your session stores user_id
            
            $stmt = $conn->prepare("INSERT INTO photo (title, imageurl, comment, idalbum, idcreator) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", $title, $newFileName, $comment, $album_id, $idcreator);

            if ($stmt->execute()) {
                // Redirect with success message
                $message = "Image uploaded successfully.";
                echo "<script type='text/javascript'>alert('$message'); window.location = 'view-album.php?album_id=" . $album_id . "';</script>";
                exit;
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message .= "\\nSorry, there was an error uploading your file.";
        }
    }
    $conn->close();
}

if (!empty($message)) {
    echo "<script type='text/javascript'>alert('$message'); window.history.go(-1);</script>";
}
?>
