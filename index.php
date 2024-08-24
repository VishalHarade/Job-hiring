<?php
include_once 'includes/db_connection.php';?>

<html>
<head>
    <title>Job Affairs </title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>

    <!-- navbar -->
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
        <!-- search bar -->
        <div class="search">
            <div class="size">
                <form action="job_search/search_jobs.php" method="get">
                    <input id="searchinput" type="text" name="search_keyword" placeholder="What are you looking for?">
                    <span class="searchBtn">
                        <button type="submit" id="searchbtn">Search</button>
                    </span>
                </form>
            </div>
        </div>  
 <!-- Image Slider -->
 <div class="slider-container">
        <div class="slider">
            <div class="slide"><img src="images/img1.jpg" alt="Slide 1"></div>
            <div class="slide"><img src="images/img2.jpg" alt="Slide 2"></div>
            <div class="slide"><img src="images/img3.jpg" alt="Slide 3"></div>
            <!-- <div class="slide"><img src="images/img4.jpg" alt="Slide 4"></div>
            <div class="slide"><img src="images/img5.jpg" alt="Slide 5"></div> -->
        </div>
    </div>
<br><br>
   <!-- Company Names -->
        <div class="category">
            <h4><center>Registered Companies</center></h4><br>
            <div class="categoryItems">
                <?php
                // Fetch and display registered companies
                $companies_sql = "SELECT * FROM companies";
                $companies_result = $conn->query($companies_sql);

                if ($companies_result->num_rows > 0) {
                    while ($company = $companies_result->fetch_assoc()) {
                        $company_name = $company['company_name'];
                        $company_id = $company['company_id'];

                        echo "<div class='divHover'>";
                        echo "<a href='profiles/company_profile.php?company_id=$company_id'>";
                        echo "<div class='text'>$company_name</div>";
                        echo "</a>";
                        echo "</div>";
                    }
                } else {
                    echo "No registered companies.";
                }
                ?>
            </div>
        </div>
        <!-- Internships -->
        <div class="category">
            <h4><center>Internships</center></h4><br>
            <div class="categoryItems">
                <?php
                //  display internships
                $internships_sql = "SELECT * FROM internships";
                $internships_result = $conn->query($internships_sql);

                if ($internships_result->num_rows > 0) {
                    while ($internship = $internships_result->fetch_assoc()) {
                        $internship_id = $internship['internship_id'];
                        $internship_title = $internship['title'];

                        echo "<div class='divHover'>";
                        echo "<a href='./apply_internship.php?internship_id=$internship_id'>";
                        echo "<div class='text'>$internship_title</div>";
                        // echo "<div class='apply-button'>Apply</div>"; // Add the Apply button
                        echo "</a>";
                        echo "</div>";
                    }
                } else {
                    echo "No available internships.";
                }
                ?>
            </div>
        </div>
<br><br><br>


   <!-- About Us -->
   <footer>
    <div class="about-us" id="about-us">
        <h2>About Us</h2>
        <p>Welcome to our Job Affairs website! We are dedicated to connecting job seekers with their dream careers and helping companies find the best talents. Our platform offers a wide range of job listings across various industries and locations, making it easier for both job seekers and employers to find their perfect match.
        Whether you're a recent graduate, an experienced professional, or a company looking to hire, our platform is designed to cater to your needs. Feel free to explore our job listings, connect with potential employers, and embark on a journey toward a successful career.
        Thank you for choosing Job Hiring. We look forward to helping you achieve your career goals!</p>
    </div>
    
    <div class="contact-us" id="contact-us">
        <h2>Contact Us</h2>
        <p>If you have any questions or inquiries, feel free to reach out to us:</p>
        <ul>
            <li>Email: contact@jobaffairs.com</li>
            <li>Phone: +123-456-7890</li>
            <li>Address: 123 Job Avenue, BGM, India</li>
        </ul>
    </div>
</footer>



    <script>
        const slider = document.querySelector(".slider");
        let isPaused = false;

        slider.addEventListener("mouseenter", () => {
            isPaused = true;
        });

        slider.addEventListener("mouseleave", () => {
            isPaused = false;
        });

        function autoSlide() {
            if (!isPaused) {
                const firstSlide = slider.firstElementChild;
                slider.appendChild(firstSlide);
            }
        }

        setInterval(autoSlide, 10000); 
    </script>

</body>
</html>