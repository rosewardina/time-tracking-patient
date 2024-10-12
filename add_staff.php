<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff</title>
    <link rel="stylesheet" href="addstaff.css"> <!-- Optional: Add your CSS -->
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="admindash.php">Home</a></li>
            <li><a href="patientdata.php">Patient Data</a></li>
            <li><a href="staffdata.php">Staff Data</a></li>
            <li><a href="mainlogin.php">Logout</a></li>
        </ul>
    </nav>
</header>

<h1>Add New Staff</h1>

<form method="POST" action="">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="password">Password:</label>
    <input type="text" id="password" name="password" required><br>

    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="Doctor">Doctor</option>
        <option value="Nurse">Nurse</option>
    </select><br>

    <label for="shift">Shift:</label>
    <select id="shift" name="shift" required>
        <option value="Day">Day</option>
        <option value="Night">Night</option>
    </select><br>

    <label for="ward_id">Ward Incharge:</label>
    <select id="ward_id" name="ward_id" required>
        <option value="EMR001">EMR001</option>
        <option value="ICU002">ICU002</option>
        <option value="GRL003">GRL003</option>
        <!-- Add more wards as needed -->
    </select><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="contact_number">Contact Number:</label>
    <input type="text" id="contact_number" name="contact_number" required><br>

    <button type="submit">Save Staff</button>
    <a href="staffdata.php" class="back-button">Back to Staff Data</a>
</form>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a new unique StaffID or DoctorID
function generateID($role, $conn) {
    if ($role === 'Doctor') {
        $table = 'doctor';
        $prefix = 'DR';
    } else {
        $table = 'staff';
        $prefix = 'N';
    }

    // Make sure the correct column name is used
    $idColumn = $role === 'Doctor' ? 'DoctorID' : 'StaffID';

    // Query to find the highest existing ID
    $query = "SELECT MAX(CAST(SUBSTRING($idColumn, 3) AS UNSIGNED)) AS maxID FROM $table";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $maxID = $row['maxID'] ? $row['maxID'] + 1 : 1; // Increment max ID or start from 1
    } else {
        $maxID = 1; // If no ID exists, start with 1
    }

    return $prefix . str_pad($maxID, 3, '0', STR_PAD_LEFT); // Format ID like DR001 or N003
}

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $staff_id = generateID($role, $conn); // Generate ID based on role

    // Retrieve and sanitize form inputs
    $name = $conn->real_escape_string($_POST['name']);
    $password = $conn->real_escape_string($_POST['password']);
    $shift = $conn->real_escape_string($_POST['shift']);
    $ward_id = $conn->real_escape_string($_POST['ward_id']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);

    // Insert data into the respective table
    if ($role === 'Doctor') {
        $sql = "INSERT INTO doctor (DoctorID, Name, Password, Role, Shift, WardID, Email, ContactNumber) 
                VALUES ('$staff_id', '$name', '$password', '$role', '$shift', '$ward_id', '$email', '$contact_number')";
    } else {
        $sql = "INSERT INTO staff (StaffID, Name, Password, Role, Shift, WardID, Email, ContactNumber) 
                VALUES ('$staff_id', '$name', '$password', '$role', '$shift', '$ward_id', '$email', '$contact_number')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "New staff added successfully! Staff ID: " . $staff_id;
        header("Location: staffdata.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

</body>
</html>
