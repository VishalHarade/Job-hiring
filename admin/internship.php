<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include_once '../includes/db_connection.php';

$error_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $application_deadline = $_POST['application_deadline'];

    // Insert internship details into the database
    $insert_query = "INSERT INTO internships (title, description, requirements, application_deadline) VALUES ('$title', '$description', '$requirements', '$application_deadline')";

    if ($conn->query($insert_query) === TRUE) {
        // Fetch student IDs
        $student_ids_query = "SELECT student_id FROM students";
        $student_ids_result = $conn->query($student_ids_query);
    
        if (!$student_ids_result) {
            die("Error fetching student IDs: " . $conn->error);
        }
    
        // Send notification to each student about the new internship
        if ($student_ids_result->num_rows > 0) {
            $message = "A new internship titled '$title' has been posted. Apply now!";
            // Escape single quotes in the message
            $message = mysqli_real_escape_string($conn, $message);

            while ($row = $student_ids_result->fetch_assoc()) {
                $student_id = $row['student_id'];
                $insert_notification_query = "INSERT INTO notifications (student_id, message, status) VALUES ($student_id, '$message', 'unread')";
                if (!$conn->query($insert_notification_query)) {
                    die("Error inserting notification: " . $conn->error);
                }
            }
        }
    
        // Redirect to a success page or the admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Error adding internship: " . $conn->error;
    }
    
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Internship - Admin </title>
    <style>
    body {
        font-family: 'Helvetica', sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    h1 {
        color: #333;
        text-align: center;
        margin-top: 30px;
    }

    form {
        background-color: #fff;
        width: 400px;
        margin: 0 auto;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input[type="text"],
    textarea,
    input[type="date"] {
        width: 95%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    textarea {
        height: 80px;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 12px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    a {
        color: #007bff;
        text-decoration: none;
        display: block;
        margin-top: 15px;
        text-align: center;
    }

    a:hover {
        text-decoration: underline;
    }

    p.error {
        color: red;
        text-align: center;
        margin-top: 10px;
    }
</style>

</head>
<body>
    <h1>Add Internship</h1>
    
    <?php if (!empty($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>
    
    <form action="" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>
        
        <label for="requirements">Requirements:</label>
        <textarea id="requirements" name="requirements"></textarea><br>
        
        <label for="application_deadline">Application Deadline:</label>
        <input type="date" id="application_deadline" name="application_deadline" required><br>
        
        <button type="submit">Add Internship</button><br>
        <a href="admin_dashboard.php">Go back to dashboard</a>
    </form>  
</body>
</html>
