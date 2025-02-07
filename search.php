<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Filter
$filter_sql = "SELECT * FROM students WHERE 1"; // Default query

if (isset($_POST['filter'])) {
    $adm_no = $_POST['adm_no'];
    $class_id = $_POST['class_id'];
    $year = $_POST['year'];

    // Apply filters
    if (!empty($adm_no)) {
        $filter_sql .= " AND adm_no LIKE '%$adm_no%'";
    }
    if (!empty($class_id)) {
        $filter_sql .= " AND class_id = '$class_id'";
    }
    if (!empty($year)) {
        $filter_sql .= " AND year = '$year'";
    }
}

// Get Student Data
$students = $conn->query($filter_sql);
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
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link, .btn-logout {
            color: #ffffff !important;
        }
        .btn-logout:hover {
            background-color: #dc3545;
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .form-control {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Student Dashboard</a>
            <div class="ms-auto">
                
                <button class="btn btn-logout" onclick="window.location.href='count.php'">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center my-4">Search Student</h2>

        <!-- Filter Form -->
        <form method="POST" action="" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="adm_no" placeholder="Search by Admission No" value="<?php echo isset($_POST['adm_no']) ? $_POST['adm_no'] : ''; ?>">
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control" name="class_id" placeholder="Class ID" value="<?php echo isset($_POST['class_id']) ? $_POST['class_id'] : ''; ?>">
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control" name="year" placeholder="Year" value="<?php echo isset($_POST['year']) ? $_POST['year'] : ''; ?>">
                </div>
            </div>
            <div class="text-center mt-2">
                <button type="submit" name="filter" class="btn btn-primary">Apply Filter</button>
            </div>
        </form>

        <!-- Display Students List -->
        <div class="card">
            <div class="card-header bg-primary text-white">Search Student</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Admission No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Class ID</th>
                            <th>Year</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display student data
                        while ($student = $students->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$student['adm_no']}</td>
                                    <td>{$student['first_name']}</td>
                                    <td>{$student['last_name']}</td>
                                    <td>{$student['gender']}</td>
                                    <td>{$student['email']}</td>
                                    <td>{$student['class_id']}</td>
                                    <td>{$student['year']}</td>
                                   
                                        
                                    </td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logout() {
            window.location.href = 'logout.php'; // Adjust the logout URL as needed
        }
    </script>
</body>
</html>

<?php
$conn->close();  // Closing the connection after everything is done
?>
