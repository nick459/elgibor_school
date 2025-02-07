<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

if (isset($_POST['logout'])) {
    session_unset();      // Clear session variables
    session_destroy();    // Destroy the session
    header("Location: admin_dash.php"); // Redirect to login page
    exit();
}
// Count Total Students
$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc();

// Count Male and Female Students
$male_count = $conn->query("SELECT COUNT(*) AS male_count FROM students WHERE gender = 'Male'")->fetch_assoc();
$female_count = $conn->query("SELECT COUNT(*) AS female_count FROM students WHERE gender = 'Female'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f4f4f4, #e0e0e0);
            display: flex;
            justify-content: center;
            align-items: center;
            
            top: 0;
        }
        .dashboard {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 80%;
            width: 100%;
            text-align: center;
        }
        h4 {
            font-size: 50px;
            color: linear grad;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #ff6b6b, #6b8eff, #50c878);
            font-style: italic;
            font-family: 'Times New Roman', Times, serif;
            top: 0px;
        }
        h3 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 15px;
        }
        h2 {
            font-size: 22px;
            color: #ff5722;
            margin-bottom: 20px;
        }
        .gender-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .gender-container p {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 45%;
            font-size: 18px;
            color: #555;
        }
        .gender-container p.male {
            background: #e3f2fd;
            color: #1976d2;
        }
        .gender-container p.female {
            background: #fce4ec;
            color: #d81b60;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .buttons button {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background: #007bff;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .buttons button:hover {
            background: #0056b3;
        }
        .buttons button:nth-child(2) {
            background: #ff5722;
        }
        .buttons button:nth-child(2):hover {
            background: #e64a19;
        }
        .buttons button:nth-child(3) {
            background: #4caf50;
        }
        .buttons button:nth-child(3):hover {
            background: #388e3c;
        }
        .logout-container {
           padding: 4px;
            background: linear-gradient(135deg,rgb(7, 231, 55),rgb(242, 4, 4));
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: absolute;
            bottom: 20px;
            align-items: center;       
        }
        .btn-logout {
            background-color:rgb(9, 20, 230);
            padding: 15px;
            font-weight: bold;
            border-radius: 20px;
            font-size: 18px;

        }
        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Title -->
        <h4>ELGIBOR SABOTI ACADEMY SCHOOL</h4>
        
        <!-- Total Students -->
        <h3>Total Students: <?php echo $total_students['total']; ?></h3>
        
        <!-- Gender Breakdown -->
        <h2>Gender Breakdown</h2>
        <div class="gender-container">
            <p class="female">Female: <?php echo $female_count['female_count']; ?></p>
            <p class="male">Male: <?php echo $male_count['male_count']; ?></p>
        </div>
        
        <!-- Buttons -->
        <div class="buttons">
            <button onclick="window.location.href='view_stud.php'">VIEW STUDENTS</button>
            <button onclick="window.location.href='search.php'">SEARCH STUDENTS</button>
            <button onclick="window.location.href='update.php'">UPDATE STUDENTS</button>
            <button onclick="window.location.href='stud_reg.php'">ADD STUDENTS</button>
        </div>
    </div>
    <br><br><br>
    <div class="logout-container">
        
        <form method="POST" action="">
            <button type="submit" name="logout" class="btn btn-logout mt-3">Log Out</button>
        </form>
    </div>
</body>
</html>