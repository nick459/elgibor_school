<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Fee Deletion
if (isset($_GET['delete'])) {
    $student_id = $_GET['delete'];

    // Prepare delete query
    $delete_sql = "DELETE FROM school_fees WHERE student_id = ?";

    // Prepare and execute the delete statement
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $student_id);
    
    if ($delete_stmt->execute()) {
        echo "<script>alert('Fee record deleted successfully.'); window.location.href='';</script>";
    } else {
        echo "<script>alert('Error: Could not delete fee record.');</script>";
    }

    $delete_stmt->close();
}

// Initialize the query
$fee_records_query = "SELECT sf.*, s.class_id FROM school_fees sf JOIN students s ON sf.student_id = s.adm_no WHERE 1=1";

// Handle filter submission
$filters = [];
$types = "";
$params = [];

if (isset($_POST['filter_fee_records'])) {
    if (!empty($_POST['filter_adm_no'])) {
        $filters[] = "sf.student_id LIKE ?";
        $types .= "s";
        $params[] = "%" . $_POST['filter_adm_no'] . "%";
    }
    if (!empty($_POST['filter_class_id'])) {
        $filters[] = "s.class_id = ?";
        $types .= "i";
        $params[] = $_POST['filter_class_id'];
    }
    if (!empty($_POST['filter_amount'])) {
        $filters[] = "sf.total_amount = ?";
        $types .= "d";
        $params[] = $_POST['filter_amount'];
    }

    if (!empty($filters)) {
        $fee_records_query .= " AND " . implode(" AND ", $filters);
    }
}

// Prepare and execute the query
$stmt = $conn->prepare($fee_records_query);
if (!empty($filters)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$fee_records_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Records</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-bottom: 20px;
        }
        .table-responsive {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }
    </style>
</head>
<body>

<!-- Fee Records Filter -->
<div class="form-container">
    <h3 class="text-center">Filter Fee Records</h3>
    <form method="POST" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="filter_adm_no" class="form-label">Admission Number:</label>
            <input type="text" name="filter_adm_no" id="filter_adm_no" class="form-control">
        </div>
        <div class="mb-3">
            <label for="filter_class_id" class="form-label">Class ID:</label>
            <input type="number" name="filter_class_id" id="filter_class_id" class="form-control">
        </div>
        <div class="mb-3">
            <label for="filter_amount" class="form-label">Amount:</label>
            <input type="number" name="filter_amount" id="filter_amount" class="form-control" step="0.01">
        </div>
        <button type="submit" name="filter_fee_records" class="btn btn-primary w-100">Filter</button>
    </form>
</div>

<!-- Fee Records Table -->
<div class="table-responsive">
    <h3 class="text-center">Fee Records</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Admission No</th>
                <th>Class ID</th>
                <th>Total Amount</th>
                <th>Due Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fee = $fee_records_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fee['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($fee['class_id']); ?></td>
                    <td>KES <?php echo number_format($fee['total_amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($fee['due_date']); ?></td>
                    <td>
                        <!-- Delete Button -->
                        <a href="?delete=<?php echo $fee['student_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- JavaScript Validation -->
<script>
    function validateForm() {
        let admNo = document.getElementById("filter_adm_no").value;
        let classId = document.getElementById("filter_class_id").value;
        let amount = document.getElementById("filter_amount").value;

        if (admNo && isNaN(admNo)) {
            alert("Admission Number should be numeric.");
            return false;
        }
        if (classId && (isNaN(classId) || classId <= 0)) {
            alert("Please enter a valid Class ID.");
            return false;
        }
        if (amount && amount <= 0) {
            alert("Amount should be greater than zero.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
