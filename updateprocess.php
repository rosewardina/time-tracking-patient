<?php
if (isset($_POST['PatientID'])) {

    $progress = $_POST['Progress'];
    $notes = $_POST['notes'];
    $patient_id = $_POST['PatientID']; // Use this variable

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Correct the SQL query (remove the comma before WHERE and use $patient_id)
    $sql = "UPDATE patient SET 
                Progress='$progress',
                notes='$notes'
           
            WHERE PatientID='$patient_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        // Redirect back to patient data page
        header("Location: patientstatusdoc.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
