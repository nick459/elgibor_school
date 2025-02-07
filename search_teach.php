<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Search for teachers by first name
$search_results = [];
if (isset($_POST['search_teacher'])) {
    $search_first_name = $_POST['search_first_name'];

    // SQL query to search teachers by first name
    $sql = "SELECT * FROM teachers WHERE first_name LIKE '%$search_first_name%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Store the results in the array
        while ($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    } else {
        echo "<script>alert('No teachers found with that first name.');</script>";
    }
}

// Handle Teacher Update
if (isset($_POST['update_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $sql = "UPDATE teachers SET first_name = '$first_name', last_name = '$last_name', email = '$email' WHERE teacher_id = '$teacher_id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Teacher updated successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch Teacher Data for Editing
$edit_teacher = null;
if (isset($_GET['edit'])) {
    $teacher_id = $_GET['edit'];
    $edit_teacher = $conn->query("SELECT * FROM teachers WHERE teacher_id = '$teacher_id'")->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Search</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .btn-logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- Logout Button -->
    <a href="teacher_dash.php" class="btn btn-danger btn-logout">Logout</a>

    <div class="container">
        <h2 class="text-center">Search Teachers</h2>

        <!-- Teacher Search Form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="search_first_name" class="form-label">Search by First Name</label>
                <input type="text" class="form-control" name="search_first_name" id="search_first_name" placeholder="Enter first name" required>
            </div>
            <div class="text-center">
                <button type="submit" name="search_teacher" class="btn btn-info">Search</button>
            </div>
        </form>

        <!-- Teacher Search Results Table -->
        <?php if (!empty($search_results)): ?>
            <h2 class="text-center mt-4">Teacher Search Results</h2>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_results as $teacher): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($teacher['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                            <td>
                                <a href="?edit=<?php echo $teacher['teacher_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Edit Teacher Form -->
        <?php if (isset($_GET['edit'])): ?>
            <h2 class="text-center mt-4">Edit Teacher</h2>
            <form method="POST" action="">
                <input type="hidden" name="teacher_id" value="<?php echo $edit_teacher['teacher_id']; ?>">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $edit_teacher['first_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $edit_teacher['last_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $edit_teacher['email']; ?>" required>
                </div>
                <div class="text-center">
                    <button type="submit" name="update_teacher" class="btn btn-primary">Update Teacher</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>