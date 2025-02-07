<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');

// Check for connection errors
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Edit Student Form
if (isset($_GET['edit'])) {
    $adm_no = $_GET['edit'];

    // Fetch student data for editing
    $student_sql = "SELECT * FROM students WHERE adm_no = '$adm_no'";
    $student_result = $conn->query($student_sql);

    // Check if student exists
    if ($student_result->num_rows > 0) {
        $student = $student_result->fetch_assoc();
    } else {
        echo "Student not found.";
        exit;
    }

    // Edit Student
    if (isset($_POST['edit_student'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $class_id = $_POST['class_id'];
        $year = $_POST['year'];

        $update_sql = "UPDATE students SET 
                        first_name = '$first_name', 
                        last_name = '$last_name', 
                        gender = '$gender', 
                        email = '$email', 
                        class_id = '$class_id', 
                        year = '$year' 
                        WHERE adm_no = '$adm_no'";

        if ($conn->query($update_sql)) {
            echo "Student details updated successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!-- Edit Student Form -->
<h3>Edit Student Details</h3>
<form method="POST" action="">
    <input type="hidden" name="adm_no" value="<?php echo $student['adm_no']; ?>">
    <div>
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo $student['first_name']; ?>" required><br>
    </div>
    <div>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $student['last_name']; ?>" required><br>
    </div>
    <div>
        <label>Gender:</label>
        <select name="gender">
            <option value="Male" <?php echo $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
        </select><br>
    </div>
    <div>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $student['email']; ?>" required><br>
    </div>
    <div>
        <label>Class ID:</label>
        <input type="number" name="class_id" value="<?php echo $student['class_id']; ?>" required><br>
    </div>
    <div>
        <label>Year:</label>
        <input type="number" name="year" value="<?php echo $student['year']; ?>" required><br>
    </div>
    <button type="submit" name="edit_student">Update Student</button>
</form>

<?php
$conn->close();  // Closing the connection after everything is done
?>
