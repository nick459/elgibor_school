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
    <title>School Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        /* Navbar */
        .navbar {
            background-color: brown;
            padding: 15px 30px;
            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
            color: yellow;
            text-align: center;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: black;
        }

        /* Hamburger Menu */
        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            background-color: white;
            padding: 5px;
            border-radius: 3px;
        }

        /* Main Section */
        .hero {
            text-align: center;
            padding: 80px 20px;
            color: brown;
            font-weight: bold;
        }

        .hero h1 {
            font-size: 40px;
        }

        .hero h2 {
            font-size: 25px;
            color: darkblue;
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
        footer {
            background-color: brown;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }

        .footer-content {
            margin-bottom: 20px;
        }

        .bottom-bar {
            margin-top: 15px;
            font-size: 12px;
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
        
        /* Slider Styles */
        .slider-container {
            position: relative;
            max-width: 100%;
            margin: 20px auto;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 100%;
        }

        .slide {
            min-width: 100%;
            transition: opacity 1s ease-in-out;
        }

        .slide img {
            width: 100%;
            height: auto;
            max-height: 500px; /* Adjust this value as needed */
            object-fit: contain; /* Ensures the image fits without cropping */
            border-radius: 10px;
        }

        /* Navigation Buttons */
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 50%;
            font-size: 18px;
        }

        .prev { left: 10px; }
        .next { right: 10px; }

        .prev:hover, .next:hover {
            background-color: black;
        }

        /* Dots for Manual Control */
        .dots-container {
            text-align: center;
            margin-top: 10px;
        }

        .dot {
            height: 12px;
            width: 12px;
            margin: 5px;
            background-color: gray;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dot.active {
            background-color: brown;
        }
        .title{
            text-align: center;
            font-weight: bold;
            font-style: italic;
            
            color: brown;
        }
        .slide {
            min-width: 100%;
            position: relative;
            transition: opacity 1s ease-in-out;
        }
        .slide-description {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            max-width: 80%;
        }

       
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

            .slide img {
                max-height: 300px; /* Adjust for smaller screens */
            }
            .slide-description {
                font-size: 14px;
                bottom: 10px;
                padding: 8px 15px;
               
            }
        }
       
        .admin {
  background-color: #4CAF50;
  color: white;
  padding: 20px;
  text-align: center;
}

main {
  padding: 20px;
}

.administration {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  gap: 20px;
}

.profile {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  text-align: center;
  width: 300px;
}

/* Unique Styling for Director */
.profile.director {
  border: 3px solid #4CAF50;
  background-color: #f0f8ff; /* Light blue background */
}

.profile.director .photo-frame {
  width: 180px; /* Larger photo frame */
  height: 180px;
  border: 4px solid #4CAF50; /* Green border */
}

.profile.director h2 {
  color:rgb(4, 17, 160);
  font-size: 24px; /* Larger font size */
}

.profile.director p {
  font-size: 16px; /* Slightly larger text */
}

.photo-frame {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  overflow: hidden;
  margin: 0 auto 15px;
}

.photo-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

h2 {
  margin: 10px 0;
  color:rgb(212, 7, 7);
}

p {
  font-size: 14px;
  line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 768px) {
  .administration {
    flex-direction: column;
    align-items: center;
  }

  .profile {
    width: 100%;
  }

  .profile.director .photo-frame {
    width: 150px; /* Adjust for smaller screens */
    height: 150px;
  }
}

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">E L G I B O R<br>SABOTI<br>A C A D E M Y</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="schooldash.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="stud_login.php">Student</a></li>
                <li class="nav-item"><a class="nav-link" href="teach_login.php">Teacher</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_dash.php">ADMIN</a></li>
            </ul>
        </div>
    </div>
</nav>
<br><br><br><br>

<!-- Hero Section -->
<section class="hero">
    <h1>WELCOME TO</h1>
    <h2>ELGIBOR SABOTI PRIMARY & JUNIOR SECONDARY SCHOOL<br>BOARDING & DAY SCHOOL</h2>
</section>

<!-- School Header Section -->
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
            <p>Junior Secondary School</p>
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

<!-- School Section -->
<section class="school-section">
    <div class="level">
        <img src="images/logo2.jpg" alt="Pre-Primary">
        <h2>Pre-Primary School</h2>
        <p>Our Pre-Primary program provides a strong foundation in early childhood education. We offer:</p>
        <ul>
            <li><strong>Playgroup:</strong> Fun and interactive activities for early development.</li>
            <li><strong>PP1 & PP2:</strong> Introduction to basic literacy, numeracy, and social skills.</li>
        </ul>
        <a href="apply.html" class="apply-btn">Apply for Admission</a>
    </div>

    <div class="level">
        <img src="images/logo1.jpg" alt="Primary">
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
        <img src="images/logo2.jpg" alt="Junior Secondary">
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
<div class="navbar">
            <h2>Elgibor Saboti School</h2>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#">Primary Section</a></li>
                    <li><a href="#">Junior Secondary</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <h1 class="title">Primary & Junior Secondary Photo Gallery</h1>

    <div class="slider-container">
        <div class="slider">
            <div class="slide">
            <div><img src="images/logo1.jpg" alt="Primary School 1"></div>
            <div class="slide-description">Junior Secondary Science Lab - Hands-on learning for future innovators.</div>
            </div>

            <div class="slide">
            <div><img src="images/slider2.jpg" alt="Primary School 2"></div>
            <div class="slide-description">Junior Secondary Science Lab - Hands-on learning for future innovators.</div>
            </div>

            <div class="slide">
            <div><img src="images/slider3.jpg" alt="Primary School 3"></div>
            <div class="slide-description">Junior Secondary Science Lab - Hands-on learning for future innovators.</div>
            </div>

            <div class="slide">
            <div><img src="images/slider4.jpg" alt="Junior Secondary 1"></div>
            <div class="slide-description">Junior Secondary Science Lab - Hands-on learning for future innovators.</div>
            </div>

            <div class="slide">
            <div><img src="images/slider5.jpg" alt="Junior Secondary 2"></div>
            <div class="slide-description">Junior Secondary Science Lab - Hands-on learning for future innovators.</div>
            </div>

            <div class="slide">
            <div><img src="images/slider6.jpg" alt="Junior Secondary 3"></div>
            <div class="slide-description">Junior Secondary Science Lab - Hands-on learning for future innovators.</div>
            </div>
        </div>
        
        <!-- Navigation Arrows -->
        <button class="prev" onclick="prevSlide()">&#10094;</button>
        <button class="next" onclick="nextSlide()">&#10095;</button>

        <!-- Dots for Manual Selection -->
        <div class="dots-container">
            <span class="dot" onclick="setSlide(0)"></span>
            <span class="dot" onclick="setSlide(1)"></span>
            <span class="dot" onclick="setSlide(2)"></span>
            <span class="dot" onclick="setSlide(3)"></span>
            <span class="dot" onclick="setSlide(4)"></span>
            <span class="dot" onclick="setSlide(5)"></span>
        </div>
    </div>


    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll(".slide");
        const dots = document.querySelectorAll(".dot");

        function showSlide(index) {
            if (index >= slides.length) { currentIndex = 0; }
            else if (index < 0) { currentIndex = slides.length - 1; }
            else { currentIndex = index; }

            // Move the slides
            document.querySelector(".slider").style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update dots
            dots.forEach(dot => dot.classList.remove("active"));
            dots[currentIndex].classList.add("active");
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        function setSlide(index) {
            showSlide(index);
        }

        // Auto-slide every 4 seconds
        setInterval(nextSlide, 4000);

        // Initialize first active dot
        showSlide(currentIndex);
    </script>
<!--ADMINISTRATION INFORMATION -->
<header class="admin">
    <h1>School Administration</h1>
  </header>

  <main>
    <section class="administration">
      <!-- Director's Profile -->
      <div class="profile director">
        <div class="photo-frame">
          <img src="images/slider1.jpg" alt="Director">
        </div>
        <h2>Madam Director</h2>
        <p>Welcome to our school! We are committed to providing a nurturing environment for all students to thrive academically and socially.</p>
      <h2>MD.LILIAN CHEBET SAEKWO<h2>
    </div>

      <!-- Headteacher (Primary) -->
      <div class="profile">
        <div class="photo-frame">
          <img src="images/logo2.jpg" alt="Head of Institution(H.O.I)">
        </div>
        <h2>Head of Institution(H.O.I)</h2>
        <p>Welcome to the primary section! Our goal is to lay a strong foundation for lifelong learning and personal growth.</p>
      </div>

      <!-- Headteacher (Junior Secondary) -->
      <div class="profile">
        <div class="photo-frame">
          <img src="images/logo1.jpg" alt="Head Junior Secondary School">
        </div>
        <h2>Head(Junior Secondary School)</h2>
        <p>Welcome to the junior secondary section! We focus on academic excellence and character development to prepare students for the future.</p>
      </div>
    </section>
  </main>
<br>
  
  <script>
    // Add interactivity
document.addEventListener("DOMContentLoaded", function () {
  const profiles = document.querySelectorAll(".profile");

  profiles.forEach((profile) => {
    profile.addEventListener("mouseenter", () => {
      profile.style.transform = "scale(1.05)";
      profile.style.transition = "transform 0.3s ease";
    });

    profile.addEventListener("mouseleave", () => {
      profile.style.transform = "scale(1)";
    });
  });

  // Special hover effect for the director
  const directorProfile = document.querySelector(".profile.director");
  directorProfile.addEventListener("mouseenter", () => {
    directorProfile.style.transform = "scale(1.1)";
    directorProfile.style.boxShadow = "0 8px 16px rgba(0, 0, 0, 0.2)";
  });

  directorProfile.addEventListener("mouseleave", () => {
    directorProfile.style.transform = "scale(1)";
    directorProfile.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
  });
});
  </script>

<!-- Footer -->
<footer id="contact">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-md-4 footer-content">
                <h3>Contact Us</h3>
                <p>Phone: 0740261565</p>
                <p>Email: elgiboracad@gmail.com</p>
                <p>Address: 1276, Kitale</p>
            </div>
            <!-- Services -->
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
            <!-- Social Media -->
            <div class="col-md-4 footer-content">
                <h3>Follow Us</h3>
                <p>Facebook</p>
            </div>
        </div>
        <div class="bottom-bar">
            <p>&copy;2024 elgiborschool. All Rights Reserved</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>