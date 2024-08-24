<?php
include_once '../includes/db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST["company_name"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Insert company data into 'companies' table
    $insert_query = "INSERT INTO companies (company_name, username, password) VALUES ('$company_name', '$username', '$password')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Company registration successful!";
        
        // Create notification for admin
        $admin_notification = "A new company registration: $company_name.";
        $get_admins_query = "SELECT admin_id FROM admins";
        $admins_result = $conn->query($get_admins_query);

        if ($admins_result->num_rows > 0) {
            while ($admin = $admins_result->fetch_assoc()) {
                $admin_id = $admin["admin_id"];
                $admin_notification_sql = "INSERT INTO admin_notifications (admin_id, message) VALUES ('$admin_id', '$admin_notification')";
                $conn->query($admin_notification_sql);
            }
        }
        header("Location: ../dashboard/company_login.php"); 
        exit();
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
}
?>
