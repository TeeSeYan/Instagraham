<?php
session_start();
include 'db_connection.php'; // Your database connection file
include 'nav_bar.html';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to login page
    exit();
}

$userId = $_SESSION['user_id'];
$userData = fetchUserData($conn, $userId);

function fetchUserData($conn, $userId) {
    // Use the MySQLi connection to fetch user data
    $stmt = $conn->prepare("SELECT name, website, imageurl, bio FROM creator WHERE idcreator = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Retrieve photos from the database
$sql_photo = "SELECT idphoto, title, imageurl FROM photo WHERE idcreator = ?";
$stmt_photo = $conn->prepare($sql_photo);
$stmt_photo->bind_param("i", $_SESSION['user_id']); // Assuming user_id is set in the session
$stmt_photo->execute();
$result_photo = $stmt_photo->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Define the target directory and file path
        $target_dir = "user_photo/";
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        
        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            // Update the imageurl in the database with the new file path
            $stmt = $conn->prepare("UPDATE creator SET imageurl = ? WHERE idcreator = ?");
            $stmt->bind_param("si", $target_file, $userId);
            $stmt->execute();
            // Fetch the updated data again
            $userData = fetchUserData($conn, $userId);
        } else {
            // Handle error if the file couldn't be moved
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Handle bio update
    if (isset($_POST['bio'])) {
        // Use your MySQLi connection to update the bio
        $stmt = $conn->prepare("UPDATE creator SET bio = ? WHERE idcreator = ?");
        $stmt->bind_param("si", $_POST['bio'], $userId);
        $stmt->execute();
        // Fetch the updated data again
        $userData = fetchUserData($conn, $userId);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/profile_style.css">

    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>
    <div class='main-content'>
    <div class='user-container'>
    <!-- Profile picture form -->
    <form action="profile.php" method="post" enctype="multipart/form-data">
    <img id='user-profile' src="<?= !empty($userData['imageurl']) ? htmlspecialchars($userData['imageurl']) : 'user_photo/profilepic_null.jpeg' ?>" alt="Profile Picture">
    <p id='user-name'><?= htmlspecialchars($userData['name']) ?></p>
    <p>Email: <?= htmlspecialchars($userData['website']) ?></p>
    <p>Change Profile Picture: <input type="file" name="profile_picture"></p>
    <p>Bio:</p>
    <textarea name="bio" placeholder="Enter your bio here"><?= htmlspecialchars($userData['bio']) ?></textarea>
    <div class="button-container">
    <button type="submit" onclick="return confirm('Save changes?');">Save Profile</button>
    </form>
    <form action="delete-account.php" method="post" id="deleteAccountForm">
    <button type="submit" id="delete-account-button" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
    </form>
    </div>

    <!-- Display photos as images -->
    <hr>
    <h2>Photos</h2>
    <div class='photo-container'>
    <?php while ($row = $result_photo->fetch_assoc()): ?>
    <div class='photo-post'>
        <img src='uploads/<?= htmlspecialchars($row['imageurl']) ?>' alt='Photo' id="post">
            <p id="post-title">Title: <?= htmlspecialchars($row['title']) ?></p>
            <div id="postbtn-container">
            <a href='edit-image.php?id=<?= $row['idphoto'] ?>' class='edit-btn'>Edit</a>
            <a href='add-image-album3.php?id=<?= $row['idphoto'] ?>' class='addToAlbum-btn'>Add to album</a>
            <a href='delete-image.php?id=<?= $row['idphoto'] ?>' id='delete-btn'>Delete</a>
            </div>
                </div>
    <?php endwhile; ?>
    </div>
    </div>
    </div>
</body>
</html>

<?php
// Close the photo statement and database connection
$stmt_photo->close();
$conn->close();
?>
