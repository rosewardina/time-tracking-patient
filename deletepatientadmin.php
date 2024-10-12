<?php
if (isset($_POST['PatientID'])) {
    $patientID = $_POST['PatientID'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete records from triage first
    $deleteTriageSql = "DELETE FROM triage WHERE PatientID='$patientID'";
    if ($conn->query($deleteTriageSql) !== TRUE) {
        echo "Error deleting triage records: " . $conn->error;
        $conn->close();
        exit();
    }

    // Delete records from emergencycontact
    $deleteEmergencyContactSql = "DELETE FROM emergencycontact WHERE PatientID='$patientID'";
    if ($conn->query($deleteEmergencyContactSql) !== TRUE) {
        echo "Error deleting emergency contacts: " . $conn->error;
        $conn->close();
        exit();
    }

    // Then delete the patient
    $sql = "DELETE FROM patient WHERE PatientID='$patientID'";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
        // Redirect back to patient data page
        header("Location: patientdata.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}
?>
