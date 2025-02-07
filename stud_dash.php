<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
            <span>Student Dashboard</span>
        </h2>
        <div class="logout-btn">
            <a href="schooldash.php">Logout</a>
        </div>
    </div>

    <?php if (isset($_SESSION['adm_no'])): ?>
        <?php
        $adm_no = $_SESSION['adm_no'];

        // Fetch Student Details
        $student_result = $conn->query("SELECT * FROM students WHERE adm_no = '$adm_no'");
        $student = $student_result->fetch_assoc();

        if ($student):
        ?>
            <!-- Welcome Message -->
            <div class="container">
                <div class="alert alert-info">
                    <h2>Welcome, <?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h2>
                    <p>Admission Number: <?php echo $student['adm_no']; ?></p>
                </div>

                <!-- Class and Teacher Details -->
                <?php
                $class_result = $conn->query("SELECT * FROM classes WHERE class_id = '{$student['class_id']}'");
                $class = $class_result->fetch_assoc();
                $teacher_result = $conn->query("SELECT * FROM teachers WHERE teacher_id = '{$class['teacher_id']}'");
                $teacher = $teacher_result->fetch_assoc();
                ?>
                <div class="alert alert-secondary">
                    <p>Class: <?php echo $class['class_name']; ?></p>
                    <p>Class Teacher: <?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?></p>
                </div>

                <!-- Fee Details: Amount, Paid, and Balance -->
                <?php
                // Fetch fee information based on the student's class_id for all terms (1-3)
                $fee_result = $conn->query("SELECT * FROM class_fees WHERE class_id = '{$student['class_id']}' ORDER BY term ASC");
                if ($fee_result->num_rows > 0):
                ?>
                    <div class="alert alert-success">
                        <h4>Fee Details:</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Term</th>
                                    <th>Fee Due (Ksh)</th>
                                    <th>Fee Paid (Ksh)</th>
                                    <th>Balance (Ksh)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($fee = $fee_result->fetch_assoc()): ?>
                                    <?php
                                    // Fetch total payments made for the term
                                    $paid_query = $conn->query("SELECT SUM(amount_paid) AS total_paid FROM payments WHERE student_id = '$adm_no' AND term = '{$fee['term']}'");
                                    $total_paid = ($paid_query->num_rows > 0) ? $paid_query->fetch_assoc()['total_paid'] : 0;
                                    $balance = $fee['amount'] - $total_paid;
                                    ?>
                                    <tr>
                                        <td>Term <?php echo $fee['term']; ?></td>
                                        <td><?php echo $fee['amount']; ?></td>
                                        <td><?php echo $total_paid; ?></td>
                                        <td><?php echo $balance; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">Fee details not available for your class.</div>
                <?php endif; ?>

                <!-- Fee Status -->
                <?php
                $fee_status_result = $conn->query("SELECT * FROM fees WHERE adm_no = '$adm_no'");
                $fee_status = $fee_status_result->fetch_assoc();
                $fee_status = $fee_status ? $fee_status['status'] : 'Not Paid';
                ?>
                <div class="alert alert-warning">
                    <p>Fee Status: <?php echo $fee_status; ?></p>
                </div>

                <!-- Filter Form for Year and Term -->
                <div class="form-container">
                    <h3>View Results</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="year" class="form-label">Year:</label>
                            <input type="number" name="year" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="term" class="form-label">Term:</label>
                            <select name="term" class="form-control" required>
                                <option value="1">Term 1</option>
                                <option value="2">Term 2</option>
                                <option value="3">Term 3</option>
                            </select>
                        </div>
                        <button type="submit" name="filter_results" class="btn btn-primary">Filter Results</button>
                    </form>
                </div>

                <!-- Display Results Based on Filter -->
                <?php if (isset($_POST['filter_results'])): ?>
                    <?php
                    $year = $_POST['year'];
                    $term = $_POST['term'];

                    $result_query = $conn->query("SELECT * FROM results WHERE adm_no = '$adm_no' AND year = '$year' AND term = '$term'");
                    $result_data = $result_query->fetch_assoc();
                    ?>
                    <div class="table-responsive">
                        <?php if ($result_data): ?>
                            <h3>Results for Year: <?php echo $year; ?>, Term: <?php echo $term; ?></h3>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>English</td>
                                        <td><?php echo $result_data['english']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Kiswahili</td>
                                        <td><?php echo $result_data['kiswahili']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Maths</td>
                                        <td><?php echo $result_data['maths']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Home Science</td>
                                        <td><?php echo $result_data['home_science']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>CRE</td>
                                        <td><?php echo $result_data['cre']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning">No results found for the selected year and term.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">Student not found.</div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning">Please log in to access the dashboard.</div>
    <?php endif; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>