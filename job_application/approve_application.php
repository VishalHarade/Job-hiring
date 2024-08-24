<?php
include_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $job_id = $_POST['job_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    
    // Update the status to 'Approved' in the job_applications table
    $approve_query = "UPDATE job_applications SET status = 'Approve' WHERE application_id = $application_id";
    
    if ($conn->query($approve_query)) {
        // Redirect back to the applicants view
        header("Location: view_applicants.php?job_id=$job_id");
        exit();
    } else {
        // Handle the error
        echo "Error updating application status: " . $conn->error;
    }
}
}
?>
