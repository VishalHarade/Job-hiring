<?php
include_once '../includes/db_connection.php';     

error_reporting(E_ALL);
ini_set('display_errors', 1);

// // Include the database connection
// include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get search criteria from form
    $search_keyword = $_GET["search_keyword"];

    // Search jobs based on keyword
    $sql = "SELECT jobs.*, companies.company_name FROM jobs INNER JOIN companies ON jobs.company_id = companies.company_id WHERE job_title LIKE '%$search_keyword%'";
    $result = $conn->query($sql);

    ?>
    <html>
    <head>
        <title>Job Search Results</title>
        <link rel="stylesheet" type="text/css" href="../css/search.css">
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
                                    <a href="../dashboard/student_login.php">As a Student</a>
                                    <a href="../dashboard/company_login.php">As a Company</a>
                                </div>
                            </li>
                            <li>
                                <a href="#">Register</a>
                                <div class="dropdown-content">
                                    <a href="../registration/student_registration.php">As a Student</a>
                                    <a href="../registration/company_registration.php">As a Company</a>
                                </div>
                            </li>
                        <li><a href="../index.php #abour-us">About us</a></li>
                    </ul>
                </div>
            </nav>
        <h1><center>Job Search Results</center></h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='job-card'>";
                echo "<div class='job-header'>";
                echo "<h2>" . $row['job_title'] . "</h2>";
                echo "<p class='location'>" . $row['location'] . "</p>";
                echo "</div>";
                echo "<div class='job-details'>";
                echo "<p>Company: " . $row['company_name'] . "</p>"; // Display Company Name
                echo "<p>Description: " . $row['description'] . "</p>";
                echo "<p>Qualifications: " . $row['qualifications'] . "</p>";
                echo "<p>Salary: " . $row['salary'] . "</p>";
                echo "<a href='../job_application/apply_job.php?job_id=" . $row['job_id'] . "' class='apply-button'>Apply Now</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-jobs'>No jobs found.</p>";
        }
        ?>
    </body>
    </html>
    <?php
}
?>
