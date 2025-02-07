<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Initialize variables
$adm_no = null;
$class_id = null;
$search_adm_no = null;
$search_first_name = null;
$search_term = null;

// Handle Search Form Submission
if (isset($_POST['search'])) {
    $search_adm_no = $_POST['search_adm_no'];
    $search_first_name = $_POST['search_first_name'];
    $search_term = $_POST['search_term'];
}

// Handle Record Payment Form Submission
if (isset($_POST['record_payment'])) {
    $adm_no = $_POST['adm_no'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $term = $_POST['term'];

    // Get the student's class
    $class_query = $conn->prepare("SELECT class_id FROM students WHERE adm_no = ?");
    $class_query->bind_param("s", $adm_no);
    $class_query->execute();
    $class_result = $class_query->get_result();

    if ($class_result->num_rows > 0) {
        $row = $class_result->fetch_assoc();
        $class_id = $row['class_id'];
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid Admission Number</div>";
        exit;
    }

    // Get the term fee from class_fees
    $fee_query = $conn->prepare("SELECT amount FROM class_fees WHERE class_id = ? AND term = ?");
    $fee_query->bind_param("ii", $class_id, $term);
    $fee_query->execute();
    $fee_result = $fee_query->get_result();

    if ($fee_result->num_rows > 0) {
        $fee_row = $fee_result->fetch_assoc();
        $term_fee = $fee_row['amount'];
    } else {
        echo "<div class='alert alert-danger text-center'>Fee structure not found for this term.</div>";
        exit;
    }

    // Get total payments made for the term
    $paid_query = $conn->prepare("SELECT SUM(amount_paid) AS total_paid FROM payments WHERE student_id = ? AND term = ?");
    $paid_query->bind_param("si", $adm_no, $term);
    $paid_query->execute();
    $paid_result = $paid_query->get_result();
    $total_paid = ($paid_result->num_rows > 0) ? $paid_result->fetch_assoc()['total_paid'] : 0;

    $remaining_balance = $term_fee - $total_paid;

    if ($amount_paid > $remaining_balance) {
        $excess_amount = $amount_paid - $remaining_balance;
        $amount_paid = $remaining_balance; // Pay off the current term fully
    } else {
        $excess_amount = 0;
    }

    // Insert payment record
    $insert_query = $conn->prepare("INSERT INTO payments (student_id, amount_paid, payment_date, payment_method, term) 
                                    VALUES (?, ?, ?, ?, ?)");
    $insert_query->bind_param("sdssi", $adm_no, $amount_paid, $payment_date, $payment_method, $term);
    if ($insert_query->execute()) {
        echo "<div class='alert alert-success text-center'>Payment recorded successfully!";
        if ($amount_paid == $remaining_balance) {
            echo " Term $term fully paid.";
        } else {
            echo " Remaining balance for Term $term: Ksh " . ($remaining_balance - $amount_paid);
        }
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
    }

    // Apply excess amount to next term
    while ($excess_amount > 0 && $term < 3) {
        $term++;
        $fee_query = $conn->prepare("SELECT amount FROM class_fees WHERE class_id = ? AND term = ?");
        $fee_query->bind_param("ii", $class_id, $term);
        $fee_query->execute();
        $fee_result = $fee_query->get_result();

        if ($fee_result->num_rows > 0) {
            $fee_row = $fee_result->fetch_assoc();
            $term_fee = $fee_row['amount'];
        }

        $paid_query = $conn->prepare("SELECT SUM(amount_paid) AS total_paid FROM payments WHERE student_id = ? AND term = ?");
        $paid_query->bind_param("si", $adm_no, $term);
        $paid_query->execute();
        $paid_result = $paid_query->get_result();
        $total_paid = ($paid_result->num_rows > 0) ? $paid_result->fetch_assoc()['total_paid'] : 0;

        $remaining_balance = $term_fee - $total_paid;
        $payment_to_apply = min($excess_amount, $remaining_balance);

        if ($payment_to_apply > 0) {
            $conn->query("INSERT INTO payments (student_id, amount_paid, payment_date, payment_method, term) 
                          VALUES ('$adm_no', '$payment_to_apply', '$payment_date', '$payment_method', '$term')");
            echo "<div class='alert alert-success text-center'> Ksh $payment_to_apply applied to Term $term.</div>";
        }

        $excess_amount -= $payment_to_apply;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Fee Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #fff;
            padding: 20px;
            border: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h3 {
            color: #343a40;
        }
        .table {
            margin-top: 20px;
        }
        .alert {
            margin-top: 20px;
        }
        .form-control {
            margin-bottom: 10px;
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
        .header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .logout{
            position: absolute;
            top: 20px;
            left: 20px;

        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .logout a {
            text-decoration: none;
            color: white;
            background-color: #dc3545;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
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
        .logout a:hover {
            background-color:rgb(14, 13, 11);
            transform: translateY(-2px);
        }
        .logout-btn a:hover {
            background-color:rgb(231, 228, 10);
            transform: translateY(-2px);
        }
        
    </style>
</head>
<body>
<div class="header">
    <h1>Record Fee</h1>
        <div class="logout-btn">
            <a href="view_payment.php">VIEW</a>
        </div>
        <div class="logout">
             <a href="fee_dashboard.php">Go Back</a>
        </div>
    </div>

<div class="container mt-5">
    <h3 class="text-center">Record Payment</h3>
    <form method="POST">
        <label>Admission Number:</label>
        <input type="text" name="adm_no" class="form-control" required>
        <label>Amount Paid:</label>
        <input type="number" name="amount_paid" class="form-control" required>
        <label>Payment Date:</label>
        <input type="date" name="payment_date" class="form-control" required>
        <label>Payment Method:</label>
        <select name="payment_method" class="form-control">
            <option value="Cash">Cash</option>
            <option value="M-Pesa">M-Pesa</option>
            <option value="Bank Transfer">Bank Transfer</option>
        </select>
        <label>Term:</label>
        <select name="term" class="form-control">
            <option value="1">Term 1</option>
            <option value="2">Term 2</option>
            <option value="3">Term 3</option>
        </select>
        <button type="submit" name="record_payment" class="btn btn-primary mt-3">Record Payment</button>
    </form>

    
<script>
    // JavaScript for interactivity (if needed)
    document.addEventListener('DOMContentLoaded', function () {
        // Add any JavaScript functionality here
    });
</script>
</body>
</html>