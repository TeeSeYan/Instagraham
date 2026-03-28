<?php
session_start();
include 'db_connection.php'; // Your database connection file

// Initialize a variable for messages
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    
    // File upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $newFileName = basename($_FILES["image"]["name"]);
    
    $uploadOk = 1;

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message .= "\\nSorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $newFileName)) {
            // File uploaded successfully, insert data into database
            $comment = $_POST['comment'];
            $idcreator = $_SESSION['user_id'];

            // Prepare and bind SQL statement
            $stmt = $conn->prepare("INSERT INTO photo (title, imageurl, comment, idcreator) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $title, $newFileName, $comment, $idcreator);

            // Execute the statement
            if ($stmt->execute()) {
                header('Location: home.php');
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            $message .= "\\nSorry, there was an error uploading your file.";
        }
    }
}

// If there's a message, print a script to alert it
if (!empty($message)) {
    echo "<script type='text/javascript'>alert('$message'); window.history.go(-1);</script>";
}
?>
