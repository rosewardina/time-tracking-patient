<?php
if (isset($_POST['PatientID'])) {
    // Collect form input values
    $patientID = $_POST['PatientID'];
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $dateOfBirth = $_POST['DateOfBirth'];
    $contactNumber = $_POST['ContactNumber'];
    $arrivalDate = $_POST['ArrivalDate'];
    $progress = $_POST['Progress'];
    $statusID = $_POST['StatusID'];
    $statusColor = $_POST['StatusColor'];
    $description = $_POST['Description'];
    $wardID = $_POST['WardID'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if StatusID exists in the 'status' table before updating
    $statusCheckStmt = $conn->prepare("SELECT StatusID FROM status WHERE StatusID = ?");
    $statusCheckStmt->bind_param("s", $statusID); // Use 's' for string type
    $statusCheckStmt->execute();
    $statusCheckStmt->store_result();

    // Check if StatusID exists
    if ($statusCheckStmt->num_rows == 0) {
        echo "Error: StatusID does not exist in the 'status' table.";
    } else {
        // Debugging information
        echo "Debug Info: StatusID = $statusID, PatientID = $patientID <br>";

        // Prepare the SQL statement for updating the patient record
        $stmt = $conn->prepare(
            "UPDATE patient 
            SET FirstName = ?, LastName = ?, DateOfBirth = ?, ContactNumber = ?, ArrivalDate = ?, Progress = ?, StatusID = ?, StatusColor = ?, Description = ?, WardID = ? 
            WHERE PatientID = ?"
        );

        // Bind parameters (use 's' for strings)
        $stmt->bind_param(
            "sssssssssss", 
            $firstName, $lastName, $dateOfBirth, $contactNumber, $arrivalDate, 
            $progress, $statusID, $statusColor, $description, $wardID, $patientID
        );

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Redirect after successful update
            header("Location: patientdata.php");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the status check statement and the connection
    $statusCheckStmt->close();
    $conn->close();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
