<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

// Include the database connection
include_once '../includes/db_connection.php';

// Handle student deletion if student_id is provided
if (isset($_GET['delete_student_id'])) {
    $delete_student_id = $_GET['delete_student_id'];

    // Delete the student from the database
    $deleteSql = "DELETE FROM students WHERE student_id = '$delete_student_id'";
    if ($conn->query($deleteSql)) {
        header("Location: manage_students.php");
        exit();
    } else {
        $delete_error = "Error deleting student: " . $conn->error;
    }
}

// Fetch and display student data along with applied jobs and internships
$students_query = "SELECT s.*, GROUP_CONCAT(DISTINCT j.job_title) AS applied_jobs,
                   GROUP_CONCAT(DISTINCT i.title) AS applied_internships, a.status
                   FROM students s
                   LEFT JOIN job_applications a ON s.student_id = a.student_id
                   LEFT JOIN jobs j ON a.job_id = j.job_id
                   LEFT JOIN internship_applications ia ON s.student_id = ia.student_id
                   LEFT JOIN internships i ON ia.internship_id = i.internship_id
                   GROUP BY s.student_id";
$students_result = $conn->query($students_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
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
    <h1>Manage Students</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone number</th>
            <th>University number</th>
            <th>Applied Jobs</th>
            <th>Applied Internships</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        if ($students_result->num_rows > 0) {
            while ($student = $students_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $student['student_id'] . "</td>";
                echo "<td>" . $student['username'] . "</td>";
                echo "<td>" . $student['email'] . "</td>";
                echo "<td>" . $student['first_name'] . "</td>";
                echo "<td>" . $student['last_name'] . "</td>";
                echo "<td>" . $student['phone_number'] . "</td>";
                echo "<td>" . $student['university_number'] . "</td>";
                echo "<td>" . ($student['applied_jobs'] ? $student['applied_jobs'] : "N/A") . "</td>";
                echo "<td>" . ($student['applied_internships'] ? $student['applied_internships'] : "N/A") . "</td>";
                echo "<td>" . $student['status'] . "</td>";
                echo "<td><a href='manage_students.php?delete_student_id=" . $student['student_id'] . "' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No students found.</td></tr>";
        }
        ?>
    </table>
    
    <?php if (isset($delete_error)) { echo "<p>$delete_error</p>"; } ?>
    <div class="back">
    <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
