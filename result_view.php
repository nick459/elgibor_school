<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Result Upload
if (isset($_POST['upload_result'])) {
    $adm_no = $_POST['adm_no'];
    $year = $_POST['year'];
    $term = $_POST['term'];
    $english = $_POST['english'];
    $kiswahili = $_POST['kiswahili'];
    $maths = $_POST['maths'];
    $home_science = $_POST['home_science'];
    $cre = $_POST['cre'];

    $check_result = $conn->query("SELECT * FROM results WHERE adm_no = '$adm_no' AND year = '$year' AND term = '$term'");
    if ($check_result->num_rows > 0) {
        $conn->query("UPDATE results 
                      SET english = '$english', kiswahili = '$kiswahili', maths = '$maths', 
                          home_science = '$home_science', cre = '$cre' 
                      WHERE adm_no = '$adm_no' AND year = '$year' AND term = '$term'");
    } else {
        $conn->query("INSERT INTO results (adm_no, year, term, english, kiswahili, maths, home_science, cre) 
                      VALUES ('$adm_no', '$year', '$term', '$english', '$kiswahili', '$maths', '$home_science', '$cre')");
    }

    echo "<div class='alert alert-success'>Result uploaded successfully!</div>";
}

// Handle Result Update
if (isset($_POST['update_result'])) {
    $adm_no = $_POST['adm_no'];
    $subject = $_POST['subject'];
    $year = $_POST['year'];
    $term = $_POST['term'];
    $marks = $_POST['marks'];

    $check_result = $conn->query("SELECT * FROM results WHERE adm_no = '$adm_no' AND year = '$year' AND term = '$term'");
    if ($check_result->num_rows > 0) {
        $conn->query("UPDATE results SET $subject = '$marks' WHERE adm_no = '$adm_no' AND year = '$year' AND term = '$term'");
    } else {
        $conn->query("INSERT INTO results (adm_no, year, term, $subject) VALUES ('$adm_no', '$year', '$term', '$marks')");
    }
    echo "<div class='alert alert-success'>Result updated successfully!</div>";
}

// TEACHER DASHBOARD
if (isset($_SESSION['teacher_id'])) {
    $teacher_id = $_SESSION['teacher_id'];
    $teacher_result = $conn->query("SELECT * FROM teachers WHERE teacher_id = '$teacher_id'");
    $teacher = $teacher_result->fetch_assoc();

    // Filters for Viewing Results
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    $term = isset($_GET['term']) ? $_GET['term'] : 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
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
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .table-responsive {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Header with Logout Button -->
    <div class="header">
        <h2>
            ELGIBOR | SABOTI | ACADEMY | SCHOOL<br>
            <span>Teacher Dashboard</span>
        </h2>
        <div class="logout-btn">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <?php if (isset($_SESSION['teacher_id'])): ?>
        <!-- Welcome Message -->
        <div class="container">
            <div class="alert alert-info">
                <h2>Welcome, <?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?></h2>
                <p>Email: <?php echo $teacher['email']; ?></p>
            </div>

            <!-- Classes Managed -->
            <?php
            $classes_result = $conn->query("SELECT * FROM classes WHERE teacher_id = '$teacher_id'");
            while ($class = $classes_result->fetch_assoc()):
            ?>
                <div class="form-container">
                    <h3>Class: <?php echo $class['class_name']; ?></h3>

                    <!-- Upload Results Form -->
                    <h4>Upload Results</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="adm_no" class="form-label">Student:</label>
                            <select name="adm_no" class="form-control" required>
                                <?php
                                $students_result = $conn->query("SELECT * FROM students WHERE class_id = '{$class['class_id']}'");
                                while ($student = $students_result->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $student['adm_no']; ?>">
                                        <?php echo $student['first_name'] . ' ' . $student['last_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Year:</label>
                            <input type="text" name="year" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="term" class="form-label">Term:</label>
                            <select name="term" class="form-control" required>
                                <option value="1">Term 1</option>
                                <option value="2">Term 2</option>
                                <option value="3">Term 3</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="english" class="form-label">English:</label>
                            <input type="number" name="english" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="kiswahili" class="form-label">Kiswahili:</label>
                            <input type="number" name="kiswahili" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="maths" class="form-label">Maths:</label>
                            <input type="number" name="maths" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="home_science" class="form-label">Home Science:</label>
                            <input type="number" name="home_science" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="cre" class="form-label">CRE:</label>
                            <input type="number" name="cre" class="form-control" min="0" max="100" required>
                        </div>

                        <button type="submit" name="upload_result" class="btn btn-primary">Upload Result</button>
                    </form>
                </div>

                <!-- View and Edit Results -->
                <div class="table-responsive">
                    <h4>View and Edit Results</h4>
                    <form method="GET" class="mb-3">
                        <label>Year:</label>
                        <input type="number" name="year" value="<?php echo $year; ?>" required>
                        <label>Term:</label>
                        <select name="term">
                            <option value="1" <?php echo ($term == 1) ? 'selected' : ''; ?>>Term 1</option>
                            <option value="2" <?php echo ($term == 2) ? 'selected' : ''; ?>>Term 2</option>
                            <option value="3" <?php echo ($term == 3) ? 'selected' : ''; ?>>Term 3</option>
                        </select>
                        <button type="submit" class="btn btn-secondary">Filter</button>
                    </form>

                    <?php
                    $subjects = ['english', 'kiswahili', 'maths', 'home_science', 'cre'];
                    $students_result = $conn->query("SELECT * FROM students WHERE class_id = '{$class['class_id']}'");
                    $students = [];
                    while ($student = $students_result->fetch_assoc()) {
                        $students[] = $student;
                    }

                    echo "<table class='table table-bordered table-striped'>
                            <tr><th>Subject</th>";
                    foreach ($students as $student) {
                        echo "<th>{$student['first_name']} {$student['last_name']}</th>";
                    }
                    echo "</tr>";

                    foreach ($subjects as $subject) {
                        echo "<tr><td>$subject</td>";
                        foreach ($students as $student) {
                            $result_query = "SELECT $subject FROM results WHERE adm_no = '{$student['adm_no']}' AND year = '$year' AND term = '$term'";
                            $result = $conn->query($result_query);
                            $marks = ($result->num_rows > 0) ? $result->fetch_assoc()[$subject] : '-';

                            echo "<td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='adm_no' value='{$student['adm_no']}'>
                                        <input type='hidden' name='subject' value='$subject'>
                                        <input type='hidden' name='year' value='$year'>
                                        <input type='hidden' name='term' value='$term'>
                                        <input type='number' name='marks' value='$marks' min='0' max='100' style='width:80px;'>
                                        <button type='submit' name='update_result' class='btn btn-sm btn-primary'>Update</button>
                                    </form>
                                  </td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>




<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Result Upload
if (isset($_POST['upload_result'])) {
    $adm_no = $_POST['adm_no'];
    $year = $_POST['year'];
    $term = $_POST['term'];
    $english = $_POST['english'];
    $kiswahili = $_POST['kiswahili'];
    $maths = $_POST['maths'];
    $home_science = $_POST['home_science'];
    $cre = $_POST['cre'];

    $check_result = $conn->query("SELECT * FROM results WHERE adm_no = '$adm_no' AND year = '$year' AND term = '$term'");
    if ($check_result->num_rows > 0) {
        $conn->query("UPDATE results 
                      SET english = '$english', kiswahili = '$kiswahili', maths = '$maths', 
                          home_science = '$home_science', cre = '$cre' 
                      WHERE adm_no = '$adm_no' AND year = '$year' AND term = '$term'");
    } else {
        $conn->query("INSERT INTO results (adm_no, year, term, english, kiswahili, maths, home_science, cre) 
                      VALUES ('$adm_no', '$year', '$term', '$english', '$kiswahili', '$maths', '$home_science', '$cre')");
    }

    echo "<div class='alert alert-success'>Result uploaded successfully!</div>";
}

// TEACHER DASHBOARD
if (isset($_SESSION['teacher_id'])) {
    $teacher_id = $_SESSION['teacher_id'];
    $teacher_result = $conn->query("SELECT * FROM teachers WHERE teacher_id = '$teacher_id'");
    $teacher = $teacher_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
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
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .table-responsive {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Header with Logout Button -->
    <div class="header">
        <h2>
            ELGIBOR | SABOTI | ACADEMY | SCHOOL<br>
            <span>Teacher Dashboard</span>
        </h2>
        <div class="logout-btn">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <?php if (isset($_SESSION['teacher_id'])): ?>
        <!-- Welcome Message -->
        <div class="container">
            <div class="alert alert-info">
                <h2>Welcome, <?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?></h2>
                <p>Email: <?php echo $teacher['email']; ?></p>
            </div>

            <!-- Classes Managed -->
            <?php
            $classes_result = $conn->query("SELECT * FROM classes WHERE teacher_id = '$teacher_id'");
            while ($class = $classes_result->fetch_assoc()):
            ?>
                <div class="form-container">
                    <h3>Class: <?php echo $class['class_name']; ?></h3>

                    <!-- Registered Students -->
                    <h4>Registered Students</h4>
                    <ul class="list-group">
                        <?php
                        $students_result = $conn->query("SELECT * FROM students WHERE class_id = '{$class['class_id']}'");
                        while ($student = $students_result->fetch_assoc()):
                        ?>
                            <li class="list-group-item">
                                <?php echo $student['first_name'] . ' ' . $student['last_name']; ?> (ADM No: <?php echo $student['adm_no']; ?>)
                            </li>
                        <?php endwhile; ?>
                    </ul>

                    <!-- Upload Results Form -->
                    <h4 class="mt-4">Upload Results</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="adm_no" class="form-label">Student:</label>
                            <select name="adm_no" class="form-control" required>
                                <?php
                                $students_result = $conn->query("SELECT * FROM students WHERE class_id = '{$class['class_id']}'");
                                while ($student = $students_result->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $student['adm_no']; ?>">
                                        <?php echo $student['first_name'] . ' ' . $student['last_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Year:</label>
                            <input type="text" name="year" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="term" class="form-label">Term:</label>
                            <select name="term" class="form-control" required>
                                <option value="1">Term 1</option>
                                <option value="2">Term 2</option>
                                <option value="3">Term 3</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="english" class="form-label">English:</label>
                            <input type="number" name="english" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="kiswahili" class="form-label">Kiswahili:</label>
                            <input type="number" name="kiswahili" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="maths" class="form-label">Maths:</label>
                            <input type="number" name="maths" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="home_science" class="form-label">Home Science:</label>
                            <input type="number" name="home_science" class="form-control" min="0" max="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="cre" class="form-label">CRE:</label>
                            <input type="number" name="cre" class="form-control" min="0" max="100" required>
                        </div>

                        <button type="submit" name="upload_result" class="btn btn-primary">Upload Result</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>


