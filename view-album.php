<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Album</title>
    <link rel="stylesheet" href="css/gallery_style.css">
</head>
<body>
<?php
session_start();
include 'db_connection.php'; // Include your database connection file
include 'nav_bar.html';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to login page
    exit();
}

// Check if album_id is set in the URL
if(isset($_GET['album_id'])) {
    // Retrieve album details from the database
    $album_id = $_GET['album_id'];
    $sql_album = "SELECT * FROM album WHERE idalbum = ?";
    
    if ($stmt_album = $conn->prepare($sql_album)) {
        $stmt_album->bind_param("i", $album_id);
        $stmt_album->execute();
        $result_album = $stmt_album->get_result();

        // Display album details
        if($result_album->num_rows > 0) {
            $row_album = $result_album->fetch_assoc();
            echo "<div class='main-content'>";
            echo "<div class='album-container'>";
            echo "<div class='album-header'>";
            echo "<h1>" . htmlspecialchars($row_album['title']) . "</h1>";
            echo "<a href='add-image-album.php?album_id=" . $album_id . "' class='add-imageToalbum-link'>Add Image to Album</a>"; 
            echo "<a href='delete-album.php?album_id=" . $album_id . "' onclick='return confirm(\"Are you sure you want to delete this album?\");' id='delete-album-link'>Delete Album</a></div>";
            echo "<hr>";
           
            // Display other album details as needed
            // Retrieve and display images from the album
            $sql_photos = "SELECT * FROM photo WHERE idalbum = ?";
            if ($stmt_photos = $conn->prepare($sql_photos)) {
                $stmt_photos->bind_param("i", $album_id);
                $stmt_photos->execute();
                $result_photos = $stmt_photos->get_result();
                
                if($result_photos->num_rows > 0) {
                    while($row_photo = $result_photos->fetch_assoc()) {
                        echo "<img src='uploads/" . htmlspecialchars($row_photo['imageurl']) . "' alt='" . htmlspecialchars($row_photo['title']) . "' id='album-image'>";
                    }
                } else {
                    echo "No images in this album."; 
                }
                echo "</div>"; 
                echo "</div>";
                $stmt_photos->close();
                $stmt_album->close();
            }
        }
    }
}

// Close database connection
$conn->close();
?>
</body>
</html>
