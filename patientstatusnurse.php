<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Status</title>
    <link rel="stylesheet" href="staffdata.css"> <!-- Link to CSS -->
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

<section class="search-section">
    <form method="POST" action="patientstatusnurse.php">
        <label for="patient_id">Please enter PatientID:</label>
        <input type="text" id="patient_id" name="patient_id" required>
        <button type="submit" name="Search">Search</button>
    </form>
</section>

<section class="table-section">
    <table>
        <tr>
            <th>Patient ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Arrival Date</th>
            <th>Emergency Contact Number</th>
            <th>Ward ID</th>
            <th>Status Color</th>
            <th>Description</th>
            <th>Progress</th>
            <th>Notes</th>
            <th>Action</th>
        </tr>

        <?php
        $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Initialize query variables
        $sql = "SELECT * FROM patient";

        // Check if filter fields are set
        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {
            // Sanitize the input
            $patient_id = $conn->real_escape_string($_POST['patient_id']);
            // Add a WHERE clause to the SQL query
            $sql .= " WHERE PatientID = '$patient_id'";
        }

        // Retrieve the records based on the query
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result && $result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["PatientID"]); ?></td>
                    <td><?php echo htmlspecialchars($row["FirstName"]); ?></td>
                    <td><?php echo htmlspecialchars($row["LastName"]); ?></td>
                    <td><?php echo htmlspecialchars($row["ArrivalDate"]); ?></td>
                    <td><?php echo htmlspecialchars($row["ContactNumber"]); ?></td>
                    <td><?php echo htmlspecialchars($row["WardID"]); ?></td>
                    <td><?php echo htmlspecialchars($row["StatusColor"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Description"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Progress"]); ?></td>
                    <td><?php echo htmlspecialchars($row["notes"]); ?></td>
                    <td>
                        <a href="updatepatientnurse.php?PatientID=<?php echo htmlspecialchars($row['PatientID']); ?>">
                            <button type="button">Update</button>
                        </a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="11">No data found</td>
            </tr>
        <?php }

        // Close the database connection
        $conn->close();
        ?>
    </table>
    
    <div style="margin-top: 20px;">
        <div style="margin: 20px 0; text-align: right;">
            <a href="addpatient.php">
                <button type="button">Add New Patient</button>
            </a>
        </div>
    </div>
</section>
</body>
</html>
