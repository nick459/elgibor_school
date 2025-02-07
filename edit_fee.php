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

    // Fetch existing data from class_fees
    $query = "SELECT * FROM class_fees WHERE fee_id = $fee_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die('Fee record not found!');
    }
}

// Fetch classes from the classes table for the dropdown
$class_query = "SELECT * FROM classes";
$class_result = mysqli_query($conn, $class_query);

// Handle form submission for updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($fee_id)) {
    $class_id = $_POST['class_id'];
    $term = $_POST['term'];
    $amount = $_POST['amount'];

    // Update the class_fees data
    $update_query = "UPDATE class_fees SET class_id='$class_id', term='$term', amount='$amount' WHERE fee_id=$fee_id";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        echo "Class fee updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class Fee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 1rem;
            margin: 10px 0 5px;
            display: block;
            color: #555;
        }
        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-btn {
            text-align: center;
            margin-top: 20px;
        }
        .back-btn a {
            color: #007BFF;
            text-decoration: none;
            font-size: 1rem;
        }
        .back-btn a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Class Fee</h2>

    <!-- Form to Edit Class Fees -->
    <form action="edit_fee.php?fee_id=<?php echo $fee_id; ?>" method="POST">
      <label for="class_id">Class:</label>
      <select id="class_id" name="class_id" required>
        <?php while ($class_row = mysqli_fetch_assoc($class_result)) { ?>
          <option value="<?php echo $class_row['class_id']; ?>" <?php echo ($row['class_id'] == $class_row['class_id']) ? 'selected' : ''; ?>>
            <?php echo $class_row['class_name']; ?>
          </option>
        <?php } ?>
      </select><br>

      <label for="term">Term:</label>
      <select id="term" name="term" required>
        <option value="Term 1" <?php echo ($row['term'] == 'Term 1') ? 'selected' : ''; ?>>Term 1</option>
        <option value="Term 2" <?php echo ($row['term'] == 'Term 2') ? 'selected' : ''; ?>>Term 2</option>
        <option value="Term 3" <?php echo ($row['term'] == 'Term 3') ? 'selected' : ''; ?>>Term 3</option>
      </select><br>

      <label for="amount">Amount:</label>
      <input type="number" id="amount" name="amount" value="<?php echo $row['amount']; ?>" required><br>

      <button type="submit">Update Fees</button>
    </form>

    <div class="back-btn">
        <a href="class_fee.php">Back to Class Fees List</a>
    </div>
</div>

</body>
</html>
