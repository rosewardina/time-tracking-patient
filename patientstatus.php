<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Status</title>
    <link rel="stylesheet" href="patientstatus.css"> <!-- Link to CSS -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="dashboardpatient.html">Home</a></li>
                <li><a href="patientstatus.php">Patient Status</a></li>
                <li><a href="mainlogin.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <section class="search-section">
        <form method="POST" action="patientstatus.php">
            <label for="patient_id">Please enter patient's name:</label>
            <input type="text" id="patient_name" name="patient_name" required>
            <button type="submit" name="Search">Search</button>
        </form>
    </section>

    <section class="table-section">
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Arrival Date</th>
                <th>Patient ID</th>
                <th>Progress</th>
            </tr>
            <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Check if the form has been submitted
                if (isset($_POST['Search']) && !empty($_POST['patient_name'])) { 
                    $patient_name = $_POST['patient_name'];
                    
                    // Prepared statement for partial matching on both first and last names
                    $stmt = $conn->prepare("SELECT * FROM patient WHERE FirstName LIKE ? OR LastName LIKE ?");
                    $searchTerm = "%$patient_name%";
                    $stmt->bind_param("ss", $searchTerm, $searchTerm);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["FirstName"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LastName"]); ?></td>
                                <td><?php echo htmlspecialchars($row["ArrivalDate"]); ?></td>
                                <td><?php echo htmlspecialchars($row["PatientID"]); ?></td>
                                <td><?php echo htmlspecialchars($row["Progress"]); ?></td>
                            </tr>
                        <?php  
                        }
                    } else { ?>
                        <tr>
                            <td colspan="5">No data found</td>
                        </tr>
                    <?php }

                    $stmt->close();
                }
                $conn->close();
            ?>
        </table>
    </section>
</body>
</html>
