<?php
include_once 'includes/db_connection.php';

if (isset($_GET['student_id']) && isset($_GET['notification_id'])) {
    $student_id = $_GET['student_id'];
    $notification_id = $_GET['notification_id'];

    // Delete the notification from the database
    $delete_query = "DELETE FROM notifications WHERE student_id = '$student_id' AND notification_id = '$notification_id'";
    if ($conn->query($delete_query)) {
        echo "Notification deleted successfully.";
    } else {
        echo "Error deleting notification: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
