<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/home_style.css">
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

$q = "SELECT photo.*, creator.name AS creator_name FROM photo INNER JOIN creator ON photo.idcreator = creator.idcreator ORDER BY photo.idphoto DESC;";

// Execute SQL query. If there is an error, print an error message.
if ($res = $conn->query($q)) {
    // Set the pointer to the first result. If there are no results, tell the user. 
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {  // Fetch the associative array for the next row
            // Output the message stored in that row
            echo '<div class="main-content">';
            echo '<div class="container">';
            echo "<p>Creator: " . $row['creator_name'] . "</p>\n";
            echo "<p>Title: " . $row['title'] . "</p>\n";
            echo '<img src="uploads/' . $row['imageurl'] . '" alt="Image" width="750" height="500">';
       
            // Check if the current user is the creator of this post
            if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['idcreator']) {
                echo "<a href=\"edit-image.php?id=" . $row['idphoto'] . "\"><button>Edit</button></a>\n ";
                echo "<a href=\"delete-image.php?id=" . $row['idphoto'] . "\"><button>Delete</button></a>\n";
            }
            echo "<p>Caption:<br>" . $row['comment'] . "</p>\n";
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo 'No images found'; // No results
    }
} else {
    echo "Something went wrong x_x! Please contact the customer service!";
} 
?>
</body>
</html>
