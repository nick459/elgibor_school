<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Deletion
if (isset($_GET['delete'])) {
    $payment_id = $_GET['delete'];
    $delete_query = "DELETE FROM payments WHERE payment_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Payment record deleted successfully.'); window.location.href='fee_payment_record.php';</script>";
    } else {
        echo "<script>alert('Error deleting payment record.');</script>";
    }
    $stmt->close();
}

// Initialize filters
$adm_no = isset($_GET['adm_no']) ? trim($_GET['adm_no']) : '';
$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

// Query to fetch payment records
$query = "SELECT p.payment_id, s.adm_no, s.first_name, s.last_name, p.amount_paid, p.payment_date, p.payment_method, p.term
          FROM payments p 
          JOIN students s ON p.student_id = s.adm_no 
          WHERE 1=1";

$parameters = [];
$paramTypes = "";

// Apply filters
if (!empty($adm_no)) {
    $query .= " AND s.adm_no = ?";
    $parameters[] = $adm_no;
    $paramTypes .= "i"; // 'i' for integer
}
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND p.payment_date BETWEEN ? AND ?";
    $parameters[] = $start_date;
    $parameters[] = $end_date;
    $paramTypes .= "ss"; // 's' for string (date)
}

// Prepare statement
$stmt = $conn->prepare($query);

// Bind parameters dynamically
if (!empty($parameters)) {
    $stmt->bind_param($paramTypes, ...$parameters);
}

// Execute query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payment Records</title>
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
            padding: 20px;
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .logout{
            position: fixed;
            top: 20px;
            left: 20px;
        }
        .logout a {
            text-decoration: none;
            color: white;
            background-color:rgb(232, 44, 11);
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .logout a:hover {
            background-color:rgb(14, 13, 11);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Fee Payment Records</h2>
    <div class="logout">
    <a href="fee_dashboard.php">Go Back</a>
</div>
</div>

<div class="container">
    <!-- Search Form -->
    <h4>Search Fee Payments</h4>
    <form method="GET" action="" class="row g-3">
        <div class="col-md-4">
            <label for="adm_no" class="form-label">Admission Number:</label>
            <input type="number" id="adm_no" name="adm_no" class="form-control" value="<?php echo htmlspecialchars($adm_no); ?>">
        </div>
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>
</div>

<div class="container mt-4">
    <!-- Fee Payment Records Table -->
    <h4>Fee Payment Records</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Admission No</th>
                    <th>Student Name</th>
                    <th>Amount Paid (KES)</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                    <th>Term</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="paymentTable">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                    <td><?php echo $row['payment_id']; ?></td>
                        <td><?php echo $row['adm_no']; ?></td>
                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        <td>KES <?php echo number_format($row['amount_paid'], 2); ?></td>
                        <td><?php echo $row['payment_date']; ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                        <td><?php echo $row['term']; ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="editfee_payment.php?payment_id=<?php echo $row['payment_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <!-- Delete Button -->
                            <a href="?delete=<?php echo $row['payment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS & Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Live search using JavaScript
    document.getElementById("adm_no").addEventListener("keyup", function() {
        let input = this.value.toLowerCase();
        let rows = document.getElementById("paymentTable").getElementsByTagName("tr");

        for (let row of rows) {
            let admNo = row.cells[1].textContent.toLowerCase();
            row.style.display = admNo.includes(input) ? "" : "none";
        }
    });

    // Prevent submitting form if dates are invalid
    document.querySelector("form").addEventListener("submit", function(event) {
        let startDate = document.getElementById("start_date").value;
        let endDate = document.getElementById("end_date").value;

        if (startDate && endDate && startDate > endDate) {
            alert("Start date cannot be later than end date.");
            event.preventDefault();
        }
    });
</script>

</body>
</html>

<?php
// Close the connection
$stmt->close();
$conn->close();
?>

