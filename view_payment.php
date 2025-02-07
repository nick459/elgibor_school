<?php
// Database Connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$search_adm_no = $search_first_name = $search_term = "";

// Handle Search Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_adm_no = $_POST['search_adm_no'];
    $search_first_name = $_POST['search_first_name'];
    $search_term = $_POST['search_term'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 40%;
            max-width: 450px;
            margin: 50px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .table-container {
            width: 90%;
            margin: auto;
        }
        .logout{
            position: fixed;
            top: 20px;
            left: 20px;
        }
        .logout a {
            text-decoration: none;
            color: white;
            background-color:rgb(33, 223, 12);
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
<div class="logout">
    <a href="f_payment.php">Return</a>
</div>
<!-- Search Form -->
<div class="form-container">
    <h3 class="text-center">Search Payment Records</h3>
    <form method="POST">
        <label>Admission Number:</label>
        <input type="text" name="search_adm_no" class="form-control mb-2" value="<?php echo htmlspecialchars($search_adm_no); ?>">

        <label>First Name:</label>
        <input type="text" name="search_first_name" class="form-control mb-2" value="<?php echo htmlspecialchars($search_first_name); ?>">

        <label>Term:</label>
        <select name="search_term" class="form-control mb-3">
            <option value="">All Terms</option>
            <option value="1" <?php if ($search_term == '1') echo 'selected'; ?>>Term 1</option>
            <option value="2" <?php if ($search_term == '2') echo 'selected'; ?>>Term 2</option>
            <option value="3" <?php if ($search_term == '3') echo 'selected'; ?>>Term 3</option>
        </select>

        <button type="submit" name="search" class="btn btn-primary w-100">Search</button>
    </form>
</div>

<!-- Payment Records Table -->
<div class="table-container">
    <h3 class="text-center mt-4">Payment Records</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Admission Number</th>
                <th>First Name</th>
                <th>Term</th>
                <th>Amount Paid</th>
                <th>Fee Due</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch payment records
            $query = "SELECT p.student_id, s.first_name, p.term, SUM(p.amount_paid) as paid, 
                             cf.amount AS fee
                      FROM payments p 
                      JOIN students s ON p.student_id = s.adm_no
                      JOIN class_fees cf ON s.class_id = cf.class_id AND p.term = cf.term
                      WHERE 1";

            if (!empty($search_adm_no)) {
                $query .= " AND p.student_id = ?";
            }
            if (!empty($search_first_name)) {
                $query .= " AND s.first_name LIKE ?";
            }
            if (!empty($search_term)) {
                $query .= " AND p.term = ?";
            }

            $query .= " GROUP BY p.student_id, p.term";
            $stmt = $conn->prepare($query);

            if (!empty($search_adm_no) && !empty($search_first_name) && !empty($search_term)) {
                $stmt->bind_param("ssi", $search_adm_no, $search_first_name, $search_term);
            } elseif (!empty($search_adm_no) && !empty($search_first_name)) {
                $stmt->bind_param("ss", $search_adm_no, $search_first_name);
            } elseif (!empty($search_adm_no) && !empty($search_term)) {
                $stmt->bind_param("si", $search_adm_no, $search_term);
            } elseif (!empty($search_first_name) && !empty($search_term)) {
                $stmt->bind_param("si", $search_first_name, $search_term);
            } elseif (!empty($search_adm_no)) {
                $stmt->bind_param("s", $search_adm_no);
            } elseif (!empty($search_first_name)) {
                $stmt->bind_param("s", $search_first_name);
            } elseif (!empty($search_term)) {
                $stmt->bind_param("i", $search_term);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $adm_no = $row['student_id'];
                    $first_name = $row['first_name'];
                    $term = $row['term'];
                    $paid = $row['paid'];
                    $fee = $row['fee'];
                    $balance = $fee - $paid;

                    echo "<tr>
                            <td>$adm_no</td>
                            <td>$first_name</td>
                            <td>Term $term</td>
                            <td>Ksh $paid</td>
                            <td>Ksh $fee</td>
                            <td>Ksh $balance</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
