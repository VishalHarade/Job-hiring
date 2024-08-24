<?php include_once '../includes/db_connection.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Post a Job</title>
    <link rel="stylesheet" type="text/css" href="../css/job_post.css">
</head>
<body>
    <div class="form-container">
    <h1>Post a Job</h1>
    <form action="process_job_posting.php" method="post">
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br>

        <label for="qualifications">Qualifications:</label>
        <textarea id="qualifications" name="qualifications" rows="4" required></textarea><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br>

        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" required><br>

        <input type="submit" value="Post Job">
    </form>
    </div>
</body>
</html>
