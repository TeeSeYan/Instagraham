<?php
// Check if album_id is set in the URL and sanitize it
$album_id = isset($_GET['album_id']) ? filter_var($_GET['album_id'], FILTER_SANITIZE_NUMBER_INT) : 0;
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Image into Album</title>
    <link rel="stylesheet" href="css/form_style.css">
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
    ?>
    
    <div class="main-content">
        <!-- Image Form -->
        <div class="container">
          <img id='logo' src="web_decor/logo.png" alt="Instagraham Official Logo">
          <h2>Add Image into Album</h2>
          <form action="add-image-album2.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="album_id" value="<?php echo $album_id; ?>">
            <label for="uploadImage">Upload Image: <input type="file" id="uploadImage" name="image" required></label>
            <label for="imageTitle">Title:</label>
            <input type="text" id="imageTitle" name="title" required>
            <label for="photoComment">Comment:</label>
            <textarea id="photoComment" name="comment" required></textarea>
            <button type="submit">Upload Image</button>
          </form>
        </div>
    </div>

</body>
</html>
