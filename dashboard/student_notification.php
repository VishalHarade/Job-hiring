<?php include_once '../includes/db_connection.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Student's Notifications</title>
    <link rel="stylesheet" type="text/css" href="../css/notification.css">
</head>
<body>
    <h1>Student's Notifications</h1>
    <?php
    session_start();
    if (isset($_SESSION["student_id"])) {
        $student_id = $_SESSION["student_id"];

        
        $notifications_sql = "SELECT * FROM notifications WHERE student_id = '$student_id' ORDER BY created_at DESC";
        $notifications_result = $conn->query($notifications_sql);

        if ($notifications_result->num_rows > 0) {
            while ($notification = $notifications_result->fetch_assoc()) {
                $message = $notification["message"];
                $status = $notification["status"];

                echo "<div class='notification $status'>$message</div>";
            }
        } else {
            echo "No notifications.";
        }
    } else {
        echo "Please log in to view your notifications.";
    }
    ?>
</body>
</html>
