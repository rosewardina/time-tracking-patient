<?php
session_start(); // Start the session at the top of the file

// Initialize error flag
$error = '';
include('dbconnect.php');  // Ensure dbconnect.php handles the database connection

// PHP code to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set
    if (isset($_POST['DoctorID']) && isset($_POST['Password'])) {
        // Retrieve the form data
        $myusername1 = $_POST['DoctorID'];
        $mypassword2 = $_POST['Password'];

        // Prepare the SQL query to prevent SQL injection
        $stmt = $conn->prepare("SELECT Name, Role, Shift, WardID FROM doctor WHERE DoctorID = ? AND Password = ?");
        $stmt->bind_param("ss", $myusername1, $mypassword2);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any results are returned
        if ($result->num_rows > 0) {
            // Output data for each row and set session variables
            while ($row = $result->fetch_assoc()) {
                $_SESSION['doctor_name'] = $row["Name"];
                $_SESSION['doctor_role'] = $row["Role"];
                $_SESSION['doctor_shift'] = $row["Shift"];
                $_SESSION['doctor_ward'] = $row["WardID"];
            }
            header("Location: doctordash.php"); // Redirect to the dashboard
            exit();
        } else {
            // Set error message if credentials are wrong
            $error = 'Invalid StaffID or Password.';
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Please enter both Staff ID and Password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicon.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper">
        <form method="POST" action="">
            <h1>Login</h1>
            <div class="input-box"> 
                <input type="text" name="DoctorID" placeholder="Doctor ID" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="password" name="Password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div>
                <a href="mainlogin.php">Back to main login page</a>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>
        
        <div id="error-message" style="color: red;">
            <?php if (!empty($error)) { echo htmlspecialchars($error); } ?>
        </div>
    </div>
</body>
</html>
