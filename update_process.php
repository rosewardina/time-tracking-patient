<?php
// update_process.php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture POST data
    $patientID = $_POST['PatientID'];
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $dateOfBirth = $_POST['DateOfBirth'];
    $contactNumber = $_POST['ContactNumber'];
    $arrivalDate = $_POST['ArrivalDate'];
    $progress = $_POST['Progress'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update patient data query
    $sql = "UPDATE patient SET 
                FirstName = ?, 
                LastName = ?, 
                DateOfBirth = ?, 
                ContactNumber = ?, 
                ArrivalDate = ?, 
                Progress = ? 
            WHERE PatientID = ?";

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $firstName, $lastName, $dateOfBirth, $contactNumber, $arrivalDate, $progress, $patientID);

    if ($stmt->execute()) {
        // Redirect back to patient status page after successful update
        header("Location: patientstatusnurse.php?update=success");
        exit(); // Stop script execution after header redirect
    } else {
        // Display error
        echo "Error updating patient information: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
