<?php
session_start();

// Check if company is logged in
if (!isset($_SESSION["company_id"])) {
    header("Location: company_login.php");
    exit();
}

// Include the database connection
include_once '../includes/db_connection.php';

// Fetch and display company-specific data here
$company_id = $_SESSION["company_id"];
$company_name_sql = "SELECT company_name FROM companies WHERE company_id = '$company_id'";
$company_name_result = $conn->query($company_name_sql);

$company_name = "";
if ($company_name_result->num_rows == 1) {
    $company_name_row = $company_name_result->fetch_assoc();
    $company_name = $company_name_row['company_name'];
}

// Fetch and display company's posted jobs
$jobs_sql = "SELECT * FROM jobs WHERE company_id = '$company_id' ORDER BY job_id DESC";
$jobs_result = $conn->query($jobs_sql);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="../css/company_dashboard.css">
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
    <div class="header">
        <h1><?php echo $company_name; ?><br>Welcome to Your Company Dashboard </h1>
        <a href="../job_posting/post_job.php" class="btn">Post a Job</a>
    </div>
    <div class="container">
        <div class="dashboard">
            <h2>Posted Jobs</h2>
                <?php
                // Fetch and display company-specific data here
                $company_id = $_SESSION["company_id"];

                // Fetch and display company's posted jobs
                $jobs_sql = "SELECT * FROM jobs WHERE company_id = '$company_id' ";
                $jobs_result = $conn->query($jobs_sql);
                //  echo $company_id;
                // echo count($jobs_result->fetch_all());
                if ($jobs_result->num_rows > 0) {
                    while ($job = $jobs_result->fetch_assoc()) {
                        echo "<div class='job'>";
                        echo "<h3>" . $job['job_title'] . "</h3>";
                        echo "<p>Description: " . $job['description'] . "</p>";
                        echo "<p>Qualifications: " . $job['qualifications'] . "</p>";
                        echo "<p>Location: " . $job['location'] . "</p>";
                        echo "<p>Salary: " . $job['salary'] . "</p>";
                        echo "<p><a href='../job_application/view_applicants.php?job_id=" . $job['job_id'] . "'>View Applicants</a></p>";
                        echo "<p><a href='../job_application/delete_job.php?job_id=" . $job['job_id'] . "'>Delete Job</a></p>";

                        echo "<hr>";
                        // echo "jobs".$job['job_id'];
                        
                        if (!empty($job['applied_students'])) {
                            $applied_students = explode(',', $job['applied_students']);
                            foreach ($applied_students as $student_id) {
                                // Fetch student details from the students table using $student_id
                                $student_details_sql = "SELECT * FROM students WHERE student_id = '$student_id'";
                                $student_details_result = $conn->query($student_details_sql);
                                $student = $student_details_result->fetch_assoc();
                                
                                // Display student details
                                echo "<div class='applied-student'>";
                                echo "<h4>Applied Student</h4>";
                                echo "<p>Name: " . $student['first_name'] . " " . $student['last_name'] . "</p>";
                                echo "<p>Email: " . $student['email'] . "</p>";
                                echo "<p>Phone Number: " . $student['phone_number'] . "</p>";
                                echo "<p>University Number: " . $student['university_number'] . "</p>";
                                echo "</div>";
                            }
                        } else {
                        //  echo "<p>No students have applied for this job.</p>";
                        }
                        echo "</div>";
                        echo "<hr>";
                    }
                } else {
                    echo "No jobs posted by this company.";
                }
                ?><br>  
            </div>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>