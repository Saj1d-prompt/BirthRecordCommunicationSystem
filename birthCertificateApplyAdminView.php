<?php 

include "database.php";

$sql = "SELECT * FROM newborns";

$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Certificate Application View | Birth Record Communication System</title>
    <link rel="stylesheet" href="birthCertificateApplyAdminView.css">
</head>
<body>
    <header class="navbar">
      <h1>Birth Record Communication System</h1>
      <nav>
        <ul class="nav-links">
          <li><a href="./admin_dashboard.html">Home</a></li>
          <li><a href="#">Birth Records</a></li>
          <li><a href="#">Vaccination Data</a></li>
          <li><a href="#">Correction Requests</a></li>
          <li><a href="#">Reissue Requests</a></li>
          <li><a href="index.html" onclick="logout()">Logout</a></li>
        </ul>
      </nav>
    </header>

    <div class =  "container">
        <h2>Birth Certificate Applications</h2>
        
        <table class="applications-table">
            <thead>
                <tr>
                <th>BRN</th>
                <th>Child Name</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Father's Name</th>
                <th>Mother's Name</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $row['reg_number']; ?></td>
                    <td><?php echo $row['fullName']; ?></td>
                    <td><?php echo $row['dateofBirth']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['fatherName']; ?></td>
                    <td><?php echo $row['motherName']; ?></td>
                    <td><?php echo $row['permanentAddress']; ?></td>
                    <td><?php echo $row['contactNum']; ?></td>
                    <td>
                        <button class="approve-btn">Approve</button>
                        <button class="reject-btn">Reject</button>
                    </td>
                </tr>
                <?php   }
            }
            $conn->close(); 
            ?> 
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Birth Record Monitoring System. All rights reserved.</p>
    </footer>

</body>
</html>