<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/gallery_style.css">
</head>
<body>
<?php
session_start();
include 'db_connection.php';
include 'nav_bar.html';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to login page
    exit();
}

// Retrieve albums from the database
$sql_album = "SELECT * FROM album WHERE idcreator = ?";
$stmt_album = $conn->prepare($sql_album);
$stmt_album->bind_param("i", $_SESSION['user_id']); // Assuming user_id is set in the session
$stmt_album->execute();
$result_album = $stmt_album->get_result();

// Display albums as buttons
echo "<div class='main-content'>";
echo "<div class='album-container'>";
echo "<div class='album-header'>";
echo "<h2>Albums</h2>";
echo '<a href="add-album.php" class="create-album-link"><i class="fas fa-regular fa-images"></i> Create Album</a></div>';
echo "<hr>";

if ($result_album->num_rows > 0) {
    while ($row_album = $result_album->fetch_assoc()) {
        // Check if imageurl is set and is not empty
        if (!empty($row_album['imageurl'])) {
            $imageUrl = "album_cover/" . htmlspecialchars($row_album['imageurl']);
            echo "<div class='album'>";
            echo "<a href='view-album.php?album_id=" . $row_album['idalbum'] . "'>";
            // Add an image element to display the album cover
            echo "<img src='" . $imageUrl . "' alt='" . htmlspecialchars($row_album['title']) . "' class='album-cover' />";
            echo "</a>";
            echo "<div class='album-title'>" . htmlspecialchars($row_album['title']) . "</div>";
            echo "</div>";
        } else {
            // Handle case where there is no image URL
            echo "<div class='album'>";
            echo "<a href='view-album.php?album_id=" . $row_album['idalbum'] . "'>";
            echo "<div class='album-placeholder'>" . htmlspecialchars($row_album['title']) . "</div>";
            echo "</a>";
            echo "<div class='album-title'>" . htmlspecialchars($row_album['title']) . "</div>";
            echo "</div>";
        }
    }
} else {
    echo "<p>No albums</p>";
}

echo "</div>";
echo "</div>";
// Close the album statement
$stmt_album->close();
$conn->close();
?>
</body>
</html>
