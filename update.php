<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .logout-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
        if ($conn->connect_error) {
            die('Connection Failed: ' . $conn->connect_error);
        }

        if (isset($_POST['update_student'])) {
            $adm_no = $_POST['adm_no'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $class_id = $_POST['class_id'];
            $gender = $_POST['gender'];
            $year = $_POST['year'];

            $update_sql = "UPDATE students SET 
                            first_name = '$first_name', 
                            last_name = '$last_name', 
                            email = '$email', 
                            password = '$password', 
                            class_id = '$class_id', 
                            gender = '$gender', 
                            year = '$year' 
                            WHERE adm_no = '$adm_no'";

            if ($conn->query($update_sql)) {
                echo "<div class='alert alert-success'>Student details updated successfully.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }

        if (isset($_POST['logout'])) {
            session_unset();      // Clear session variables
            session_destroy();    // Destroy the session
            header("Location: count.php"); // Redirect to login page
            exit();
        }

        if (isset($_POST['search_student'])) {
            $adm_no = $_POST['adm_no'];
            $student_sql = "SELECT * FROM students WHERE adm_no = '$adm_no'";
            $student_result = $conn->query($student_sql);
            $student = $student_result->fetch_assoc();
            if (!$student) {
                echo "<div class='alert alert-warning'>Student not found!</div>";
            }
        }
        ?>

        <h3 class="text-center mb-4">Search Student</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="adm_no" class="form-label">Admission No:</label>
                <input type="text" class="form-control" name="adm_no" placeholder="Enter Admission No" required>
            </div>
            <button type="submit" name="search_student" class="btn btn-custom w-100">Search</button>
        </form>

        <?php if (isset($student)): ?>
        <h3 class="text-center mt-4">Update Student Details</h3>
        <form method="POST" action="">
            <input type="hidden" name="adm_no" value="<?php echo $student['adm_no']; ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name:</label>
                    <input type="text" class="form-control" name="first_name" value="<?php echo $student['first_name']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Name:</label>
                    <input type="text" class="form-control" name="last_name" value="<?php echo $student['last_name']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $student['email']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" value="<?php echo $student['password']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Class ID:</label>
                    <input type="number" class="form-control" name="class_id" value="<?php echo $student['class_id']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gender:</label>
                    <select class="form-select" name="gender" required>
                        <option value="Male" <?php echo $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Year:</label>
                    <input type="number" class="form-control" name="year" value="<?php echo $student['year']; ?>" required>
                </div>
            </div>

            <button type="submit" name="update_student" class="btn btn-success w-100 mt-3">Update Student</button>
        </form>
        <?php endif; ?>

        <div class="logout-container">
        <h2></h2>
        <form method="POST" action="">
            <button type="submit" name="logout" class="btn btn-logout mt-3">Log Out</button>
        </form>
    </div>


        <?php $conn->close(); ?>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
