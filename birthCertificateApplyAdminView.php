<?php 

include "database.php";

$sql = "SELECT * FROM newborns";

$result = $conn->query($sql);

if (isset($_POST['reg_number']) && isset($_POST['status'])) {
    $reg_number = $_POST['reg_number'];
    $status = $_POST['status'];

    $update_sql = "UPDATE newborns SET status='$status' WHERE reg_number='$reg_number'";
    $conn->query($update_sql);
}

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
          <li><a href="birthCertificateApplyAdminView.php">Birth Records</a></li>
          <li><a href="#">Vaccination Data</a></li>
          <li><a href="correctionViewAdmin.html">Correction Requests</a></li>
          <li><a href="reissueAdminView.php">Reissue Requests</a></li>
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
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { 
                    $rowStatus = isset($row['status']) && $row['status'] !== '' ? $row['status'] : 'pending';
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
                        <?php if ($rowStatus === 'pending') { ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="reg_number" value="<?php echo $row['reg_number']; ?>">
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="approve-btn">Approve</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="reg_number" value="<?php echo $row['reg_number']; ?>">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="reject-btn">Reject</button>
                            </form>
                        <?php } else { ?>
                            <span class="status-<?php echo $rowStatus; ?>">
                                <?= ucfirst($rowStatus); ?>
                            </span>
                        <?php } ?>
                    </td>
                </tr>
            <?php 
                } 
            } ?>
            </tbody>

        </table>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Birth Record Monitoring System. All rights reserved.</p>
    </footer>

</body>
</html>