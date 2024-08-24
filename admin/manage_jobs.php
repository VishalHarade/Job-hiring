<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

// Include the database connection
include_once '../includes/db_connection.php';

// Handle job deletion if job_id is provided
if (isset($_GET['delete_job_id'])) {
    $delete_job_id = $_GET['delete_job_id'];

    // Delete the job from the database
    $deleteSql = "DELETE FROM jobs WHERE job_id = '$delete_job_id'";
    if ($conn->query($deleteSql)) {
        header("Location: manage_jobs.php");
        exit();
    } else {
        $delete_error = "Error deleting job: " . $conn->error;
    }
}

// Fetch and display job listings along with company name
$jobs_query = "SELECT j.*, c.company_name FROM jobs j
               JOIN companies c ON j.company_id = c.company_id";
$jobs_result = $conn->query($jobs_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Jobs</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }

    h1 {
        color: #333;
        text-align: center;
        margin: 30px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e0e0e0;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .delete-link {
        color: #dc3545;
        text-decoration: none;
        cursor: pointer;
    }

    .delete-link:hover {
        text-decoration: underline;
    }

    .back {
        margin-top: 20px;
        text-align: center;
    }

    p.error {
        color: red;
        text-align: center;
        margin-top: 10px;
    }
</style>

</head>
<body>
    <h1>Manage Jobs</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Company Name</th>
            <th>Job Title</th>
            <th>Description</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Applicants</th>
            <th>Action</th>
        </tr>
        <?php
        if ($jobs_result->num_rows > 0) {
            while ($job = $jobs_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $job['job_id'] . "</td>";
                echo "<td>" . $job['company_name'] . "</td>";
                echo "<td>" . $job['job_title'] . "</td>";
                echo "<td>" . $job['description'] . "</td>";
                echo "<td>" . $job['location'] . "</td>";
                echo "<td>" . $job['salary'] . "</td>";
                // Fetch and display applicants for the job
                $applicants_query = "SELECT s.first_name, s.last_name FROM job_applications a
                                    JOIN students s ON a.student_id = s.student_id
                                    WHERE a.job_id = " . $job['job_id'];
                $applicants_result = $conn->query($applicants_query);
                
                if ($applicants_result->num_rows > 0) {
                    echo "<td>";
                    while ($applicant = $applicants_result->fetch_assoc()) {
                        echo $applicant['first_name'] . ' ' . $applicant['last_name'] . "<br>";
                    }
                    echo "</td>";
                } else {
                    echo "<td>N/A</td>";
                }
                echo "<td><a href='manage_jobs.php?delete_job_id=" . $job['job_id'] . "' onclick='return confirm(\"Are you sure you want to delete this job?\")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No jobs found.</td></tr>";
        }
        ?>
    </table>
    
    <?php if (isset($delete_error)) { echo "<p>$delete_error</p>"; } ?>
    <div class="back">
    <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
