<?php
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
    <title>Fee Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: brown;
            margin: 0;
            padding: 0;
        }

        .nav-links {
            text-align: center;
            margin-top: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: brown;
            background-color: skyblue;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .nav-links a:active {
            background-color: #388e3c;
        }
        .header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            position: relative;
        }
        .header h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header h2 span {
            display: block;
            font-size: 1.5rem;
            font-weight: normal;
            margin-top: 10px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
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
            background-color: #c82333;
            transform: translateY(-2px);
        }
        .logout-btn a:active {
            background-color: #bd2130;
        }
    </style>
</head>
<body>
<div class="header">
        <h2>
            ELGIBOR | SABOTI | ACADEMY | SCHOOL<br>
            <span>Fee Management Dashboard</span>
        </h2>
        <div class="logout-btn">
            <a href="admin_dash.php">Logout</a>
        </div>
    </div>

    <div class="nav-links">
        <a href="class_fee.php">Assign Fees</a> | 
        <a href="f_payment.php">Record Payment</a> | 
        <a href="fee_payment_record.php">View Fee Report</a>
    </div>

</body>
</html>
