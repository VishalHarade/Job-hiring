<?php
session_start();

// Check if student is already logged in
if (isset($_SESSION["student_id"])) {
    header("Location: student_dashboard.php");
    exit();
}

include_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT student_id, password, email, first_name, last_name, phone_number, university_number
            FROM students WHERE username = '$username'";
    
    try {
        $result = $conn->query($sql);

        if (!$result) {
            throw new Exception("Error executing the query: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row["password"];

            if ($password === $hashed_password) {
                // Authentication successful
                $student_id = $row["student_id"];
                $email = $row["email"];
                $first_name = $row["first_name"];
                $last_name = $row["last_name"];
                $phone_number = $row["phone_number"];
                $university_number = $row["university_number"];

                // Store student data in session
                $_SESSION["student_id"] = $student_id;
                $_SESSION["email"] = $email;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["phone_number"] = $phone_number;
                $_SESSION["university_number"] = $university_number;

                // Redirect to the student dashboard
                header("Location: student_dashboard.php");
                exit();
            } else {
                // Authentication failed
                $error_message = "Invalid password";
            }
        } else {
            // Authentication failed
            $error_message = "Invalid username";
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post">
        <h3>Student Login</h3><br>
        <div class="container">

            <label for="username">Username</label>
            <input type="text"id="username" name="username" required placeholder="username"><br><br>
            
            <label for="password">Password</label>
            <input type="password"id="password" name="password" required placeholder="password"><br><br>
        </div>
        
        <button>login</button>
        <div clas="register">
            <div class="reg">If not register<a href="../registration/student_registration.php">Register</a></div>
        </div>
        <div class="error"><?php
        if (isset($error_message)) {
            echo "<p>$error_message</p>";
        }
        ?></div>
    </form>
</body>

</html>