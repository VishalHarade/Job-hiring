<?php
include_once '../includes/db_connection.php';

if (isset($_GET['notification_id'])) {
    $notification_id = $_GET['notification_id'];

    // Delete the notification from the database
    $deleteSql = "DELETE FROM admin_notifications WHERE notification_id = '$notification_id'";
    if ($conn->query($deleteSql)) {
        echo "Notification deleted successfully.";
    } else {
        echo "Error deleting notification: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
