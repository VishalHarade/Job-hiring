<?php
session_start();

// Include the database connection
include_once '../includes/db_connection.php';

// Handle admin login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate and authenticate admin credentials
    $admin_query = "SELECT * FROM admins WHERE username = '$username'";
    $admin_result = $conn->query($admin_query);

    if ($admin_result->num_rows == 1) {
        $admin = $admin_result->fetch_assoc();
        $hashed_password = $admin["password"];

        if ($password === $hashed_password) {
            // If authentication is successful, set admin session
            $_SESSION["admin_id"] = $admin["admin_id"];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "Invalid username";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin_login.css">
</head>
<body>
    
    <?php
    if (isset($error_message)) {
        echo "<p>$error_message</p>";
    }
    ?>
    <form method="post">
         <h1>Admin Login</h1>
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
