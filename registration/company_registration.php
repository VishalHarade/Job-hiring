<?php include_once '../includes/db_connection.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Registration</title>
    <link rel="stylesheet" type="text/css" href="../css/registration.css"> 
</head>
<body>
  
    <form action="process_company_registration.php" method="post">
        <div class="container">
        <h1>Company Registration</h1>
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <!-- <input type="submit" value="Register"><br> -->
        <input type="submit" value="Register" id="btn"><br> Already Register <a href="../dashboard/company_login.php">Login</a>
        </div>
    
    </form>
</body>
</html>
