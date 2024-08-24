<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}
include_once '../includes/db_connection.php'; 
$student_id = $_SESSION["student_id"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/student_dash.css">
    <link rel="stylesheet" href="../css/nav.css">
</head>
<body>
        <!-- navbar -->
        <nav>
            <div class="navbar">
                <div class="logo">
                    <a href="../admin/admin_login.php">Job Affairs</a>
                </div>
                <ul class="nav-links">
                        <li><a href="../index.php">Home</a></li>
                        <li>
                            <a href="#">Login</a>
                            <div class="dropdown-content">
                                <a href="student_login.php">As a Student</a>
                                <a href="company_login.php">As a Company</a>
                            </div>
                        </li>
                        <li>
                            <a href="#">Register</a>
                            <div class="dropdown-content">
                                <a href="../registration/student_registration.php">As a Student</a>
                                <a href="../registration/company_registration.php">As a Company</a>
                            </div>
                        </li>
                    <li><a href="">About us</a></li>
                </ul>
            </div>
        </nav>
    <div class="container">
        <?php
        $student_query = "SELECT first_name, last_name FROM students WHERE student_id = '$student_id'";
        $student_result = $conn->query($student_query);
        
        if ($student_result->num_rows == 1) {
            $student = $student_result->fetch_assoc();
            $student_name = $student['first_name'] . ' ' . $student['last_name'];
        } else {
            $student_name = "Student";
        }
        ?>
        
        <div class="profile">
            <h2>Your Profile</h2>
            <p>Welcome, <?php echo $student_name; ?></p>
            
            <?php
            $profile_query = "SELECT * FROM students WHERE student_id = '$student_id'";
            $profile_result = $conn->query($profile_query);
            
            if ($profile_result->num_rows == 1) {
                $profile = $profile_result->fetch_assoc();
                if ($profile['profile_photo_path']) {
                    echo "<img class='profile-photo' src='" . $profile['profile_photo_path'] . "' alt='Profile Photo'>";
                } else {
                    echo "<p>No profile photo uploaded.</p>";
                }
                echo "<p>Username: " . $profile['username'] . "</p>";
                echo "<p>Email: " . $profile['email'] . "</p>";
                echo "<p>First Name: " . $profile['first_name'] . "</p>";
                echo "<p>Last Name: " . $profile['last_name'] . "</p>";
                echo "<p>Phone Number: " . $profile['phone_number'] . "</p>";
                echo "<p>University Number: " . $profile['university_number'] . "</p>";
                              
            }
            
            ?>
        </div>
        <div class="notifications">
            <h2>Notifications</h2>
            <?php
            // Display student's notifications
            $notifications_sql = "SELECT * FROM notifications WHERE student_id = '$student_id' ORDER BY created_at DESC";
            $notifications_result = $conn->query($notifications_sql);

            if ($notifications_result->num_rows > 0) {
                echo "<div class='notification-container'>";
                while ($notification = $notifications_result->fetch_assoc()) {
                    $message = $notification["message"];
                    $status = $notification["status"];
                    $notificationId = $notification["notification_id"];

                    echo "<div class='notification-container' id='notification-$notificationId'>";
                    echo "<div class='notification $status'>";
                    echo "<span class='delete-btn' onclick='handleDeleteClick($student_id, $notificationId)'>&times</span>";
                    echo "<p class='notification-text'>$message</p>";
                    echo "</div>";
                    echo "</div>";

                    // Display a confirmation message upon successful deletion
                    if (isset($_GET['deleted_notification_id']) && $_GET['deleted_notification_id'] == $notificationId) {
                        echo "<p class='delete-success'>Notification deleted successfully.</p>";
                    }
                }
                echo "</div>";
            } else {
                echo "No notifications.";
            }
            ?>
            <a href="student_notification.php">view all notification</a>
        </div>

        <br>
        <div class="job-applications">
            <h2>Your Job Applications</h2>
            <?php
            // Display student's job applications
            $job_applications_sql = "SELECT * FROM job_applications WHERE student_id = '$student_id'";
            $job_applications_result = $conn->query($job_applications_sql);

            if ($job_applications_result->num_rows > 0) {
                while ($job_application = $job_applications_result->fetch_assoc()) {
                    $job_id = $job_application['job_id'];
                    $application_date = $job_application['application_date'];

                    // Fetch job details based on job_id
                    $job_query = "SELECT job_title FROM jobs WHERE job_id = '$job_id'";
                    $job_result = $conn->query($job_query);
                    $job = $job_result->fetch_assoc();

                    if ($job) {
                        $job_title = $job['job_title'];
                        $status = $job_application['status']; 

                        
                        $cardClass = '';
                        if ($status === 'Applied') {
                            $cardClass = 'applied-card';
                        } elseif ($status === 'In Review') {
                            $cardClass = 'review-card';
                        } elseif ($status === 'Accepted') {
                            $cardClass = 'accepted-card';
                        } elseif ($status === 'Rejected') {
                            $cardClass = 'rejected-card';
                        }

                        echo "<div class='job-application-card $cardClass'>";
                        echo "<p>Job Title: $job_title</p>";
                        echo "<p>Application Date: $application_date</p>";
                        echo "<p>Status: $status</p>";
                        echo "</div>";
                    }
                }
            } else {
                echo "No job applications.";
            }
            ?>
        </div>
        
        <script>
            function handleDeleteClick(notificationId, studentId) {
                var notificationToRemove = document.getElementById('notification-' + notificationId);

                if (notificationToRemove) {
                    notificationToRemove.style.display = 'none';

                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "delete_notification.php?notification_id=" + notificationId + "&studentId=" + studentId, true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Reload the page after successful deletion
                            location.reload();   
                        }
                    };
                    xhr.send();
                }
            }

   

            function handleNotificationClick(notificationId, status) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "update_notification.php?notification_id=" + notificationId + "&status=" + status, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Reload 
                        location.reload();
                    }
                };
                xhr.send();
            }


        </script>
        <br><br>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
