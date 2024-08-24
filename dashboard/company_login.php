<?php
session_start();

if (isset($_SESSION["company_id"])) {
    header("Location: company_dashboard.php");
    exit();
}

include_once '../includes/db_connection.php';

// Handle company login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate and authenticate company credentials
    $company_query = "SELECT * FROM companies WHERE username = '$username'";
    $company_result = $conn->query($company_query);

    if ($company_result->num_rows == 1) {
        $company = $company_result->fetch_assoc();
        $hashed_password = $company["password"];

        if ($password === $hashed_password) {
            // If authentication is successful, set session variable
            $_SESSION["company_id"] = $company["company_id"];
            header("Location: company_dashboard.php");
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
    <title>Company Login</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
     ?>
    <form method="post">
        <h3>Company Login</h3><br>
        <div class="container">

            <label for="username">Username</label>
            <input type="text"id="username" name="username" required placeholder="username"><br><br>
            
            <label for="password">Password</label>
            <input type="password"id="password" name="password" required placeholder="password"><br><br>
        </div>
        <button>login</button>
        <div clas="register">
            <div class="reg">If not register<a href="../registration/company_registration.php">Register</a></div>
        </div>
        <div class="error">
        <?php
            if (isset($error_message)) {
            echo "<p>$error_message</p>";
            }
        ?>
        </div>
    </form>
</body>
</html>