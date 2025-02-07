<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>School Administration</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* General Styles */
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f4f4f4;
  color: #333;
}

header {
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
  color: #4CAF50;
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
  color: #4CAF50;
}

p {
  font-size: 14px;
  line-height: 1.5;
}

footer {
  background-color: #333;
  color: white;
  text-align: center;
  padding: 10px;
 
  bottom: 0;
  width: 100%;
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
  <header>
    <h1>Welcome to Our School</h1>
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
  <footer>
    <p>&copy; 2023 Our School. All rights reserved.</p>
  </footer>

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
</body>
</html>