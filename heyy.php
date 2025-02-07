<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: heyy.php");
    exit();
}

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Add Fee Record Form Submission
if (isset($_POST['add_fee'])) {
    $adm_no = $_POST['adm_no'];
    $total_amount = $_POST['total_amount'];
    $due_date = $_POST['due_date'];

    // Check if the student exists
    $student_query = $conn->query("SELECT * FROM students WHERE adm_no = '$adm_no'");
    if ($student_query->num_rows > 0) {
        // Insert fee record into school_fees table
        $insert_query = "INSERT INTO school_fees (student_id, total_amount, due_date) 
                         VALUES ('$adm_no', '$total_amount', '$due_date')";
        if ($conn->query($insert_query)) {
            echo "<div class='alert alert-success'>Fee record added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Student with Admission Number $adm_no not found.</div>";
    }
}

// Handle Record Payment Form Submission
if (isset($_POST['record_payment'])) {
    $adm_no = $_POST['adm_no'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];

    // Insert payment record into payments table
    $insert_query = "INSERT INTO payments (student_id, amount_paid, payment_date, payment_method) 
                     VALUES ('$adm_no', '$amount_paid', '$payment_date', '$payment_method')";
    if ($conn->query($insert_query)) {
        echo "<div class='alert alert-success'>Payment recorded successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Fee Portal</title>
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
            padding: 15px 15px;
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            position: fixed;
            top: 0;
            width: 100%;
        }
        .header h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
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
        .form-container {
            background-color: greenyellow;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 50%;
        }
        .table-responsive {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Header with Logout Button -->
    <div class="header">
        <h2>
            ELGIBOR | SABOTI | ACADEMY | SCHOOL<br>
            <span>Admin Fee Portal</span>
        </h2>
        <div class="logout-btn">
            <a href="logout.php">Logout</a>
        </div>
    </div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <div class="container">
        <!-- Add Fee Record Form -->
        <div class="form-container">
            <h3>Add Fee Record</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="adm_no" class="form-label">Admission Number:</label>
                    <input type="text" name="adm_no" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="total_amount" class="form-label">Total Amount:</label>
                    <input type="number" name="total_amount" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label">Due Date:</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>
                <button type="submit" name="add_fee" class="btn btn-primary">Add Fee</button>
            </form>
        </div>

        <!-- Record Payment Form -->
        <div class="form-container">
            <h3>Record Payment</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="adm_no" class="form-label">Admission Number:</label>
                    <input type="text" name="adm_no" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="amount_paid" class="form-label">Amount Paid:</label>
                    <input type="number" name="amount_paid" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="payment_date" class="form-label">Payment Date:</label>
                    <input type="date" name="payment_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method:</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="Cash">Cash</option>
                        <option value="M-Pesa">M-Pesa</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
                <button type="submit" name="record_payment" class="btn btn-primary">Record Payment</button>
            </form>
        </div>

        <!-- Fee Records Filter -->
        <div class="form-container">
            <h3>Filter Fee Records</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="filter_adm_no" class="form-label">Admission Number:</label>
                    <input type="text" name="filter_adm_no" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="filter_class_id" class="form-label">Class ID:</label>
                    <input type="number" name="filter_class_id" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="filter_amount" class="form-label">Amount:</label>
                    <input type="number" name="filter_amount" class="form-control" step="0.01">
                </div>
                <button type="submit" name="filter_fee_records" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <!-- Fee Records Table -->
        <?php
        $fee_records_query = "SELECT sf.*, s.class_id 
                              FROM school_fees sf 
                              JOIN students s ON sf.student_id = s.adm_no";
        if (isset($_POST['filter_fee_records'])) {
            $filter_adm_no = $_POST['filter_adm_no'];
            $filter_class_id = $_POST['filter_class_id'];
            $filter_amount = $_POST['filter_amount'];

            if (!empty($filter_adm_no)) {
                $fee_records_query .= " WHERE sf.student_id LIKE '%$filter_adm_no%'";
            }
            if (!empty($filter_class_id)) {
                $fee_records_query .= (strpos($fee_records_query, 'WHERE') === false) ? " WHERE" : " AND";
                $fee_records_query .= " s.class_id = '$filter_class_id'";
            }
            if (!empty($filter_amount)) {
                $fee_records_query .= (strpos($fee_records_query, 'WHERE') === false) ? " WHERE" : " AND";
                $fee_records_query .= " sf.total_amount = '$filter_amount'";
            }
        }
        $fee_records_result = $conn->query($fee_records_query);
        ?>
        <div class="table-responsive">
            <h3>Fee Records</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Admission No</th>
                        <th>Class ID</th>
                        <th>Total Amount</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fee = $fee_records_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fee['student_id']; ?></td>
                            <td><?php echo $fee['class_id']; ?></td>
                            <td>KES <?php echo number_format($fee['total_amount'], 2); ?></td>
                            <td><?php echo $fee['due_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>