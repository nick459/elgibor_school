<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Fees Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .form-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 40%; /* Reduce width */
    max-width: 400px; /* Set a maximum width */
    margin: 50px auto; /* Center it horizontally */
    display: flex;
    flex-direction: column;
    align-items: center; /* Center content inside */
        }
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            flex-direction: column;

        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .action-buttons button {
            margin-right: 5px;
        }
        .edit-btn {
            background-color: #ffc107;
        }
        .delete-btn {
            background-color: #dc3545;
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
        .logout-btn {
            position: fixed;
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
            background-color:rgb(231, 228, 10);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="header">
    <h1>Assign Fees Per Class</h1>
    <div class="logout-btn">
            <a href="fee_dashboard.php">Go Back</a>
        </div>
    </div>

    <!-- Form to Assign Data -->
    <div class="form-container">
        <h2>Assign Class Fees</h2>
        <form method="POST" action="">
            <label for="class_id">Class ID:</label>
            <input type="text" id="class_id" name="class_id" required>

            <label for="term">Term:</label>
            <select id="term" name="term" required>
                <option value="1">Term 1</option>
                <option value="2">Term 2</option>
                <option value="3">Term 3</option>
            </select>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="0.01" required>

            <button type="submit" name="submit">Assign Fee</button>
        </form>
    </div>

    <!-- Display Table with Edit and Delete Buttons -->
    <div class="table-container">
        <h2>Class Fees List</h2>
        <table>
            <thead>
                <tr>
                    <th>Fee ID</th>
                    <th>Class ID</th>
                    <th>Term</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "db_elgibor_management";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Insert data into the table
                if (isset($_POST['submit'])) {
                    $class_id = $_POST['class_id'];
                    $term = $_POST['term'];
                    $amount = $_POST['amount'];

                    // Use prepared statements to prevent SQL injection
                    $stmt = $conn->prepare("INSERT INTO class_fees (class_id, term, amount) VALUES (?, ?, ?)");
                    $stmt->bind_param("sii", $class_id, $term, $amount);

                    if ($stmt->execute()) {
                        echo "<p>Fee assigned successfully!</p>";
                    } else {
                        echo "<p>Error: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                }

                // Fetch data from the table
                $sql = "SELECT * FROM class_fees";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['fee_id']}</td>
                                <td>{$row['class_id']}</td>
                                <td>{$row['term']}</td>
                                <td>{$row['amount']}</td>
                                <td class='action-buttons'>
                                    <button class='edit-btn' onclick='editFee({$row['fee_id']})'>Edit</button>
                                    <button class='delete-btn' onclick='deleteFee({$row['fee_id']})'>Delete</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No fees assigned yet.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function editFee(feeId) {
            alert("Edit fee with ID: " + feeId);
            // Redirect to an edit page or show a modal
            window.location.href = "edit_fee.php?fee_id=" + feeId;
        }

        function deleteFee(feeId) {
            if (confirm("Are you sure you want to delete this fee?")) {
                window.location.href = "del_fee.php?fee_id=" + feeId; // Redirect to a PHP script to handle deletion
            }
        }
    </script>
</body>
</html>