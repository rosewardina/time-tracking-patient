<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Data</title>
    <link rel="stylesheet" href="patientstatusnurse.css"> <!-- Link to CSS -->
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
                <li><a href="admindash.php">Home</a></li>
                <li><a href="patientdata.php">Patient Data</a></li>
                <li><a href="staffdata.php">Staff Data</a></li>
                <li><a href="mainlogin.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <section class="search-section">
        <form method="POST" action="patientdata.php">
           
            
            <label for="statusColor">Status Color:</label>
            <select name="status_color" id="statusColor">
                <option value="">Select Status Color</option>
                <option value="Red">Red</option>
                <option value="Yellow">Yellow</option>
                <option value="Green">Green</option>
                <!-- Add more colors as needed -->
            </select>

            <label for="Progress">Progress:</label>
            <select name="Progress" id="Progress">
                <option value="">Select Progress</option>
                <option value="Waiting for Treatment">Waiting for Treatment</option>
                <option value="Waiting for Medicine">Waiting for Medicine</option>
                <option value="In Treatment">In Treatment</option>
                <option value="Discharge">Discharge</option>
                <!-- Add more colors as needed -->
            </select>
            
            <input type="submit" name="filter" value="Filter">
        </form>
    </section>

    <section class="table-section">
        <table border="1">
            <tr>
                <th>PatientID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date Of Birth</th>
                <th>Contact Number</th>
                <th>Arrival Date</th>
                <th>Status ID</th>
                <th>Status Color</th>
                <th>Description</th>
                <th>Ward ID</th>
                <th>Progress</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
            <?php
                // Connect to the database
                $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Initialize query variables
                $sql = "SELECT * FROM patient";
                $whereClauses = [];

                // Check if the filter form was submitted
                if (isset($_POST['filter'])) {
                    if (!empty($_POST['Progress'])) {
                        $Progress = $_POST['Progress'];
                        $whereClauses[] = "Progress = '" . $conn->real_escape_string($Progress) . "'";
                    }
                    if (!empty($_POST['status_color'])) {
                        $status_color = $_POST['status_color'];
                        $whereClauses[] = "StatusColor = '" . $conn->real_escape_string($status_color) . "'";
                    }

                    // Append WHERE clauses to the SQL query
                    if (count($whereClauses) > 0) {
                        $sql .= " WHERE " . implode(" AND ", $whereClauses);
                    }
                }

                // Retrieve the records based on the query
                $result = $conn->query($sql);

                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) { 
                        // Ensure status color is lowercase and valid
                        $statusClass = 'status-' . strtolower(htmlspecialchars($row["StatusColor"]));
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["PatientID"]); ?></td>
                            <td><?php echo htmlspecialchars($row["FirstName"]); ?></td>
                            <td><?php echo htmlspecialchars($row["LastName"]); ?></td>
                            <td><?php echo htmlspecialchars($row["DateOfBirth"]); ?></td>
                            <td><?php echo htmlspecialchars($row["ContactNumber"]); ?></td>
                            <td><?php echo htmlspecialchars($row["ArrivalDate"]); ?></td>
                            
                            <td><?php echo htmlspecialchars($row["StatusID"]); ?></td>
                            <td class="<?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($row["StatusColor"]); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row["Description"]); ?></td>
                            <td><?php echo htmlspecialchars($row["WardID"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Progress"]); ?></td>
                            <td><?php echo htmlspecialchars($row["notes"]); ?></td>
                            <td>
                                <!-- Update button: Redirects to the update form with the PatientID -->
                                <a href="updatepatient.php?PatientID=<?php echo htmlspecialchars($row['PatientID']); ?>">Update</a>
                                
                                <!-- Delete button: Submits the delete action via POST to a delete script -->
                                <form method="POST" action="deletepatientadmin.php" style="display:inline;">
                                    <input type="hidden" name="PatientID" value="<?php echo htmlspecialchars($row['PatientID']); ?>">
                                    <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this patient?');">
                                </form>
                            </td>
                        </tr>
                    <?php  
                    }
                } else { ?>
                    <tr>
                        <td colspan="13">No data found</td>
                    </tr>
                <?php }
                $conn->close();
                ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
            ?>
        </table>
    </section>
</body>
</html>
