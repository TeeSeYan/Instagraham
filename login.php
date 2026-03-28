<?php

include 'db_connection.php'; // Include the database connection script

session_start(); // Start a new session

// Function to sanitize data to prevent SQL injection attacks
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

$error_message = ''; // Variable to hold the error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $username_email = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    // Validate that none of the fields are empty
    if (empty($username_email) || empty($password)) {
        $error_message = 'Please fill all the fields.';
    } else {
        // Select user from the database
        $stmt = $conn->prepare("SELECT id, username, email, password FROM user WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username_email, $username_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Authentication success
                // Store data in session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect user to home page
                header('Location: home.php');
                exit;
            } else {
                // Authentication failed
                $error_message = 'Invalid password.';
            }
        } else {
            $error_message = 'No account found with that username/email.';
        }
        $stmt->close();
    }
    $conn->close();

    // If there's an error, use JavaScript to alert the user
    if (!empty($error_message)) {
        echo "<script type='text/javascript'>alert('$error_message'); window.history.go(-1);</script>";
        exit;
    }
}
?>
