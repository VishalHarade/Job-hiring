<?php
include_once '../includes/db_connection.php';

// Get the company_id from the URL
$company_id = isset($_GET['company_id']) ? $_GET['company_id'] : null;

if ($company_id) {
    // Fetch jobs posted by this company
    $jobs_sql = "SELECT * FROM jobs WHERE company_id = '$company_id' ORDER BY job_id DESC";
    $jobs_result = $conn->query($jobs_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Company's Posted Jobs</title>
    <link rel="stylesheet" type="text/css" href="../css/company_profile.css">
</head>
<body>
    <h1>Company's Posted Jobs</h1>
    <?php
    if ($jobs_result->num_rows > 0) {
        while ($job = $jobs_result->fetch_assoc()) {
            echo "<div class='job-details'>";
            echo "<h2 class='job-title'>" . $job['job_title'] . "</h2>";
            echo "<p class='description'>Description: " . $job['description'] . "</p>";
            echo "<p class='qualifications'>Qualifications: " . $job['qualifications'] . "</p>";
            echo "<p class='location'>Location: " . $job['location'] . "</p>";
            echo "<p class='salary'>Salary: " . $job['salary'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No jobs posted by this company.";
    }
    ?>
</body>
</html>
<?php
} else {
    echo "Invalid or missing company ID.";
}
?>
