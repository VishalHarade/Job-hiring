<?php
include_once '../includes/db_connection.php'; // Include the database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = $_POST["job_title"];
    $description = $_POST["description"];
    $qualifications = $_POST["qualifications"];
    $location = $_POST["location"];
    $salary = $_POST["salary"];
    $company_id = $_SESSION["company_id"];

    // Insert job data into 'jobs' table using prepared statement
    $insert_query = "INSERT INTO jobs (company_id, job_title, description, qualifications, location, salary) 
                    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssssd", $company_id, $job_title, $description, $qualifications, $location, $salary);

    if ($stmt->execute()) {
        // Create notifications for students
        $message = "A new job '$job_title' has been posted!";
        $get_students_query = "SELECT student_id FROM students";
        $students_result = $conn->query($get_students_query);

        if ($students_result->num_rows > 0) {
            while ($student = $students_result->fetch_assoc()) {
                $student_id = $student["student_id"];
                // Insert student notifications using prepared statement
                $notification_sql = "INSERT INTO notifications (student_id, message) VALUES (?, ?)";
                $stmt = $conn->prepare($notification_sql);
                $stmt->bind_param("is", $student_id, $message);
                $stmt->execute();
            }
        }

        // Create notifications for admins
        $get_admins_query = "SELECT admin_id FROM admins";
        $admins_result = $conn->query($get_admins_query);

        if ($admins_result->num_rows > 0) {
            while ($admin = $admins_result->fetch_assoc()) {
                $admin_id = $admin["admin_id"];
                // Insert admin notifications using prepared statement
                $notification_sql = "INSERT INTO admin_notifications (admin_id, message) VALUES (?, ?)";
                $stmt = $conn->prepare($notification_sql);
                $stmt->bind_param("is", $admin_id, $message);
                $stmt->execute();
            }
        }

        // Display a success message as an alert using JavaScript
        echo '<script>alert("Job posted successfully!");</script>';
        header("Location: ../dashboard/company_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
