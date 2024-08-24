<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include_once '../includes/db_connection.php';

// Check if the 'internship_id' parameter is provided and is numeric
if (isset($_GET['internship_id']) && is_numeric($_GET['internship_id'])) {
    $internship_id = $_GET['internship_id'];
    echo "Received internship ID: " . $internship_id;

    // Delete internship from the database
    $delete_query = "DELETE FROM internships WHERE internship_id = $internship_id";
    
    if ($conn->query($delete_query) === TRUE) {
        // Redirect to a success page or the admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Error deleting internship: " . $conn->error;
    }
} else {
    // Handle case where internship_id is not provided or not numeric
    $error_message = "Invalid internship ID.";
    echo $error_message; // Debugging line
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Delete Internship - Admin Panel</title>
</head>
<body>
    <h1>Delete Internship</h1>
    
    <?php if (!empty($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>
    
    <!-- You can add more content or confirmation here if needed -->
</body>
</html>
