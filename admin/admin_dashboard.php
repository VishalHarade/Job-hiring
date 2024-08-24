<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include_once '../includes/db_connection.php';

$admin_id = $_SESSION["admin_id"];

// Fetch admin's notifications
$notifications_sql = "SELECT * FROM admin_notifications WHERE admin_id = '$admin_id' ORDER BY created_at DESC";
$notifications_result = $conn->query($notifications_sql);
$internships_sql = "SELECT internship_id, title, requirements, application_deadline FROM internships";
$internships_result = $conn->query($internships_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
/* Style for the "Add Internship" link */
a.add-internship {
    display: block;
    margin-bottom: 20px;
    text-decoration: none;
    color: blue;
}

/* Style for the internships list */
.internships-list {
    margin-bottom: 20px;
}

/* Style for each internship item */
.internship-item {
    border: 1px solid #ccc;
    padding: 35px;
    margin-bottom: 15px;
    background-color: #f9f9f9;
    border-radius: 20px;
}

.internship-item h3 {
    margin-top: 0;
    color: #333;
}

.internship-item p {
    margin: 5px 0;
    color: #666;
}

.internship-item button {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 10px;
}

.internship-item button:hover {
    background-color: darkred;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .internship-item {
        padding: 10px;
    }
}

    </style>
</head>

<body>
    
    <h1>Welcome to Admin Dashboard</h1>
    
    <!-- Display admin-specific content here -->
    <h2>Your Notifications</h2>

    <?php
    if ($notifications_result->num_rows > 0) {
        $count = 0; // To limit the number of displayed notifications
        while ($notification = $notifications_result->fetch_assoc()) {
            $notification_id = $notification["notification_id"];
            $message = $notification["message"];
            $created_at = $notification["created_at"];
            
            echo "<div class='notification' id='notification-$notification_id'>";
            echo "<span class='delete-btn' onclick='handleDeleteClick($notification_id)'>&times;</span>";
            echo "<p>$message</p>";
            echo "<p>Created at: $created_at</p>";
            echo "</div>";

            $count++;
            if ($count >= 5) {
                break; // Display only 5 notifications
            }
        }
        
        // Display "View All Notifications" link
        echo "<a href='view_all_notifications.php'>View All Notifications</a>";
    } else {
        echo "No notifications.";
    }
    ?>
    <br><br><a href="internship.php">Add Internship</a><br>
    <div class="internships-list">
    <h2>Internships</h2>
    <?php
    if ($internships_result->num_rows > 0) {
        while ($internship = $internships_result->fetch_assoc()) {
            $internship_id = $internship['internship_id'];
            $internship_title = $internship['title'];
            $internship_requirements = $internship['requirements'];
            $application_deadline = $internship['application_deadline'];

            // Count the number of students who applied for this internship
            $applied_students_sql = "SELECT COUNT(*) AS num_students_applied FROM internship_applications WHERE internship_id = '$internship_id'";
            $applied_students_result = $conn->query($applied_students_sql);
            
            // Check if the query was successful
            if ($applied_students_result) {
                $num_students_applied_data = $applied_students_result->fetch_assoc();
                $num_students_applied = $num_students_applied_data['num_students_applied'];
            } else {
                // Error handling for failed query
                $num_students_applied = 'N/A';
            }

            echo "<div class='internship-item'>";
            echo "<h3>$internship_title</h3>";
            echo "<p>Requirements: $internship_requirements</p>";
            echo "<p>Application Deadline: $application_deadline</p>";
            echo "<p>Number of Students Applied: $num_students_applied </p>";
            // echo "<button onclick='handleDeleteInternship(\"$internship_title\", $internship_id)'>Delete</button>";
            echo "<button onclick='handleDeleteInternship($internship_id)'>Delete</button>";


            echo "</div>";
        }
    } else {
        echo "No internships available.";
    }
    ?>
</div>

    <script>
        function handleDeleteClick(notificationId) {
            var notificationToRemove = document.getElementById('notification-' + notificationId);

            if (notificationToRemove) {
                notificationToRemove.style.display = 'none';

                var xhr = new XMLHttpRequest();
                xhr.open("GET", "delete_notification.php?notification_id=" + notificationId, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Do nothing 
                    }
                };
                xhr.send();
            }
        }
        function handleDeleteInternship(internshipId) {
            console.log("Deleting internship with ID: " + internshipId);
            if (confirm("Are you sure you want to delete this internship?")) {
                window.location.href = "delete_internship.php?internship_id=" + internshipId;
            }
        }

    </script>
    <br>
    <a href="manage_jobs.php">manage jobs</a><br>
    <a href="manage_students.php">manage students</a><br>
    
    <a href="../dashboard/logout.php">Logout</a>
</body>
</html>
