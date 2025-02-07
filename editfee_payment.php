<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Check if a payment_id is provided
if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch payment details from the database
    $query = "SELECT p.payment_id, p.amount_paid, p.payment_date, p.payment_method, s.first_name, s.last_name
              FROM payments p 
              JOIN students s ON p.student_id = s.adm_no
              WHERE p.payment_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the record exists
    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
    } else {
        echo "Payment record not found.";
        exit;
    }
} else {
    echo "No payment ID provided.";
    exit;
}

// Handle form submission for updating payment record
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];

    // Update the payment record
    $update_query = "UPDATE payments SET amount_paid = ?, payment_date = ?, payment_method = ? WHERE payment_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $amount_paid, $payment_date, $payment_method, $payment_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Payment record updated successfully.'); window.location.href='';</script>";
    } else {
        echo "<script>alert('Error updating payment record.');</script>";
    }
    $update_stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h4>Edit Payment Record</h4>

    <!-- Edit Payment Form -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="first_name" class="form-label">Student Name:</label>
            <input type="text" class="form-control" id="first_name" value="<?php echo $payment['first_name'] . ' ' . $payment['last_name']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="amount_paid" class="form-label">Amount Paid (KES):</label>
            <input type="number" class="form-control" id="amount_paid" name="amount_paid" value="<?php echo $payment['amount_paid']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date:</label>
            <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo $payment['payment_date']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method:</label>
            <input type="text" class="form-control" id="payment_method" name="payment_method" value="<?php echo $payment['payment_method']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Payment</button>
        <a href="fee_payment_record.php" class="btn btn-secondary">Back to Records</a>
    </form>
</div>

<!-- Bootstrap JS & Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
