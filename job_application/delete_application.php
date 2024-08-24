<?php
include_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $job_id = $_POST['job_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];

    // Delete the application from the job_applications table
    $delete_query = "DELETE FROM job_applications WHERE application_id = $application_id";
    
    if ($conn->query($delete_query)) {
        // Redirect back to the applicants view
        header("Location: view_applicants.php?job_id=$job_id");
        exit();
    } else {
        // Handle the error
        echo "Error deleting application: " . $conn->error;
    }
}
}
?>
