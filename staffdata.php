<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Data</title>
    <link rel="stylesheet" href="staffdata.css"> <!-- Link to CSS -->

    <script>
        function showEditForm(id, name, password, role, shift, wardID, email, contactNumber) {
            document.getElementById('editID').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editPassword').value = password;
            document.getElementById('editRole').value = role;
            document.getElementById('editShift').value = shift;
            document.getElementById('editWardID').value = wardID;
            document.getElementById('editEmail').value = email;
            document.getElementById('editContactNumber').value = contactNumber;

            document.getElementById('edit-form').style.display = 'block'; // Show the edit form
        }

        function hideEditForm() {
            document.getElementById('edit-form').style.display = 'none'; // Hide the edit form
        }
    </script>
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
        <form method="POST" action="staffdata.php">
            <label for="role">Role:</label>
            <input type="text" name="role" id="role">
            <label for="shift">Shift:</label>
            <input type="text" name="shift" id="shift">
            <label for="ward_incharge">Ward Incharge:</label>
            <input type="text" name="ward_incharge" id="ward_incharge">
            <input type="submit" name="filter" value="Filter">
        </form>
    </section>

    <section class="table-section">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Staff ID</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Shift</th>
                    <th>Ward Incharge</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
    $conn = new mysqli("localhost", "root", "", "patientprioritytracking_");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize query variables
    $whereClausesStaff = [];
    $whereClausesDoctor = [];

    // Check if filter fields are set and add to the respective whereClauses arrays
    if (!empty($_POST['role'])) {
        $role = $conn->real_escape_string($_POST['role']);
        $whereClausesStaff[] = "Role = '$role'";
        $whereClausesDoctor[] = "Role = '$role'";
    }

    if (!empty($_POST['shift'])) {
        $shift = $conn->real_escape_string($_POST['shift']);
        $whereClausesStaff[] = "Shift = '$shift'";
        $whereClausesDoctor[] = "Shift = '$shift'";
    }

    if (!empty($_POST['ward_incharge'])) {
        $ward_incharge = $conn->real_escape_string($_POST['ward_incharge']);
        $whereClausesStaff[] = "WardID = '$ward_incharge'";
        $whereClausesDoctor[] = "WardID = '$ward_incharge'";
    }

    // Build the WHERE clauses for each query
    $whereClauseStaff = count($whereClausesStaff) > 0 ? " WHERE " . implode(" AND ", $whereClausesStaff) : "";
    $whereClauseDoctor = count($whereClausesDoctor) > 0 ? " WHERE " . implode(" AND ", $whereClausesDoctor) : "";

    // SQL query to retrieve staff and doctors data with filters applied individually
    $sql = "SELECT Name, StaffID AS ID, Password, Role, Shift, WardID, Email, ContactNumber FROM staff $whereClauseStaff
            UNION ALL
            SELECT Name, DoctorID AS ID, Password, Role, Shift, WardID, Email, ContactNumber FROM doctor $whereClauseDoctor";

    // Retrieve the records based on the query
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result && $result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["Name"]); ?></td>
                <td><?php echo htmlspecialchars($row["ID"]); ?></td>
                <td><?php echo htmlspecialchars($row["Password"]); ?></td>
                <td><?php echo htmlspecialchars($row["Role"]); ?></td>
                <td><?php echo htmlspecialchars($row["Shift"]); ?></td>
                <td><?php echo htmlspecialchars($row["WardID"]); ?></td>
                <td><?php echo htmlspecialchars($row["Email"]); ?></td>
                <td><?php echo htmlspecialchars($row["ContactNumber"]); ?></td>
                <td>
                    <button onclick="showEditForm('<?php echo htmlspecialchars($row['ID']); ?>', '<?php echo addslashes($row['Name']); ?>', '<?php echo addslashes($row['Password']); ?>', '<?php echo addslashes($row['Role']); ?>', '<?php echo addslashes($row['Shift']); ?>', '<?php echo addslashes($row['WardID']); ?>', '<?php echo addslashes($row['Email']); ?>', '<?php echo addslashes($row['ContactNumber']); ?>')">Edit</button>
                    <form method="POST" action="delete_staff.php" style="display:inline;">
                        <input type="hidden" name="StaffID" value="<?php echo htmlspecialchars($row['ID']); ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php }
    } else { ?>
        <tr>
            <td colspan="9">No data found</td>
        </tr>
    <?php }

    $conn->close();
?>

            </tbody>
        </table>
    </section>

    <div id="edit-form" style="display: none;" class="modal">
        <div class="modal-content">
            <h3>Edit Staff</h3>
            <form method="POST" action="update_staff.php">
                <input type="hidden" name="ID" id="editID">
                <label for="editName">Name:</label>
                <input type="text" name="Name" id="editName" required><br>
                <label for="editPassword">Password:</label>
                <input type="text" name="Password" id="editPassword" required><br>
                <label for="editRole">Role:</label>
                <input type="text" name="Role" id="editRole" required><br>
                <label for="editShift">Shift:</label>
                <input type="text" name="Shift" id="editShift" required><br>
                <label for="editWardID">Ward Incharge:</label>
                <input type="text" name="WardID" id="editWardID" required><br>
                <label for="editEmail">Email:</label>
                <input type="email" name="Email" id="editEmail" required><br>
                <label for="editContactNumber">Contact Number:</label>
                <input type="text" name="ContactNumber" id="editContactNumber" required><br>
                <input type="submit" value="Update Staff">
                <button type="button" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>

    <div style="margin: 20px 0; text-align: right;">
        <a href="add_staff.php" class="add-new-button">Add New Staff</a>
    </div>
</body>
</html>
