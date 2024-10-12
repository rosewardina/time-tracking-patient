<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if PatientID is set
if (isset($_GET['PatientID'])) {
    $patientID = $_GET['PatientID'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM patient WHERE PatientID = ?");
    $stmt->bind_param("s", $patientID); // "s" means string type
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch patient data
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "No patient found!";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Patient ID not provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Information</title>
    <link rel="stylesheet" href="updatepatient.css">
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

<div class="container">
    <h1>Update Patient Information</h1>

    <form action="savepatient.php" method="POST">
        <!-- Hidden field for PatientID -->
        <input type="hidden" name="PatientID" value="<?php echo htmlspecialchars($patient['PatientID'] ?? ''); ?>">

        <!-- First Name Field -->
        <label for="FirstName">First Name:</label>
        <input type="text" name="FirstName" id="FirstName" value="<?php echo htmlspecialchars($patient['FirstName'] ?? ''); ?>" required>

        <!-- Last Name Field -->
        <label for="LastName">Last Name:</label>
        <input type="text" name="LastName" id="LastName" value="<?php echo htmlspecialchars($patient['LastName'] ?? ''); ?>" required>

        <!-- Date of Birth Field -->
        <label for="DateOfBirth">Date of Birth:</label>
        <input type="date" name="DateOfBirth" id="DateOfBirth" value="<?php echo htmlspecialchars($patient['DateOfBirth'] ?? ''); ?>" required>

        <!-- Contact Number Field -->
        <label for="ContactNumber">Contact Number:</label>
        <input type="text" name="ContactNumber" id="ContactNumber" value="<?php echo htmlspecialchars($patient['ContactNumber'] ?? ''); ?>" required>

       
        <!-- Arrival Date Field -->
        <label for="ArrivalDate">Arrival Date:</label>
        <input type="date" name="ArrivalDate" id="ArrivalDate" value="<?php echo htmlspecialchars($patient['ArrivalDate'] ?? ''); ?>" required>

        

        <!-- Status ID Dropdown -->
        <label for="StatusID">Status ID:</label>
        <select name="StatusID" id="status_id" required>
            <option value="R001" <?php echo ($patient['StatusID'] == 'R001') ? 'selected' : ''; ?>>R001</option>
            <option value="Y002" <?php echo ($patient['StatusID'] == 'Y002') ? 'selected' : ''; ?>>Y002</option>
            <option value="G003" <?php echo ($patient['StatusID'] == 'G003') ? 'selected' : ''; ?>>G003</option>
        </select>

        <!-- Description Field -->
        <label for="Description">Description:</label>
        <textarea name="Description" id="Description" rows="4" required><?php echo htmlspecialchars($patient['Description'] ?? ''); ?></textarea>

        <!-- Progress Dropdown -->
        <label for="Progress">Progress:</label>
        <select name="Progress" id="Progress" required>
            <option value="Waiting for Treatment" <?php echo ($patient['Progress'] == 'Waiting for Treatment') ? 'selected' : ''; ?>>Waiting for Treatment</option>
            <option value="In Treatment" <?php echo ($patient['Progress'] == 'In Treatment') ? 'selected' : ''; ?>>In Treatment</option>
            <option value="Complete Treatment" <?php echo ($patient['Progress'] == 'Complete Treatment') ? 'selected' : ''; ?>>Complete Treatment</option>
        </select>
        
        <!-- Ward ID Dropdown -->
        <label for="WardID">Ward ID:</label>
        <select name="WardID" id="ward_id" required>
            <option value="EMR001" <?php echo ($patient['WardID'] == 'EMR001') ? 'selected' : ''; ?>>EMR001</option>
            <option value="ICU002" <?php echo ($patient['WardID'] == 'ICU002') ? 'selected' : ''; ?>>ICU002</option>
            <option value="GRL003" <?php echo ($patient['WardID'] == 'GRL003') ? 'selected' : ''; ?>>GRL003</option>
        </select>

        <!-- Status Color Dropdown -->
        <label for="StatusColor">Status Color:</label>
        <select name="StatusColor" id="statusColor" required>
            <option value="Red" <?php echo ($patient['StatusColor'] == 'Red') ? 'selected' : ''; ?>>Red</option>
            <option value="Yellow" <?php echo ($patient['StatusColor'] == 'Yellow') ? 'selected' : ''; ?>>Yellow</option>
            <option value="Green" <?php echo ($patient['StatusColor'] == 'Green') ? 'selected' : ''; ?>>Green</option>
        </select>

        <input type="submit" value="Update">
        <a href="patientdata.php" class="back-button">Back to Patient Data</a>
    </form>
</div>
</body>
</html>
