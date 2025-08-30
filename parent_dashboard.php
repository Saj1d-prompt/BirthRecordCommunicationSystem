<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

include "database.php";
$userID = $_SESSION['userID'];

$sql = "SELECT * FROM newborns WHERE reg_number = $userID";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard | Birth Record Communication System</title>
    <link rel="stylesheet" href="parent_dashboard.css">
</head>
<body>
    <Header class = "header">
        <h1>Birth Record Communication System</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="parent_dashboard.html">Home</a></li>
                <li><a href="birthRegistrationApply.html">Birth Registration Application</a></li>
                <li><a href="#">Vaccination</a></li>
                <li><a href="#">Certificate Reissue</a></li>
                <li><a href="#">Correction Request</a></li>
                <li><a href="#">Application History</a></li>
                <li><a href="index.html" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </Header>

    <section class="pDashboardSection">
        <div class = "childInfo">
            <h2>Newborn Information</h2>
            <p><strong>Registration No:</strong> <?php echo $row['reg_number']; ?></p>
            <p><strong>Gender:</strong> <?php echo $row['gender']; ?></p>
            <p><strong>Weight:</strong> <?php echo $row['weight']; ?></p>
            <p><strong>Gestation:</strong> <?php echo $row['gestation']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
        </div>

        <div class = "pFeatures">
            <a href="#" class="features"> Vaccination Schedule</a>
            <a href="#" class="features"> Request Certificate Reissue</a>
            <a href="#" class="features"> Submit Correction Request</a>
        </div>

        <div class="notification">
            <h3>Notification</h3>
            <p>Your child is due for <strong>DPT1</strong> on <strong>August 10</strong>. 
                Please visit your nearest health center or Vaccination Center.</p>
        </div>

        <div class="correctionStatus">
            <h3>Correction Request Status</h3>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Field</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>REQ-002</td>
                        <td>Date of Birth</td>
                        <td>July 28, 2025</td>
                        <td><span class="status_pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>REQ-001</td>
                        <td>Weight</td>
                        <td>July 10, 2025</td>
                        <td><span class="status_approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td>REQ-003</td>
                        <td>Gender</td>
                        <td>July 20, 2025</td>
                        <td><span class="status_rejected">Rejected</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <footer class="footer">
        <p>&copy; 2025 Birth Record Monitoring System. All rights reserved.</p>
    </footer>

</body>
</html>