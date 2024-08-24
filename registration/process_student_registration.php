<?php
include_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $university_number = $_POST["university_number"];
    
    // Handle profile photo upload
    $targetDir = '../profile_photos/';
    $profilePhoto = $_FILES['profile_photo']['name'];
    $targetFile = $targetDir . basename($profilePhoto);
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png");

    if (in_array($fileExtension, $allowedExtensions) && move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFile)) {
        $profilePhotoPath = $targetFile;

        // Insert data into 'students' table
        $sql = "INSERT INTO students (username, password, email, first_name, last_name, phone_number, university_number, profile_photo_path)
                VALUES ('$username', '$password', '$email', '$first_name', '$last_name', '$phone_number', '$university_number', '$profilePhotoPath')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
            
            // Create notification for admin
            $admin_notification = "A new student registration: $username.";
            $get_admins_query = "SELECT admin_id FROM admins";
            $admins_result = $conn->query($get_admins_query);

            if ($admins_result->num_rows > 0) {
                while ($admin = $admins_result->fetch_assoc()) {
                    $admin_id = $admin["admin_id"];
                    $admin_notification_sql = "INSERT INTO admin_notifications (admin_id, message) VALUES ('$admin_id', '$admin_notification')";
                    $conn->query($admin_notification_sql);
                }
            }
            
            header("Location: ../dashboard/student_login.php"); // Redirect to the login page
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload profile photo or invalid file format.";
    }
}
?>
