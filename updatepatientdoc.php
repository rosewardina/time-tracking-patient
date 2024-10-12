<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientID = $_POST['PatientID'];
    $progress = $_POST['Progress'];
    $notes = $_POST['Notes'];

    // Update the patient record with progress
    $sql = "UPDATE patient SET notes=? ,Progress = ? WHERE PatientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $notes,$progress, $patientID); // Bind the progress and patient ID

    if ($stmt->execute()) {
        echo "Patient updated successfully!";
    } else {
        echo "Error updating patient: " . $conn->error;
    }

    $stmt->close();
} else {
    // If no POST request, fetch the patient data for display
    if (isset($_GET['PatientID'])) {
        $patientID = $_GET['PatientID'];
        
        // Get patient data
        $sql = "SELECT * FROM patient WHERE PatientID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $patientID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $patient = $result->fetch_assoc();
        } else {
            echo "No patient found!";
            exit;
        }
        
        $stmt->close();
    } else {
        echo "Patient ID not provided!";
        exit;
    }
}

$conn->close();
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
        textarea,
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

        .back-button:hover {
            background-color: #218838;
        }

        #colorPreview {
            display: inline-block;
            padding: 5px;
            border-radius: 4px;
            color: #fff;
            margin-top: 5px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="doctordash.php">Home</a></li>
            <li><a href="patientstatusdoc.php">Patient Queue</a></li>
         
            <li><a href="mainlogin.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h1>Update Patient Information</h1>

    <form action="updateprocess.php" method="POST">
    <input type="hidden" name="PatientID" value="<?php echo htmlspecialchars($patient['PatientID']); ?>">

    
    


    <label for="notes">Notes:</label>
        <select name="notes" id="notes" required>
            <option value="Need a futher surgical appoinment" <?php echo ($patient['notes'] == 'Need a futher surgical appoinment') ? 'selected' : ''; ?>>Need a futher surgical appoinment</option>
            <option value="Need to monitor the effects after treatment" <?php echo ($patient['notes'] == 'Need to monitor the effects after treatment') ? 'selected' : ''; ?>>Need to monitor the effects after treatment</option>
            <option value="Follow-up with further treatment with a specialist" <?php echo ($patient['notes'] == 'Follow-up with further treatment with a specialist') ? 'selected' : ''; ?>>Follow-up with further treatment with a specialist</option>
            <option value="Follow up with hospital-based clinics" <?php echo ($patient['notes'] == 'Follow up with hospital-based clinics') ? 'selected' : ''; ?>>Follow up with hospital-based clinics</option>
            <option value="Waiting for available body storage" <?php echo ($patient['notes'] == 'Waiting for available body storage') ? 'selected' : ''; ?>>Waiting for available body storage</option>
            <option value="Waiting for lab result" <?php echo ($patient['notes'] == 'Waiting for lab result') ? 'selected' : ''; ?>>Waiting for lab result</option>
            <option value="Waiting for bed" <?php echo ($patient['notes'] == 'Waiting for bed') ? 'selected' : ''; ?>>Waiting for bed</option>
            <option value="-" <?php echo ($patient['notes'] == '-') ? 'selected' : ''; ?>>-</option>
        </select>

        

    
    
    <input type="submit" value="Update">
    <a href="patientstatusdoc.php" class="back-button">Back to Patient Queue</a>
</form>

</div>

</body>
</html>
