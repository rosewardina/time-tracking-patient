<?php
// updatepatientnurse.php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['PatientID'])) {
    $patientID = $_GET['PatientID'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get patient data
    $sql = "SELECT * FROM patient WHERE PatientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $patientID); // 's' because PatientID is VARCHAR
    $stmt->execute();
    $result = $stmt->get_result();

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #a58f72;
            color: #495057;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #008080;
            padding: 10px 0;
            margin: 0;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: space-around;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            font-weight: bold;
            color: black;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #f0e6cc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            margin-top: 10px;
            display: block;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
    </style>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #a58f72;
            color: #495057;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #008080; 
            padding: 10px 0;
            margin: 0;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: space-around;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            font-weight: bold;
            color: black; 
        }
    </style>
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

<body>
<div class="container">
    <h1>Update Patient Information</h1>

    <!-- Patient Update Form -->
    <form action="update_process.php" method="POST">
        <!-- Ensure the hidden field is correctly populated with the PatientID -->
        <input type="hidden" name="PatientID" value="<?php echo htmlspecialchars($patient['PatientID'] ?? ''); ?>">

        <!-- The rest of the form fields -->
        <label for="FirstName">First Name:</label>
        <input type="text" name="FirstName" id="FirstName" value="<?php echo htmlspecialchars($patient['FirstName'] ?? ''); ?>" required>

        <label for="LastName">Last Name:</label>
        <input type="text" name="LastName" id="LastName" value="<?php echo htmlspecialchars($patient['LastName'] ?? ''); ?>" required>

        <label for="DateOfBirth">Date of Birth:</label>
        <input type="date" name="DateOfBirth" id="DateOfBirth" value="<?php echo htmlspecialchars($patient['DateOfBirth'] ?? ''); ?>" required>

        <label for="ContactNumber">Emergency Contact Number:</label>
        <input type="text" name="ContactNumber" id="ContactNumber" value="<?php echo htmlspecialchars($patient['ContactNumber'] ?? ''); ?>" required>

        <label for="ArrivalDate">Arrival Date:</label>
        <input type="date" name="ArrivalDate" id="ArrivalDate" value="<?php echo htmlspecialchars($patient['ArrivalDate'] ?? ''); ?>" required>

        <label for="Progress">Progress:</label>
        <select name="Progress" id="Progress" required>
            <option value="Waiting for Treatment" <?php echo ($patient['Progress'] == 'Waiting for Treatment') ? 'selected' : ''; ?>>Waiting for Treatment</option>
            <option value="In Treatment" <?php echo ($patient['Progress'] == 'In Treatment') ? 'selected' : ''; ?>>In Treatment</option>
            <option value="Waiting for medicine" <?php echo ($patient['Progress'] == 'Waiting for medicine') ? 'selected' : ''; ?>>Waiting for medicine</option>
            <option value="Discharge" <?php echo ($patient['Progress'] == 'Discharge') ? 'selected' : ''; ?>>Discharge</option>
        </select>

        
        <input type="submit" value="Update">
        <a href="patientstatusnurse.php" class="back-button">Back to Patient Queue</a>
    </form>

</div>
</body>
</html>
