<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// TEACHER REGISTRATION
if (isset($_POST['register_teacher'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO teachers (first_name, last_name, email, password)
            VALUES ('$first_name', '$last_name', '$email', '$password')";

    if ($conn->query($sql)) {
        echo "Teacher registered successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .btn-logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <!-- Logout Button -->
    <a href="teacher_dash.php" class="btn btn-danger btn-logout">Logout</a>

    <div class="container">
        <h2 class="text-center">Teacher Registration Form</h2>
        
        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="text-center">
                <button type="submit" name="register_teacher" class="btn btn-custom">Register Teacher</button>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            // Example validation: Ensure all fields are filled in (this is basic, can be extended)
            var first_name = document.getElementById('first_name').value;
            var last_name = document.getElementById('last_name').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            if (first_name == "" || last_name == "" || email == "" || password == "") {
                alert("Please fill in all fields.");
                return false;
            }
            return true; // Proceed with form submission
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
