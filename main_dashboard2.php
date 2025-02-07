<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elgibor School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #343a40;
            padding: 1rem;
            color: white;
            position: fixed;
            width: 100%;
        }

        .navbar h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .nav-links ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        .nav-links ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-links ul li a:hover {
            color: #ffc107;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-toggle div {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 4px 0;
        }

        /* Mobile View */
        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                position: fixed;
                top: 0;
                left: -100%;
                width: 250px;
                height: 100vh;
                background-color: #343a40;
                padding-top: 60px;
                transition: left 0.3s ease-in-out;
            }

            .nav-links.active {
                left: 0;
                display: flex;
            }

            .nav-links ul {
                flex-direction: column;
                gap: 20px;
                padding-left: 20px;
            }
        }

        .hero-section {
            text-align: center;
            padding: 100px 20px;
            background-color: #007bff;
            color: white;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero-section h2 {
            font-size: 2rem;
            margin-top: 20px;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .footer-content {
            margin-bottom: 20px;
        }

        .footer-content h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .footer-content p, .footer-content li {
            margin: 5px 0;
        }

        .bottom-bar {
            background-color: #212529;
            padding: 10px;
            text-align: center;
        }
        
/* School Header */
.school-header {
    text-align: center;
    padding: 30px;
    background-color: #8B0000; /* Dark Red */
    color: white;
}

/* Logo Container */
.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
}

/* Logo Frame */
.logo-frame {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-frame img {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    border: 5px solid white;
    object-fit: cover;
}

.logo-frame p {
    margin-top: 5px;
    font-weight: bold;
}

/* School Title */
.school-title h1 {
    font-size: 32px;
    text-transform: uppercase;
}

.school-title h3 {
    font-size: 18px;
    font-weight: normal;
}

/* School Info Section */
.school-info {
    display: flex;
    justify-content: space-around;
    text-align: center;
    margin: 50px auto;
    max-width: 1000px;
}

.info-box {
    width: 30%;
    padding: 20px;
    background: #f4f4f4;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.info-box h2 {
    color: #8B0000;
    margin-bottom: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .logo-container {
        flex-direction: column;
    }

    .school-info {
        flex-direction: column;
        align-items: center;
    }

    .info-box {
        width: 80%;
        margin-bottom: 20px;
    }
    /* School Section */
.school-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    padding: 40px 20px;
}

.level {
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 300px;
    transition: transform 0.3s ease-in-out;
}

.level:hover {
    transform: scale(1.05);
}

.level img {
    width: 100%;
    height: 180px;
    border-radius: 10px;
}

.level h2 {
    color: brown;
    margin: 15px 0;
}

.level ul {
    text-align: left;
    padding-left: 20px;
}

/* Apply Button */
.apply-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 15px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease-in-out;
}

.apply-btn:hover {
    background-color: #218838;
}

/* Footer */



/* Responsive Design */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        text-align: center;
    }

    .navbar ul {
        flex-direction: column;
        padding: 10px 0;
    }

    .school-section {
        flex-direction: column;
        align-items: center;
    }
}
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <div class="navbar">
            <h3>WELCOME<br>TO<br>E.S.A</h3>
            <div class="menu-toggle" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <nav class="nav-links">
                <ul>
                    <li><a href="schooldash.php">Home</a></li>
                    <li><a href="stud_login.php">Student</a></li>
                    <li><a href="teach_login.php">Teacher</a></li>
                    <li><a href="#bottom">Contact</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="admin_dash.php">ADMIN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1>WELCOME TO</h1>
        <h2>ELGIBOR SABOTI PRIMARY & JUNIOR SECONDARY SCHOOL<br>BOARDING & DAY SCHOOL</h2>
    </section>

    <header class="school-header">
        <div class="logo-container">
            <div class="logo-frame">
                <img src="images/logo1.jpg" alt="Primary School Logo">
                <p>Primary School</p>
            </div>
            <div class="school-title">
                <h1>Elgibor Saboti School</h1>
                <h3>Boarding & Day School</h3>
            </div>
            <div class="logo-frame">
                <img src="images/logo2.jpg" alt="JSS School Logo">
                <p>Junior Secondary</p>
            </div>
        </div>
    </header>

    <!-- School Motto, Mission & Vision -->
    <section class="school-info">
        <div class="info-box">
            <h2>Motto</h2>
            <p>"Excellence Through Hard Work"</p>
        </div>
        <div class="info-box">
            <h2>Mission</h2>
            <p>To provide quality education that nurtures students into responsible and innovative leaders.</p>
        </div>
        <div class="info-box">
            <h2>Vision</h2>
            <p>To be a center of academic excellence and holistic development for all learners.</p>
        </div>
    </section>
    <section class="school-section">
        <div class="level">
            <img src="pre_primary.jpg" alt="Pre-Primary">
            <h2>Pre-Primary School</h2>
            <p>Our Pre-Primary program provides a strong foundation in early childhood education. We offer:</p>
            <ul>
                <li><strong>Playgroup:</strong> Fun and interactive activities for early development.</li>
                <li><strong>PP1 & PP2:</strong> Introduction to basic literacy, numeracy, and social skills.</li>
            </ul>
            <a href="apply.html" class="apply-btn">Apply for Admission</a>
        </div>

        <div class="level">
            <img src="primary.jpg" alt="Primary">
            <h2>Primary School (Grade 1-6)</h2>
            <p>Our primary school focuses on quality education and competency-based learning:</p>
            <ul>
                <li>Core subjects include Mathematics, English, Kiswahili, Science, and Social Studies.</li>
                <li>Engagement in co-curricular activities for holistic development.</li>
                <li>Technology-integrated learning to enhance skills.</li>
            </ul>
            <a href="apply.html" class="apply-btn">Apply for Admission</a>
        </div>

        <div class="level">
            <img src="jss.jpg" alt="Junior Secondary">
            <h2>Junior Secondary School (Grade 7-9)</h2>
            <p>Junior Secondary prepares students for advanced learning by emphasizing:</p>
            <ul>
                <li>STEM subjects (Science, Technology, Engineering, and Mathematics).</li>
                <li>Advanced literacy and problem-solving skills.</li>
                <li>Practical learning through projects and research.</li>
            </ul>
            <a href="apply.html" class="apply-btn">Apply for Admission</a>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-content">
                    <h3>Contact Us</h3>
                    <p>Phone: 0740261565</p>
                    <p>Email: elgiboracad@gmail.com</p>
                    <p>Address: 1276, Kitale</p>
                </div>
                <div class="col-md-4 footer-content">
                    <h3>Services</h3>
                    <p>We offer Quality and reliable education</p>
                    <p>CBC Compliant School</p>
                    <p>Primary Section</p>
                    <ul>
                        <li>Playgroup, PP1 & PP2</li>
                        <li>GRADE 1 - GRADE 6</li>
                    </ul>
                    <p>Junior Secondary School (JSS)</p>
                    <ul>
                        <li>GRADE 7 - GRADE 9</li>
                    </ul>
                </div>
                <div class="col-md-4 footer-content">
                    <h3>Follow Us</h3>
                    <ul>
                        <li>Facebook</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bottom-bar">
            <p>&copy; 2024 Elgibor School. All Rights Reserved</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMenu() {
            const menu = document.querySelector(".nav-links");
            menu.classList.toggle("active");
        }
    </script>
</body>
</html>