<?php include_once '../includes/db_connection.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Application</title>
    <link rel="stylesheet" type="text/css" href="../css/apply_job.css">
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
                        <li><a href="index.php">Home</a></li>
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
                    <li><a href="../index.php#about-us">About us</a></li>
                </ul>
            </div>
        </nav>
    <h1>Job Application</h1>
    <form action="process_job_application.php" method="post" enctype="multipart/form-data">
        <label for="resume">Resume:</label>
        <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required><br>

        <label for="cover_letter">Cover Letter:</label>
        <textarea id="cover_letter" name="cover_letter" rows="4" required></textarea><br>

        <input type="hidden" name="job_id" value="<?php echo $_GET['job_id']; ?>"> 

        <input type="submit" value="Apply Now">
    </form>
    
    <!-- Display submission message here using JavaScript -->
    <div id="message">
        <script>
            var messageDiv = document.getElementById("message");
            <?php
            if (isset($success_message)) {
                echo 'messageDiv.innerHTML = "' . $success_message . '";';
            } elseif (isset($error_message)) {
                echo 'messageDiv.innerHTML = "' . $error_message . '";';
            }
            ?>
        </script>
        </div>
</body>
</html>
