<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
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
        <div class="container">
            <img id='logo' src="web_decor/logo.png" alt="Instagraham Official Logo">
            <h2>Add New Album</h2>
            
            <!-- Note the enctype attribute added here -->
            <form id="albumForm" action="add-album2.php" method="POST" enctype="multipart/form-data"> 
                <label for="albumTitle">Title:</label>
                <input type="text" id="albumTitle" name="albumTitle" required>
                <!-- Album Cover File Input -->
                <label for="albumCover">Album Cover: <input type="file" id="albumCover" name="image"></label>
                <button type="submit">Create Album</button>
            </form>
        </div>
    </div>

</body>
</html>
