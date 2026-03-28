<html>
    <head>
    <title>Delete Image</title>
    <script type="text/javascript">
        // Function to show an alert and then redirect
        function showAlertAndRedirect(message, url) {
            alert(message);
            window.location.href = url; // Redirect the browser to the specified URL
        }
    </script>
    </head>
    <body>
<?php
// Connect to database
include 'db_connection.php';

// Create SQL query to delete message with idphoto=id
$q = 'DELETE FROM photo WHERE idphoto=' . $_GET['id'] . ';';

// Execute query and output a success/error message
if ($conn->query($q)) {
    echo '<script type="text/javascript">showAlertAndRedirect("Image deleted.", document.referrer);</script>';
} else {
    echo '<script type="text/javascript">showAlertAndRedirect("Something went wrong. Please contact your system administrator.", document.referrer);</script>';
}

?>
    </body>
</html>
