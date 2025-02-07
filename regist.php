<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// STUDENT REGISTRATION
if (isset($_POST['register_student'])) {
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
        echo "Invalid credentials.";
    }
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
        header('Location: teach_dash.php');
    } else {
        echo "Invalid credentials.";
    }
}

$conn->close();
?>
<!-- Student Registration Form -->
<h2>Student Registration</h2>
<form method="POST" action="">
    <input type="text" name="adm_no" placeholder="Admission No" required><br>
    <input type="text" name="first_name" placeholder="First Name" required><br>
    <input type="text" name="last_name" placeholder="Last Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="number" name="class_id" placeholder="Class ID" required><br>
    <button type="submit" name="register_student">Register Student</button>
</form>

<!-- Student Login Form -->
<h2>Student Login</h2>
<form method="POST" action="">
    <input type="text" name="adm_no" placeholder="Admission No" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="login_student">Login Student</button>
</form>

<!-- Teacher Registration Form -->
<h2>Teacher Registration</h2>
<form method="POST" action="">
    <input type="text" name="first_name" placeholder="First Name" required><br>
    <input type="text" name="last_name" placeholder="Last Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="register_teacher">Register Teacher</button>
</form>

<!-- Teacher Login Form -->
<h2>Teacher Login</h2>
<form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="login_teacher">Login Teacher</button>
</form>
