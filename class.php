<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Add Class
if (isset($_POST['add_class'])) {
    $class_name = $_POST['class_name'];
    $teacher_id = $_POST['teacher_id'];

    $sql = "INSERT INTO classes (class_name, teacher_id) VALUES ('$class_name', '$teacher_id')";
    if ($conn->query($sql)) {
        echo "<script>alert('Class added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Edit Class
if (isset($_POST['edit_class'])) {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $teacher_id = $_POST['teacher_id'];

    $sql = "UPDATE classes SET class_name = '$class_name', teacher_id = '$teacher_id' WHERE class_id = '$class_id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Class updated successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Delete Class
if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $sql = "DELETE FROM classes WHERE class_id = '$class_id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Class deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch Teachers for Dropdown
$teachers = $conn->query("SELECT teacher_id, first_name, last_name FROM teachers");

// Fetch All Classes
$classes = $conn->query("SELECT c.*, t.first_name, t.last_name 
                         FROM classes c 
                         LEFT JOIN teachers t ON c.teacher_id = t.teacher_id");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .navbar-text {
            color: white !important;
        }
        .logout-btn {
            margin-left: auto;
        }
        .form-container {
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
    <!-- Navbar with Logout Button -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Class Management</a>
            <div class="logout-btn">
                <a href="admin_dash.php" class="btn btn-danger">Go Back</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Form to Add or Edit Class -->
        <div class="form-container">
            <h2><?php echo isset($_GET['edit']) ? 'Edit Class' : 'Add New Class'; ?></h2>
            <form method="POST" action="">
                <?php
                $edit_class = null;
                if (isset($_GET['edit'])) {
                    $edit_class_id = $_GET['edit'];
                    $conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
                    $edit_class = $conn->query("SELECT * FROM classes WHERE class_id = '$edit_class_id'")->fetch_assoc();
                    $conn->close();
                }
                ?>

                <input type="hidden" name="class_id" value="<?php echo $edit_class['class_id'] ?? ''; ?>">

                <div class="mb-3">
                    <label for="class_name" class="form-label">Class Name:</label>
                    <input type="text" name="class_name" id="class_name" class="form-control" required value="<?php echo $edit_class['class_name'] ?? ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Assign Teacher:</label>
                    <select name="teacher_id" id="teacher_id" class="form-control" required>
                        <option value="">--Select Teacher--</option>
                        <?php
                        $conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
                        $teachers = $conn->query("SELECT teacher_id, first_name, last_name FROM teachers");
                        while ($teacher = $teachers->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $teacher['teacher_id']; ?>"
                                <?php echo (isset($edit_class) && $edit_class['teacher_id'] == $teacher['teacher_id']) ? 'selected' : ''; ?>>
                                <?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?>
                            </option>
                        <?php endwhile; ?>
                        <?php $conn->close(); ?>
                    </select>
                </div>

                <button type="submit" name="<?php echo isset($edit_class) ? 'edit_class' : 'add_class'; ?>" class="btn btn-primary">
                    <?php echo isset($edit_class) ? 'Update Class' : 'Add Class'; ?>
                </button>
            </form>
        </div>

        <!-- Display All Classes -->
        <div class="table-responsive">
            <h2>Class List</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Class ID</th>
                        <th>Class Name</th>
                        <th>Assigned Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($class = $classes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($class['class_id']); ?></td>
                            <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                            <td><?php echo htmlspecialchars($class['first_name'] . ' ' . $class['last_name']); ?></td>
                            <td>
                                <a href="?edit=<?php echo $class['class_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?php echo $class['class_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>