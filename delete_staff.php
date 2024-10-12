<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if StaffID is set and not empty
if (isset($_POST['StaffID']) && !empty($_POST['StaffID'])) {
    // Sanitize the input
    $staffID = $conn->real_escape_string($_POST['StaffID']);

    // Determine the role by querying the staff and doctor tables
    $roleQuery = "SELECT Role FROM staff WHERE StaffID = '$staffID'";
    $roleResult = $conn->query($roleQuery);

    if ($roleResult->num_rows > 0) {
        // If found in the staff table
        $row = $roleResult->fetch_assoc();
        $role = $row['Role'];

        // Delete related records in the triage table if the role is Nurse
        if ($role === 'Nurse') {
            $deleteTriageSql = "DELETE FROM triage WHERE NurseID = '$staffID'";
            $conn->query($deleteTriageSql); // Execute the delete for nurses
        }

        // Delete the staff member
        $sql = "DELETE FROM staff WHERE StaffID = '$staffID'";
    } else {
        // If not found in staff, check in the doctor table
        $roleQuery = "SELECT Role FROM doctor WHERE DoctorID = '$staffID'";
        $roleResult = $conn->query($roleQuery);

        if ($roleResult->num_rows > 0) {
            // If found in the doctor table
            $row = $roleResult->fetch_assoc();
            $role = $row['Role'];

            // Delete the doctor member
            $sql = "DELETE FROM doctor WHERE DoctorID = '$staffID'";
        } else {
            echo "No staff member found with that ID.";
            exit();
        }
    }

    // Execute the delete query
    if ($conn->query($sql) === TRUE) {
        // Successfully deleted, redirect to staff data page
        header("Location: staffdata.php?message=success");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No StaffID provided.";
}

// Close the connection
$conn->close();
?>
