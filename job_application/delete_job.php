<?php
include_once '../includes/db_connection.php';

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Delete the job from the database
    $delete_query = "DELETE FROM jobs WHERE job_id = $job_id";
    if ($conn->query($delete_query)) {
        // Redirect back to the company dashboard after deletion
        header("Location: ../dashboard/company_dashboard.php");
        exit();
    } else {
        // Handle error if deletion fails
        echo "Error deleting job: " . $conn->error;
    }
}
?>
