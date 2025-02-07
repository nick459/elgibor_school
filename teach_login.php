<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// TEACHER LOGIN
if (isset($_POST['login_teacher'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM teachers WHERE email = '$email'");
    $teacher = $result->fetch_assoc();

    if ($teacher && password_verify($password, $teacher['password'])) {
        session_start();
        $_SESSION['teacher_id'] = $teacher['teacher_id'];
        header('Location: teach_portal_dash.php');
    } else {
        
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: wheat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: greenyellow;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(240, 20, 20, 0.1);
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
    <h2>Teacher Login</h2>
    <form method="POST" action="">
        <input type="email" class="form-control" name="email" placeholder="Email" required><br>
        <input type="password" class="form-control" name="password" placeholder="Password" required><br>
        <button type="submit" name="login_teacher" class="btn btn-primary">Login Teacher</button>
    </form>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
