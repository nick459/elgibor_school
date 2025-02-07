<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #2c3e50;
            color: #ecf0f1;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        .card {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .logout-btn a {
            text-decoration: none;
            color: white;
            background-color: #dc3545;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .logout-btn a:hover {
            background-color:rgb(231, 228, 10);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#" onclick="showSection('dashboard')">Dashboard</a>
        <a href="count.php" onclick="showSection('students')">Manage Students</a>
        <a href="teacher_dash.php" onclick="showSection('teachers')">Manage Teachers</a>
        <a href="fee_dashboard.php" onclick="showSection('fees')">Manage Fees</a>
        <a href="view_result.php" onclick="showSection('results')">Manage Results</a>
        <a href="class.php" onclick="showSection('class_teachers')">Class Teachers</a><br>
        <div class="logout-btn">
            <a href="schooldash.php">HOME</a>
        </div>
    </div>
    

    <div class="content" id="content">
        <div id="dashboard" class="card">
            <h2>Dashboard Overview</h2>
            <p>Total Students: 120</p>
            <p>Total Teachers: 15</p>
            <a href="stud_reg.php">Register New Student</a><br>
            <a href="">Manage Students</a>
        </div>
        <div id="students" class="card" style="display: none;">
            <h2>Manage Students</h2>
            <p>Student management section.</p>
        </div>
        <div id="teachers" class="card" style="display: none;">
            <h2>Manage Teachers</h2>
            <p>Teacher management section.</p>
        </div>
        <div id="fees" class="card" style="display: none;">
            <h2>Manage Fees</h2>
            <p>Fees management section.</p>
        </div>
        <div id="results" class="card" style="display: none;">
            <h2>Manage Results</h2>
            <p>Results management section.</p>
        </div>
        <div id="class_teachers" class="card" style="display: none;">
            <h2>Class Teachers</h2>
            <p>Class teachers management section.</p>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.card');
            sections.forEach(section => section.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</body>
</html>