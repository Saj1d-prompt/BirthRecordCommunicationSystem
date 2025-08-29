<?php
include "database.php";

if (isset($_POST['submit'])) {
    $brn = $_POST['brn'];
    $child_name = $_POST['child_name'];
    $gender = $_POST['gender'];
    $place_of_birth = $_POST['place_of_birth'];
    $father_name = $_POST['father_name'];
    $mother_name = $_POST['mother_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $sql = "UPDATE `newborns` SET 
            `fullName` = '$child_name',
            `fatherName` = '$father_name',
            `motherName` = '$mother_name',
            `permanentAddress` = '$address',
            `contactNum` = '$contact'
        WHERE `reg_number` = '$brn'";


    $result = $conn->query($sql);

    if ($result == TRUE) {

      echo "<script>console.log('New record created successfully!');</script>";

    }else{

      echo "Error:". $sql . "<br>". $conn->error;

    } 

    $conn->close();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Registration Application | Birth Record Communication System</title>
    <link rel="stylesheet" href="birthCertificateApply.css">
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
                <li><a href="./Patient_user/correction.html">Correction Request</a></li>
                <li><a href="#">Application History</a></li>
                <li><a href="index.html" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </Header>
    <main class="form-container">
        <h2>Birth Certificate Application</h2>
        <form action="#" method="post">
            
            <label for="brn">Birth Registration Number</label>
            <input type="text" id="brn" name="brn" placeholder="Enter Birth Registration Number" required>

            <label for="child_name">Child's Full Name</label>
            <input type="text" id="child_name" name="child_name" placeholder="Enter child's name" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="">Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="place_of_birth">Place of Birth</label>
            <input type="text" id="place_of_birth" name="place_of_birth" placeholder="Enter place of birth">

            <label for="father_name">Father's Name</label>
            <input type="text" id="father_name" name="father_name" required>

            <label for="mother_name">Mother's Name</label>
            <input type="text" id="mother_name" name="mother_name" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" required></textarea>

            <label for="contact">Contact Number</label>
            <input type="tel" id="contact" name="contact" required>

            <button type="submit" class="submit-btn" value="submit" name = "submit">Submit Application</button>
        </form>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Birth Record Monitoring System. All rights reserved.</p>
    </footer>
</body>
</html>