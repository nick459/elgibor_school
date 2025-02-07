<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

if (isset($_POST['submit_student'])) {
    $adm_no = $_POST['adm_no'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $class_id = $_POST['class_id'];

    $sql = "INSERT INTO students (adm_no, first_name, last_name, email, password, class_id)
            VALUES ('$adm_no', '$first_name', '$last_name', '$email', '$password', '$class_id')";

    if ($conn->query($sql)) {
        echo "Student registered successfully.";
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
    <title>Student Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: greenyellow;
            font-family: Arial, sans-serif;
            
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        h2 {
            color:rgb(232, 188, 12);
            padding: 20px;
            font-weight: bold;
           
        }
        .btn-logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <!-- Logout Button -->
    <a href="count.php" class="btn btn-danger btn-logout">Logout</a>

    <div class="container mt-5">
        <h2 class="text-center mb-4">ELGIBOR SABOTI ACADEMY SCHOOL<br>Student Registration Form</h2>
       
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="adm_no" class="form-label">Admission No</label>
                    <input type="text" class="form-control" name="adm_no" placeholder="Adm No" value="<?php echo $student['adm_no'] ?? ''; ?>">
                </div>

                <div class="col-md-6">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo $student['first_name'] ?? ''; ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo $student['last_name'] ?? ''; ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="Male" <?php echo isset($student['gender']) && $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo isset($student['gender']) && $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo isset($student['gender']) && $student['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $student['email'] ?? ''; ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>

                <div class="col-md-6">
                    <label for="class_id" class="form-label">Class ID</label>
                    <input type="number" class="form-control" name="class_id" placeholder="Class ID" value="<?php echo $student['class_id'] ?? ''; ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" class="form-control" name="year" placeholder="Year" value="<?php echo $student['year'] ?? ''; ?>" required>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" name="submit_student" class="btn btn-primary">Register Student</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

