<?php
// db_connect.php
$conn = new mysqli('localhost', 'root', '', '5214cw');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

