<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Student Update
if (isset($_POST['edit_student'])) {
    $adm_no = $_POST['adm_no'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $class_id = $_POST['class_id'];
    $year = $_POST['year'];

    $update_sql = "UPDATE students SET first_name='$first_name', last_name='$last_name', gender='$gender', email='$email', class_id='$class_id', year='$year' WHERE adm_no='$adm_no'";

    if ($conn->query($update_sql)) {
        echo "<script>alert('Student details updated successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Handle Student Deletion
if (isset($_GET['delete'])) {
    $adm_no = $_GET['delete'];
    $delete_sql = "DELETE FROM students WHERE adm_no='$adm_no'";

    if ($conn->query($delete_sql)) {
        echo "<script>alert('Student deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error: Could not delete student.');</script>";
    }
}

// Handle Filters
$year_filter = isset($_GET['year']) ? $_GET['year'] : '';
$class_filter = isset($_GET['class_id']) ? $_GET['class_id'] : '';
$adm_no_filter = isset($_GET['adm_no']) ? $_GET['adm_no'] : '';

// Build SQL Query with Filters
$students_sql = "SELECT * FROM students WHERE 1=1";
if (!empty($year_filter)) {
    $students_sql .= " AND year = '$year_filter'";
}
if (!empty($class_filter)) {
    $students_sql .= " AND class_id = '$class_filter'";
}
if (!empty($adm_no_filter)) {
    $students_sql .= " AND adm_no LIKE '%$adm_no_filter%'";
}

$students = $conn->query($students_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
        }

        .header {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 20px;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .filter-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-form h3 {
            margin-bottom: 20px;
        }

        .filter-form .form-label {
            font-weight: bold;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 12px;
            text-align: center;
        }

        .btn-primary {
            background-color: #4CAF50;
        }

        .btn-secondary {
            background-color: #f1f1f1;
            color: #333;
        }

        .btn-danger {
            background-color: #d9534f;
        }

        .btn-sm {
            padding: 5px 10px;
        }

        .form-control {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>ELGIBOR | SABOTI | ACADEMY | SCHOOL</h2>
        <span>STUDENTS TABLE</span>
    </div>

    <!-- Navbar with Logout Button -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Student Dashboard</a>
            <div class="logout-btn">
                <a href="count.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Filter Form -->
        <div class="filter-form mb-3">
            <h3>Filter Students:</h3>
            <form method="GET" action="" class="row g-3">
                <div class="col-md-3">
                    <label for="adm_no" class="form-label">Admission No:</label>
                    <input type="text" name="adm_no" id="adm_no" class="form-control" value="<?php echo htmlspecialchars($adm_no_filter); ?>" placeholder="Enter Admission No">
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Year:</label>
                    <input type="text" name="year" id="year" class="form-control" value="<?php echo htmlspecialchars($year_filter); ?>" placeholder="Enter Year">
                </div>
                <div class="col-md-3">
                    <label for="class_id" class="form-label">Class ID:</label>
                    <input type="text" name="class_id" id="class_id" class="form-control" value="<?php echo htmlspecialchars($class_filter); ?>" placeholder="Enter Class ID">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Apply Filter</button>
                    <a href="?" class="btn btn-secondary">Clear Filters</a>
                </div>
            </form>
        </div>

        <!-- Display Students List -->
        <div class="table-responsive">
            <h3>Student List:</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Admission No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Class ID</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($students->num_rows > 0) {
                        while ($row = $students->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['adm_no']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['class_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                            echo "<td>
                                  
                                    <a href='?delete=" . $row['adm_no'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this student?\");'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No students found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        // Edit Student Form
        if (isset($_GET['edit'])) {
            $adm_no = $_GET['edit'];
            $edit_sql = "SELECT * FROM students WHERE adm_no='$adm_no'";
            $edit_result = $conn->query($edit_sql);
            $edit_row = $edit_result->fetch_assoc();
            ?>
            <div class="mt-4">
                <h3>Edit Student:</h3>
                <form method="POST" action="">
                    <input type="hidden" name="adm_no" value="<?php echo htmlspecialchars($edit_row['adm_no']); ?>">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($edit_row['first_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($edit_row['last_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender:</label>
                        <input type="text" name="gender" class="form-control" value="<?php echo htmlspecialchars($edit_row['gender']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($edit_row['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class ID:</label>
                        <input type="text" name="class_id" class="form-control" value="<?php echo htmlspecialchars($edit_row['class_id']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year:</label>
                        <input type="text" name="year" class="form-control" value="<?php echo htmlspecialchars($edit_row['year']); ?>" required>
                    </div>
                    <button type="submit" name="edit_student" class="btn btn-primary">Update Student</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();  // Closing the connection after everything is done
?>
