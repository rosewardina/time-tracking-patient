<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patient</title>
    <link rel="stylesheet" href="addpatient.css"> <!-- Optional: Add your CSS -->
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="checklogindb.php">Home</a></li>
            <li><a href="patientstatusnurse.php">Patient Queue</a></li>
            <li><a href="mainlogin.php">Logout</a></li>
        </ul>
    </nav>
</header>

<h1>Add New Patient</h1>

<form method="POST" action="addpatient.php">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required><br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required><br>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" required><br>

    <label for="arrival_date">Arrival Date:</label>
    <input type="date" id="arrival_date" name="arrival_date" required><br>

    <label for="contact_number">Contact Number:</label>
    <input type="text" id="contact_number" name="contact_number" required><br>

    <label for="Relationship">Relation with Patient:</label>
    <input type="text" id="Relationship" name="Relationship" required><br>

    <label for="ward_id">Ward:</label>
    <select id="ward_id" name="ward_id" required>
        <option value="EMR001">Emergency</option>
        <option value="GRL003">General Inpatient Unit</option>
        <option value="ICU002">ICU</option>
    </select><br>

    <label for="status_color">Status Color:</label>
    <select id="status_color" name="status_color" required>
        <option value="Red">Red</option>
        <option value="Yellow">Yellow</option>
        <option value="Green">Green</option>
    </select><br>

    <label for="status_id">Status ID:</label>
    <select id="status_id" name="status_id" required>
        <option value="R001">R001</option>
        <option value="Y001">Y002</option>
        <option value="G003">G003</option>
    </select><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea><br>

    <label for="progress">Progress:</label>
    <select name="Progress" id="progress" required>
        <option value="Waiting for Treatment">Waiting for Treatment</option>
        <option value="In Treatment">In Treatment</option>
        <option value="Complete Treatment">Complete Treatment</option>
        <option value="Set For Next Treatment">Set For Next Treatment</option>
    </select>

    <button type="submit">Save Patient</button>
    <a href="patientstatusnurse.php" class="back-button">Back to Patient Queue</a>
</form>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a new unique PatientID
function generatePatientID($conn) {
    // Query to find the highest existing PatientID
    $query = "SELECT PatientID FROM patient ORDER BY PatientID DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['PatientID'];

        // Extract the numeric part from the last PatientID and increment it
        $numeric_part = (int)filter_var($last_id, FILTER_SANITIZE_NUMBER_INT);
        $new_id = 'P' . str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT);  // New ID like P001, P002, etc.
    } else {
        // If no record exists, start with P001
        $new_id = 'P001';
    }

    return $new_id;
}

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Generate a new unique PatientID
    $patient_id = generatePatientID($conn);

    // Retrieve and sanitize form inputs
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $arrival_date = $conn->real_escape_string($_POST['arrival_date']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $ward_id = $conn->real_escape_string($_POST['ward_id']);
    $status_color = $conn->real_escape_string($_POST['status_color']);
    $status_id = $conn->real_escape_string($_POST['status_id']);
    $description = $conn->real_escape_string($_POST['description']);
    $progress = $conn->real_escape_string($_POST['Progress']); // Changed from 'progress' to 'Progress'

    // Insert data into the patient table
    $sql = "INSERT INTO patient (PatientID, FirstName, LastName, DateOfBirth, ArrivalDate, ContactNumber, WardID, StatusColor, StatusID, Description, Progress) 
            VALUES ('$patient_id', '$first_name', '$last_name', '$dob', '$arrival_date', '$contact_number', '$ward_id', '$status_color', '$status_id', '$description', '$progress')";

    if ($conn->query($sql) === TRUE) {
        // Using header() before any output
        header("Location: patientstatusnurse.php?msg=New patient added successfully! Patient ID: " . $patient_id);
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
