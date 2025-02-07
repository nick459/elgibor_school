<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Check if fee_id is provided
if (isset($_GET['fee_id'])) {
    $fee_id = $_GET['fee_id'];

    // Delete the class fee record
    $delete_query = "DELETE FROM class_fees WHERE fee_id = $fee_id";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "Class fee deleted successfully!";
        // Redirect to the page where fees are displayed (e.g., class_fee.php)
        header("Location: class_fee.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Fee ID not provided!";
}
?>
