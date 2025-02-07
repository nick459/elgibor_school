<?php
session_start();

// Database connection (for future login functionality)
$host = 'localhost';
$dbname = 'db_elgibor_management';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle image upload
if (isset($_POST['upload']) && isset($_FILES['slider_image'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['slider_image']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    if (getimagesize($_FILES['slider_image']['tmp_name']) !== false) {
        if (move_uploaded_file($_FILES['slider_image']['tmp_name'], $target_file)) {
            $upload_message = "Image uploaded successfully.";
        } else {
            $upload_message = "Error uploading image.";
        }
    } else {
        $upload_message = "File is not an image.";
    }
}

// Get all images from the uploads folder
$images = glob("uploads/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>School Portal</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    .slider-container {
      position: relative;
      max-width: 800px;
      margin: 50px auto;
      overflow: hidden;
      border: 5px solid #4CAF50;
      border-radius: 10px;
    }

    .slider {
      display: flex;
      transition: transform 0.5s ease;
    }

    .slide {
      min-width: 100%;
      box-sizing: border-box;
    }

    .slide img {
      width: 100%;
      display: block;
    }

    .prev, .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
    }

    .prev {
      left: 0;
    }

    .next {
      right: 0;
    }

    .admin-container {
      max-width: 400px;
      margin: 50px auto;
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- Admin Portal for Image Upload -->
  <div class="admin-container">
    <h2>Image Upload</h2>
    <?php if (isset($upload_message)) echo "<p>$upload_message</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="slider_image" accept="image/*" required>
      <button type="submit" name="upload">Upload Image</button>
    </form>
  </div>

  <!-- Slider -->
  <div class="slider-container">
    <div class="slider">
      <?php foreach ($images as $image): ?>
        <div class="slide">
          <img src="<?php echo $image; ?>" alt="Slider Image">
        </div>
      <?php endforeach; ?>
    </div>
    <button class="prev">&#10094;</button>
    <button class="next">&#10095;</button>
  </div>

  <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    document.querySelector('.next').addEventListener('click', () => {
      currentSlide = (currentSlide + 1) % totalSlides;
      updateSlider();
    });

    document.querySelector('.prev').addEventListener('click', () => {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      updateSlider();
    });

    function updateSlider() {
      const offset = -currentSlide * 100;
      document.querySelector('.slider').style.transform = `translateX(${offset}%)`;
    }
  </script>
</body>
</html>