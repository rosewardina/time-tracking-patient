<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the POST request
$id = $conn->real_escape_string($_POST['ID']);
$name = $conn->real_escape_string($_POST['Name']);
$password = $conn->real_escape_string($_POST['Password']);
$role = $conn->real_escape_string($_POST['Role']);
$shift = $conn->real_escape_string($_POST['Shift']);
$wardID = $conn->real_escape_string($_POST['WardID']);
$email = $conn->real_escape_string($_POST['Email']);
$contactNumber = $conn->real_escape_string($_POST['ContactNumber']);

// Debug: Log all form data
error_log("Received form data: ID=$id, Name=$name, Role=$role, Shift=$shift, WardID=$wardID, Email=$email, ContactNumber=$contactNumber");

// Determine the table and ID column to update
if (strpos($id, 'N') === 0) { // Nurse
    $table = 'staff';
    $idColumn = 'StaffID';
} else if (strpos($id, 'DR') === 0) { // Doctor
    $table = 'doctor';
    $idColumn = 'DoctorID';
} else {
    die("Invalid ID format");
}

// Debug: Log the table and ID column to ensure the correct table is selected
error_log("Updating table: $table using column: $idColumn");

// Prepare the SQL query to update both staff and doctors
$sql = "UPDATE $table SET 
        Name='$name', 
        Password='$password', 
        Role='$role', 
        Shift='$shift', 
        WardID='$wardID', 
        Email='$email', 
        ContactNumber='$contactNumber' 
        WHERE $idColumn='$id'";

// Debug: Print the exact SQL query
error_log("Executing SQL query: $sql");

// Execute the query
if ($conn->query($sql) === TRUE) {
    // Success: Redirect back to the staffdata page with a success message
    header("Location: staffdata.php?update=success");
    exit();
} else {
    // Error: Log the SQL error for debugging
    error_log("Error updating record: " . $conn->error);
    echo "Error updating record: " . $conn->error;
}

$conn->close();

?>
