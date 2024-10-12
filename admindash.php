<?php
// Start the session
session_start();

// Check if the session variables are set
if (isset($_SESSION['AdminID']) && isset($_SESSION['Name'])) {
    $adminID = $_SESSION['AdminID'];
    $adminName = $_SESSION['Name'];
} else {
    // If session variables are not set, redirect to the login page
    header("Location: admindash.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Health Hospital</title>
    <link rel="stylesheet" href="admindash.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="LOGO PROJECT WPA.png" alt="Hospital Logo" class="logo">
            <h1>UNITY HEALTH HOSPITAL</h1> <br>
        </div>

        <div> 
            <h4>Real-Time Patient Tracking</h4> 
        </div>
        <div>
        <h3>Logged in as: <br>
        <?php
        // Display the logged-in admin's name and AdminID
        echo "Staff Name: " . htmlspecialchars($adminName) . " - Admin ID: " . htmlspecialchars($adminID);
        ?>
        </h3>
        </div>
    </header>

    <nav>
        <a href="admindash.php">Home</a>
        <a href="patientdata.php">Patient Data</a>
        <a href="staffdata.php">Staff Data</a>
        <a href="mainlogin.php">Logout</a>
    </nav>

    <section class="info-section">
        <div>
            <ol>
                <h2 onclick="toggleVisibility('VisionList', this)">Vision</h2>
                <p style="text-align: center;" id="VisionList">
                    To be a leading healthcare provider recognized for excellence in patient care, innovative medical solutions, and a compassionate approach to healing, ensuring a healthier future for our community and beyond.
                </p>

                <h2 onclick="toggleVisibility('MissionList', this)">Mission</h2>
                <ul id="MissionList" style="display: none;"> <!-- Initially hidden -->
                    <li>Deliver comprehensive, high-quality medical care to every patient, with a focus on safety, empathy, and respect.</li>
                    <li>Harness the latest advancements in medical technology and research to improve patient outcomes and healthcare services.</li>
                    <li>Foster a collaborative environment where healthcare professionals are empowered to provide personalized, lifesaving care.</li>
                    <li>Promote community health through education, outreach, and preventative care programs.</li>
                </ul>
            </ol>
        </div>
    </section>

    <script>
        // JavaScript to toggle the visibility of the mission and vision lists
        function toggleVisibility(id, element) {
            var list = document.getElementById(id);
            if (list.style.display === "none") {
                list.style.display = "block";
            } else {
                list.style.display = "none";
            }
        }
    </script>
</body>
</html>
