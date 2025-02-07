<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// STUDENT LOGIN
if (isset($_POST['login_student'])) {
    $adm_no = $_POST['adm_no'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM students WHERE adm_no = '$adm_no'");
    $student = $result->fetch_assoc();

    if ($student && password_verify($password, $student['password'])) {
        session_start();
        $_SESSION['adm_no'] = $adm_no;
        header('Location: stud_dash.php');
    } else {
        echo "<div class='alert alert-danger'>Invalid credentials.</div>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-control {
            margin-bottom: 15px;
        }
        button {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Student Login</h2>
    <form method="POST" action="">
        <input type="text" class="form-control" name="adm_no" placeholder="Admission No" required><br>
        <input type="password" class="form-control" name="password" placeholder="Password" required><br>
        <button type="submit" name="login_student" class="btn btn-primary">Login Student</button>
    </form>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
