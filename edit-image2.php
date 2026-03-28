<?php
// Connect to database
include 'db_connection.php';

$uploadOk = 1; // Initialize upload flag

// Start of the if block for checking newImage upload
if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["newImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["newImage"]["tmp_name"]);

    // Check if file is an actual image or fake image
    if($check !== false) {
        // File is an image - Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.'); window.history.go(-1);</script>";
            $uploadOk = 0;
        }
    } else {
        echo "<script>alert('File is not an image.'); window.history.go(-1);</script>";
        $uploadOk = 0;
    }

    // Proceed if checks pass
    if ($uploadOk == 1 && move_uploaded_file($_FILES["newImage"]["tmp_name"], $target_file)) {
        // Only display the successful upload message if file upload succeeded
        echo "<script>alert('The file ". htmlspecialchars(basename($_FILES["newImage"]["name"])). " has been uploaded.');</script>";
    }
}

if ($uploadOk == 1) {
    // Proceed with database update only if uploadOk is still 1
    $comment = addslashes($_POST['comment']); // Sanitize the input
    $title = addslashes($_POST['title']);
    $idphoto = $_POST['photoID'];
    
    if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == 0) {
        // Include image URL in update if new image was uploaded
        $q = "UPDATE photo SET title='$title', imageurl='" . basename($_FILES["newImage"]["name"]) . "', comment='$comment' WHERE idphoto=$idphoto;";
    } else {
        // Update without changing image URL
        $q = "UPDATE photo SET title='$title', comment='$comment' WHERE idphoto=$idphoto;";
    }

    // Run the query and provide user feedback
    if ($conn->query($q)) {
        echo "<script>alert('Image updated successfully.'); window.location.href='home.php';</script>";
    } else {
        echo "<script>alert('Query error: please contact your system administrator.'); window.history.go(-1);</script>";
    }
} else {
    // If uploadOk is not 1, do not attempt to update the database
    // The script already sent an alert and redirected for any errors
}

$conn->close(); // Close the database connection
?>
