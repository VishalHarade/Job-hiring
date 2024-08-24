<?php
include_once 'includes/db_connection.php';

if (isset($_GET['notification_id']) && isset($_GET['status'])) {
    $notification_id = $_GET['notification_id'];
    $status = $_GET['status'];

    // Update the status in the database
    $update_query = "UPDATE notifications SET status = '$status' WHERE notification_id = '$notification_id'";
    $conn->query($update_query);
}
?>
