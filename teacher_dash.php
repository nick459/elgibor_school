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

        /* Header Styles */
        .header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header h2 br {
            display: none; /* Hide the line break on larger screens */
        }

        .header h2 span {
            display: block;
            font-size: 1.5rem;
            font-weight: normal;
            margin-top: 10px;
        }

        /* Logout Button Styles */
        .logout-btn {
            text-align: center;
            margin-top: 20px;
        }

        .logout-btn a {
            text-decoration: none;
            color: white;
            background-color: #dc3545; /* Red color for logout button */
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn a:hover {
            background-color: #c82333; /* Darker red on hover */
            transform: translateY(-2px);
        }

        .logout-btn a:active {
            background-color: #bd2130; /* Even darker red on active */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h2 {
                font-size: 2rem;
            }

            .header h2 br {
                display: block; /* Show the line break on smaller screens */
            }

            .header h2 span {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>
            ELGIBOR | SABOTI | ACADEMY | SCHOOL<br>
            <span>Teachers Management Portal</span>
        </h2>
    </div>

    <!-- Navigation Links -->
    <div class="nav-links">
        <a href="teacher_reg.php">Register New Teacher</a> | 
        <a href="search_teach.php">Update Teacher</a> | 
        <a href="vieww_teach.php">View Teacher</a>
    </div>
<br><br><br><br><br>
    <!-- Logout Button -->
    <div class="logout-btn">
        <a href="admin_dash.php">Logout</a>
    </div>
</body>
</html>