<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle form submission for inserting class data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $teacher_id = $_POST['teacher_id'];

    // Insert into classes table
    $query = "INSERT INTO classes (class_name, teacher_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $class_name, $teacher_id); // 's' for string, 'i' for integer

    if ($stmt->execute()) {
        echo "<script>alert('Class added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Fetch teachers for dropdown
$teachers = [];
$sql = "SELECT teacher_id, CONCAT(first_name, ' ', last_name) AS full_name FROM teachers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
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
        .form-control {
            border-radius: 5px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Add New Class</h2>

    <!-- Form to Insert Class Data -->
    <form action="" method="POST" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="class_name" class="form-label">Class Name:</label>
            <input type="text" class="form-control" id="class_name" name="class_name" required>
        </div>

        <div class="mb-3">
            <label for="teacher_id" class="form-label">Assign Teacher:</label>
            <select class="form-control" id="teacher_id" name="teacher_id" required>
                <option value="">Select Teacher</option>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo htmlspecialchars($teacher['full_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-submit w-100">Add Class</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript Validation
    function validateForm() {
        let className = document.getElementById("class_name").value;
        let teacherId = document.getElementById("teacher_id").value;

        if (className == "" || teacherId == "") {
            alert("All fields must be filled out.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
