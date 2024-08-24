<?php
session_start();
include_once 'includes/db_connection.php';

if (!isset($_SESSION["student_id"])) {
    header("Location: dashboard/student_login.php");
    exit();
}

$internship_id = $_GET["internship_id"];

// Fetch  internship details
$internship_query = "SELECT title, description, requirements, application_deadline FROM internships WHERE internship_id = '$internship_id'";
$internship_result = $conn->query($internship_query);
$internship_data = $internship_result->fetch_assoc();

$student_id = $_SESSION["student_id"];

// Process the application 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert application data into internship_applications table
    $insert_application_query = "INSERT INTO internship_applications (student_id, internship_id)
                                 VALUES ('$student_id', '$internship_id')";

    if ($conn->query($insert_application_query) === TRUE) {
        // Insert application notification for admin
        $notification_message = "A student has applied for the internship: {$internship_data['title']}";
        $insert_notification_query = "INSERT INTO admin_notifications (admin_id, message, created_at)
                                      VALUES (1, '$notification_message', NOW())"; 

        $conn->query($insert_notification_query);

      
        echo "<script>alert('Application submitted successfully!');</script>";
    } else {
        //  error
        $error_message = "Error submitting application: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Internship</title>
    <link rel="stylesheet" href="css/nav.css">
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


header {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 20px 0;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.internship-card {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 10px;
    background-color: #f9f9f9;
    margin: 20px 0;
}

.internship-info {
    margin-bottom: 10px;
}

.internship-info h3 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
}

/* error message */
.error-message {
    color: red;
    margin-top: 20px;
    text-align: center;
}

/*  apply button */
button {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #333;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #555;
}

.go-back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #333;
    text-decoration: none;
    transition: color 0.3s;
}

.go-back-link:hover {
    color: #555;
}
</style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar">
            <div class="logo">
                <a href="admin/admin_login.php">Job Affairs</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li>
                    <a href="#">Login</a>
                    <div class="dropdown-content">
                        <a href="dashboard/student_login.php">As a Student</a>
                        <a href="dashboard/company_login.php">As a Company</a>
                    </div>
                </li>
                <li>
                    <a href="#">Register</a>
                    <div class="dropdown-content">
                        <a href="registration/student_registration.php">As a Student</a>
                        <a href="registration/company_registration.php">As a Company</a>
                    </div>
                </li>
                <li><a href="#about-us">About us</a></li>
            </ul>
        </div>
    </nav>

    <header>
        <h1>Apply for Internship</h1>
    </header>

    <div class="container">
        <h2>Internship Details</h2>
        <div class="internship-card">
            <div class="internship-info">
                <h3>Title:</h3>
                <p><?php echo $internship_data['title']; ?></p>
            </div>
            <div class="internship-info">
                <h3>Description:</h3>
                <p><?php echo $internship_data['description']; ?></p>
            </div>
            <div class="internship-info">
                <h3>Requirements:</h3>
                <p><?php echo $internship_data['requirements']; ?></p>
            </div>
            <div class="internship-info">
                <h3>Application Deadline:</h3>
                <p><?php echo $internship_data['application_deadline']; ?></p>
            </div>
        </div>

        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>

        <form action="" method="post">
    
            <button type="submit">Apply</button>
        </form>

        <a href="index.php" class="go-back-link">Go back</a>
    </div>
</body>
</html>
