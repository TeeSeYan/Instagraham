<?php
session_start();
include 'db_connection.php'; // Your database connection file
include 'nav_bar.html'; // Your navigation bar file

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to login page
    exit();
}

// Initialize a variable to hold potential alert messages
$alertMessage = "";

$userId = $_SESSION['user_id'];
$photoId = isset($_GET['id']) ? $_GET['id'] : 0; // Ensure the photo ID is obtained from the query parameter

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the form submission
    $albumId = $_POST['album'];
    $photoId = $_POST['photo_id']; // Using the hidden input to carry forward the photo ID

    // Prepare the SQL statement to update the photo's album
    $stmt = $conn->prepare("UPDATE photo SET idalbum = ? WHERE idphoto = ?");
    $stmt->bind_param("ii", $albumId, $photoId);

    // Execute the statement
    if ($stmt->execute()) {
        $alertMessage = "Photo was successfully added to the album.";
    } else {
        $alertMessage = "An error occurred.";
    }

    // Close statement
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Image to Album</title>
    <link rel="stylesheet" href="css/form_style.css">
    <script type="text/javascript">
        // Function to show alert if there's a message
        function showAlert(message) {
            if(message) {
                alert(message);
            }
        }
    </script>
</head>
<body onload="showAlert('<?php echo addslashes($alertMessage); ?>')">
    <div class="main-content">
        <div class="container">
            <img id='logo' src="web_decor/logo.png" alt="Instagraham Official Logo">
            <h2>Add Image into Album</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $photoId; ?>" method="post">
                <input type="hidden" name="photo_id" value="<?php echo $photoId; ?>">
                <label for="album" >Select Album:</label>
                <select id="album" name="album" required>
                    <option value="" disabled selected>Select your album</option>
                    <?php
                    $query = "SELECT idalbum, title FROM album WHERE idcreator = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['idalbum'] . "'>" . htmlspecialchars($row['title']) . "</option>";
                    }
                    $stmt->close();
                    ?>
                </select>
                <br>
                <button>Add to Album</button>
            </form>
        </div>
    </div>
</body>
</html>
