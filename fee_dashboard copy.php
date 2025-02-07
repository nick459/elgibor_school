
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: cornsilk;
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
    </style>
</head>
<body>

    <div class="nav-links">
        <a href="assign_fee.php">Assign Fees</a> | 
        <a href="record_payment.php">Record Payment</a> | 
        <a href="fee_report.php">View Fee Report</a>
    </div>

</body>
</html>
