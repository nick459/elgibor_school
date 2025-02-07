<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'db_elgibor_management');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Edit Student
if (isset($_POST['edit_student'])) {
    $adm_no = $_POST['adm_no'];
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

// Delete Student
if (isset($_GET['delete'])) {
    $adm_no = $_GET['delete'];

    $delete_sql = "DELETE FROM students WHERE adm_no = '$adm_no'";

    if ($conn->query($delete_sql)) {
        echo "Student deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle Filter
$filter_sql = "SELECT * FROM students WHERE 1"; // Default query

if (isset($_POST['filter'])) {
    $adm_no = $_POST['adm_no'];
    $class_id = $_POST['class_id'];
    $year = $_POST['year'];

    // Apply filters
    if (!empty($adm_no)) {
        $filter_sql .= " AND adm_no LIKE '%$adm_no%'";
    }
    if (!empty($class_id)) {
        $filter_sql .= " AND class_id = '$class_id'";
    }
    if (!empty($year)) {
        $filter_sql .= " AND year = '$year'";
    }
}

// Get Student Data
$students = $conn->query($filter_sql);

// Count Total Students
$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc();

// Count Male and Female Students
$male_count = $conn->query("SELECT COUNT(*) AS male_count FROM students WHERE gender = 'Male'")->fetch_assoc();
$female_count = $conn->query("SELECT COUNT(*) AS female_count FROM students WHERE gender = 'Female'")->fetch_assoc();

?>

<!-- Dashboard HTML -->
<h2>Student Dashboard</h2>

<!-- Filter Form -->
<form method="POST" action="">
    <label for="adm_no">Admission No:</label>
    <input type="text" name="adm_no" placeholder="Search by Admission No" value="<?php echo isset($_POST['adm_no']) ? $_POST['adm_no'] : ''; ?>"><br>

    <label for="class_id">Class ID:</label>
    <input type="number" name="class_id" placeholder="Class ID" value="<?php echo isset($_POST['class_id']) ? $_POST['class_id'] : ''; ?>"><br>

    <label for="year">Year:</label>
    <input type="number" name="year" placeholder="Year" value="<?php echo isset($_POST['year']) ? $_POST['year'] : ''; ?>"><br>

    <button type="submit" name="filter">Apply Filter</button>
</form>

<!-- Display Total Students -->
<h3>Total Students: <?php echo $total_students['total']; ?></h3>

<!-- Display Gender Counts -->
<h3>Gender Breakdown:</h3>
<p>Male: <?php echo $male_count['male_count']; ?></p>
<p>Female: <?php echo $female_count['female_count']; ?></p>

<!-- Display Students List -->
<h3>Student List:</h3>
<table border="1">
    <thead>
        <tr>
            <th>Admission No</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Class ID</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Display student data
        while ($student = $students->fetch_assoc()) {
            echo "<tr>
                    <td>{$student['adm_no']}</td>
                    <td>{$student['first_name']}</td>
                    <td>{$student['last_name']}</td>
                    <td>{$student['gender']}</td>
                    <td>{$student['email']}</td>
                    <td>{$student['class_id']}</td>
                    <td>{$student['year']}</td>
                    <td>
                         <a href='?edit={$student['adm_no']}'>Edit</a>
                        <a href='?delete={$student['adm_no']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
        }
        ?>
    </tbody>
</table>



<?php
$conn->close();  // Closing the connection after everything is done
?>
