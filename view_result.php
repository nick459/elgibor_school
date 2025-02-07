<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle Search and Filter
$search_results = [];
if (isset($_POST['search'])) {
    $adm_no = $_POST['adm_no'];
    $year = $_POST['year'];
    $term = $_POST['term'];
    $subject = $_POST['subject'];

    // Build the SQL query dynamically based on filters
    $sql = "SELECT * FROM results WHERE 1=1";
    if (!empty($adm_no)) {
        $sql .= " AND adm_no LIKE '%$adm_no%'";
    }
    if (!empty($year)) {
        $sql .= " AND year = '$year'";
    }
    if (!empty($term)) {
        $sql .= " AND term = '$term'";
    }
    if (!empty($subject)) {
        $sql .= " AND $subject IS NOT NULL"; // Filter by subject column
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    } else {
        echo "<script>alert('No results found.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
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
        .filter-form {
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
            <span>Student Results Management</span>
        </h2>
        <div class="logout-btn">
            <a href="admin_dash.php">Logout</a>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="filter-form">
        <h3>Search and Filter Results</h3>
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="adm_no" class="form-label">Admission No:</label>
                    <input type="text" name="adm_no" id="adm_no" class="form-control" placeholder="Enter Admission No" value="<?php echo isset($_POST['adm_no']) ? htmlspecialchars($_POST['adm_no']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Year:</label>
                    <input type="number" name="year" id="year" class="form-control" placeholder="Enter Year" value="<?php echo isset($_POST['year']) ? htmlspecialchars($_POST['year']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label for="term" class="form-label">Term:</label>
                    <select name="term" id="term" class="form-control">
                        <option value="">--Select Term--</option>
                        <option value="1" <?php echo (isset($_POST['term']) && $_POST['term'] == '1') ? 'selected' : ''; ?>>Term 1</option>
                        <option value="2" <?php echo (isset($_POST['term']) && $_POST['term'] == '2') ? 'selected' : ''; ?>>Term 2</option>
                        <option value="3" <?php echo (isset($_POST['term']) && $_POST['term'] == '3') ? 'selected' : ''; ?>>Term 3</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subject" class="form-label">Subject:</label>
                    <select name="subject" id="subject" class="form-control">
                        <option value="">--Select Subject--</option>
                        <option value="english" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'english') ? 'selected' : ''; ?>>English</option>
                        <option value="kiswahili" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'kiswahili') ? 'selected' : ''; ?>>Kiswahili</option>
                        <option value="maths" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'maths') ? 'selected' : ''; ?>>Maths</option>
                        <option value="home_science" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'home_science') ? 'selected' : ''; ?>>Home Science</option>
                        <option value="cre" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'cre') ? 'selected' : ''; ?>>CRE</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" name="search" class="btn btn-primary btn-custom">Search</button>
                    <a href="?" class="btn btn-secondary">Clear Filters</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Display Results -->
    <div class="table-responsive">
        <h3>Student Results</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Result ID</th>
                    <th>Admission No</th>
                    <th>Year</th>
                    <th>Term</th>
                    <th>English</th>
                    <th>Kiswahili</th>
                    <th>Maths</th>
                    <th>Home Science</th>
                    <th>CRE</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($search_results)): ?>
                    <?php foreach ($search_results as $result): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($result['result_id']); ?></td>
                            <td><?php echo htmlspecialchars($result['adm_no']); ?></td>
                            <td><?php echo htmlspecialchars($result['year']); ?></td>
                            <td><?php echo htmlspecialchars($result['term']); ?></td>
                            <td><?php echo htmlspecialchars($result['english']); ?></td>
                            <td><?php echo htmlspecialchars($result['kiswahili']); ?></td>
                            <td><?php echo htmlspecialchars($result['maths']); ?></td>
                            <td><?php echo htmlspecialchars($result['home_science']); ?></td>
                            <td><?php echo htmlspecialchars($result['cre']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>