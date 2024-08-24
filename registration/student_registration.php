<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>

    <link rel="stylesheet" href="../css/registration.css">
    <script>
        function validateForm() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var email = document.getElementById("email").value;
            var firstName = document.getElementById("first_name").value;
            var lastName = document.getElementById("last_name").value;
            var phoneNumber = document.getElementById("phone_number").value;
            var universityNumber = document.getElementById("university_number").value;

            var emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

            if (username === "" || password === "" || email === "" || firstName === "" || lastName === "" || phoneNumber === "" || universityNumber === "") {
                alert("All fields must be filled out");
                return false;
            }
            if (!email.match(emailRegex)) {
                alert("Please enter a valid email address");
                return false;
            }
            return true; 
        }
    </script>
</head>
<body>
     
    <form action="process_student_registration.php" method="post" enctype="multipart/form-data">
    <h1>Student Registration</h1>
        <div class="container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required><br>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required><br>

            <label for="profile_photo">Profile Photo:</label>
            <input type="file" id="profile_photo" accept="image/*" name="profile_photo"><br><br>

            <label for="university_number">University Number:</label>
            <input type="text" id="university_number" name="university_number" required><br>

            <input type="submit" value="Register" id="btn"><br> Already Register <a href="../dashboard/student_login.php">Login</a>
        </div>
    </form>
</body>
</html>
