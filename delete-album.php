<?php
session_start();
include 'db_connection.php'; // Adjust this path as necessary

$album_id = $_GET['album_id'];
$user_id = $_SESSION['user_id'];

// Security check: Make sure the album belongs to the user trying to delete it
// This query checks if the album exists and belongs to the logged-in user
$sql_check = "SELECT idalbum FROM album WHERE idalbum = ? AND idcreator = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $album_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // The album belongs to the user, proceed with deletion
    $sql_delete = "DELETE FROM album WHERE idalbum = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $album_id);
    $result_delete = $stmt_delete->execute();

    if ($result_delete) {
        echo "Album deleted successfully.";
        // Redirect to a different page if needed
     header("Location: gallery.php");
    } else {
        echo "Error deleting album.";
    }
    $stmt_delete->close();
} else {
    echo "Album not found or you do not have permission to delete this album.";
}

$stmt_check->close();
$conn->close();
?>

