<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include_once '../includes/db_connection.php';

$admin_id = $_SESSION["admin_id"];

// Fetch all admin's notifications
$notifications_sql = "SELECT * FROM admin_notifications WHERE admin_id = '$admin_id' ORDER BY created_at DESC";
$notifications_result = $conn->query($notifications_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Notifications</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_dashboard.css">
    <style>
        
.notification {
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px;
    position: relative;
}

.delete-btn {
    position: absolute;
    top: 5px;
    right: 10px;
    cursor: pointer;
    color: red;
}
    </style>
</head>
<body>
    <h1>All Notifications</h1>

    <?php
    if ($notifications_result->num_rows > 0) {
        while ($notification = $notifications_result->fetch_assoc()) {
            $notification_id = $notification["notification_id"];
            $message = $notification["message"];
            $created_at = $notification["created_at"];

            echo "<div class='notification' id='notification-$notification_id'>";
            echo "<span class='delete-btn' onclick='handleDeleteClick($notification_id)'>&times;</span>";
            echo "<p>$message</p>";
            echo "<p>Created at: $created_at</p>";
            echo "</div>";
        }
    } else {
        echo "No notifications.";
    }
    ?>

    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <script>
    function handleDeleteClick(notificationId) {
        if (confirm("Are you sure you want to delete this notification?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "delete_notification.php?notification_id=" + notificationId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var notificationToRemove = document.getElementById('notification-' + notificationId);
                    if (notificationToRemove) {
                        notificationToRemove.style.display = 'none';
                    }
                }
            };
            xhr.send();
        }
    }
</script>

</body>
</html>
