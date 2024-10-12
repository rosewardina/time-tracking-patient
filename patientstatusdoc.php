<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Status</title>
    <link rel="stylesheet" href="staffdata.css"> <!-- Link to CSS -->
    <style>
        .status-red {
            color: red;
        }

        .status-yellow {
            color: yellow;
        }

        .status-green {
            color: green;
        }

        /* Add more colors as needed */
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


    <section class="search-section">
        <form method="POST" action="patientstatusdoc.php">
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
                <th>Contact Number</th>
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
    $whereClauses = [];
    $sql = "SELECT PatientID, FirstName, LastName, ArrivalDate, ContactNumber, WardID, StatusColor, Description, Progress, notes FROM patient";

    // Check if filter fields are set and add to the whereClauses array
    if (!empty($_POST['patient_id'])) {
        // Assign the POST value to $patient_id
        $patient_id = $conn->real_escape_string($_POST['patient_id']);
        // Use $patient_id in the WHERE clause
        $whereClauses[] = "PatientID = '$patient_id'";
    }

    // If there are any conditions, append them to the SQL query
    if (count($whereClauses) > 0) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    // Retrieve the records based on the query
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row["PatientID"]; ?></td>
                <td><?php echo $row["FirstName"]; ?></td>
                <td><?php echo $row["LastName"]; ?></td>
                <td><?php echo $row["ArrivalDate"]; ?></td>
                <td><?php echo $row["ContactNumber"]; ?></td>
                <td><?php echo $row["WardID"]; ?></td>
                <td><?php echo $row["StatusColor"]; ?></td>
                <td><?php echo $row["Description"]; ?></td>
                <td><?php echo $row["Progress"]; ?></td>
                <td><?php echo $row["notes"]; ?></td>
                <td><a href="updatepatientdoc.php?PatientID=<?php echo $row['PatientID']; ?>">Update</a></td>


            </tr>
        <?php }
    } else { ?>
        <tr>
            <td colspan="11">No data found</td>
        </tr>
    <?php }
    
    $conn->close();
?>

        </table>

    </section>
</body>
</html>
