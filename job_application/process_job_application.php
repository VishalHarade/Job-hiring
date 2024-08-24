<?php
include_once '../includes/db_connection.php'; 
session_start();

// Check if student is logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: ../dashboard/student_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION["student_id"]; 
    $job_id = $_POST["job_id"];
    $cover_letter = $_POST["cover_letter"];

    $student_details_sql = "SELECT * FROM students WHERE student_id = '$student_id'";
    $student_details_result = $conn->query($student_details_sql);
    
    if ($student_details_result->num_rows == 1) {
        $student = $student_details_result->fetch_assoc();
        $status = "pending";
        $student_email = $student["email"];
        $student_first_name = $student["first_name"];
        $student_last_name = $student["last_name"];
        $student_phone_number = $student["phone_number"];
        $student_university_number = $student["university_number"]; 
    }
    
    // Process file upload
    $uploadDir = 'uploads/';  // Directory where files are uploaded
    $resumeFileName = $_FILES["resume"]["name"];
    $resumePath = $uploadDir . $resumeFileName;
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Creates directory with full permissions
    }
    
    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $resumePath)) {
        
        // Insert the resume path into the database
        $insertApplicationSql = "INSERT INTO job_applications (student_id, job_id, resume, cover_letter, status, student_email, student_first_name, student_last_name, student_phone_number, student_university_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insertApplicationSql);
        $stmt->bind_param("iissssssss", $student_id, $job_id, $resumePath, $cover_letter, $status, $student_email, $student_first_name, $student_last_name, $student_phone_number, $student_university_number);

        if ($stmt->execute()) {
            $success_message = "Application submitted successfully!";
            
            // Update the applied_students field in the jobs table
            $update_applied_students_query = "UPDATE jobs SET applied_students = CONCAT(applied_students, ',', '$student_id') WHERE job_id = $job_id";
            if ($conn->query($update_applied_students_query)) {
                echo "Applied students updated successfully!";
            } else {
                echo "Error updating applied students: " . $conn->error;
            }
            // Create notification for student
            $student_notification = "Your application for job ID: $job_id has been submitted.";
            $student_notification_sql = "INSERT INTO notifications (student_id, message) VALUES ('$student_id', '$student_notification')";
            $conn->query($student_notification_sql);

            // Create notification for admin
            $admin_notification = "A new application has been submitted for job ID: $job_id.";
            $get_admins_query = "SELECT admin_id FROM admins";
            $admins_result = $conn->query($get_admins_query);

            if ($admins_result->num_rows > 0) {
                while ($admin = $admins_result->fetch_assoc()) {
                    $admin_id = $admin["admin_id"];
                    $admin_notification_sql = "INSERT INTO admin_notifications (admin_id, message) VALUES ('$admin_id', '$admin_notification')";
                    $conn->query($admin_notification_sql);
                }
            }

            // Check if the resume file exists in the upload directory
            if (file_exists($resumePath)) {
                echo "Resume uploaded successfully!";
            } else {
                echo "Resume upload failed!";
            }
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    
        $stmt->close();
    } else {
        $error_message = "Error moving uploaded file.";
    }
    header("Location: apply_job.php?job_id=$job_id");
    exit();
}
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Apply for Job</title>
    </head>
    <body>
        <h1>Apply for Job</h1>
        <?php
        if (isset($success_message)) {
            echo "<p>$success_message</p>";
        } elseif (isset($error_message)) {
            echo "<p>$error_message</p>";
        } else {
            $job_id = $_GET['job_id'];
            $job_query = "SELECT * FROM jobs WHERE job_id = $job_id";
            $job_result = $conn->query($job_query);
            $job = $job_result->fetch_assoc();

            echo "<h2>" . $job['job_title'] . "</h2>";
            echo "<p>Description: " . $job['description'] . "</p>";
            echo "<p>Location: " . $job['location'] . "</p>";
            echo "<p>Salary: " . $job['salary'] . "</p>";

            echo "<form action='apply_job.php' method='post' enctype='multipart/form-data'>";
            echo "<input type='hidden' name='job_id' value='$job_id'>";
            echo "Cover Letter: <textarea name='cover_letter'></textarea><br>";
            echo "Resume: <input type='file' name='resume'><br>";
            echo "<input type='submit' value='Apply'>";
            echo "</form>";
            
        }
        ?>
        <div id="message">
        <script>
            var messageDiv = document.getElementById("message");
            <?php
            if (isset($success_message)) {
                echo 'messageDiv.innerHTML = "' . $success_message . '";';
            } elseif (isset($error_message)) {
                echo 'messageDiv.innerHTML = "' . $error_message . '";';
            }
            ?>
        </script>
        </div>
</body>
</html>
