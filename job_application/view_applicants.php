<?php
include_once '../includes/db_connection.php';

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch job details
    $job_query = "SELECT * FROM jobs WHERE job_id = $job_id";
    $job_result = $conn->query($job_query);
    
    // Check if the job exists
    if ($job_result->num_rows > 0) {
        $job = $job_result->fetch_assoc();

        // Fetch applicants for this job
        $applicants_query = "SELECT a.*, s.first_name, s.last_name, s.email, s.phone_number, s.university_number, s.profile_photo_path
                            FROM job_applications a 
                            JOIN students s ON a.student_id = s.student_id 
                            WHERE a.job_id = $job_id";
        $applicants_result = $conn->query($applicants_query);
        
      
    } else {
        // Handle the case when the job doesn't exist
        header("Location: company_dashboard.php");
        exit();
    }
} else {
    // Handle the case when job_id is not set
    header("Location: company_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Applicants for <?php echo $job['job_title']; ?></title>
    <link rel="stylesheet" href="../css/view_application.css">
</head>
<body>
    <h1>Applicants for <?php echo $job['job_title']; ?></h1>
    
    <?php if ($applicants_result->num_rows > 0): ?>
        <?php while ($applicant = $applicants_result->fetch_assoc()): ?>
            <div class="applicant-box">
                <h4>Applied Student</h4>
                <p>Name: <?php echo $applicant['student_first_name'] . ' ' . $applicant['student_last_name']; ?></p>
                <p>Email: <?php echo $applicant['student_email']; ?></p>
                <p>Phone Number: <?php echo $applicant['student_phone_number']; ?></p>
                <p>University Number: <?php echo $applicant['student_university_number']; ?></p>
                <p>Status: <?php echo $applicant['status']; ?></p>
                
                <?php if ($applicant['resume']): ?>
                    <p>Resume: <a href="<?php echo $applicant['resume']; ?>" target="_blank">View Resume</a></p>
                <?php else: ?>
                    <p>Resume: Not uploaded</p>
                <?php endif; ?>
                <div class="applicant-buttons">
                    <!-- Approve Button -->
                    <form method="post" action="approve_application.php">
                        <input type="hidden" name="application_id" value="<?php echo $applicant['application_id']; ?>">
                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                        <button type="submit" style="background-color: #28a745; color: #fff;">Approve</button>
                    </form>
                    <!-- Delete Button -->
                    <form method="post" action="delete_application.php">
                        <input type="hidden" name="application_id" value="<?php echo $applicant['application_id']; ?>">
                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                        <button type="submit" style="background-color: #dc3545; color: #fff;">Delete</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
        
    <?php else: ?>
        <p>No applicants yet.</p>
    <?php endif; ?>

    <a href="../dashboard/company_dashboard.php">Back to Dashboard</a>
</body>
</html>