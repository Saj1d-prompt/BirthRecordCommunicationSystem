<?php 
include "database.php";

  if (isset($_POST['submit'])) {

    $ApplicantName = $_POST['ApplicantName'];

    $birthRegNum = $_POST['birthRegNum'];

    $contact = $_POST['contact'];

    $email = $_POST['email'];

    $reason = $_POST['reason'];

    $message = $_POST['message'];

    $sql = "INSERT INTO `reissueapp_t`(`applicantName`, `birthRegistrationNum`, `contactNum`, `email`, `reason`, `additionalInfo`) VALUES ('$ApplicantName','$birthRegNum','$contact','$email','$reason','$message')";

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
    <title>Reissue Birth Certificate | Birth Record Communication System</title>
  <link rel="stylesheet" href="reissue.css">    
</head>
<body>
    
    <Header class = "header">
        <h1>Birth Record Communication System</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="parent_dashboard.html">Home</a></li>
                <li><a href="#">Vaccination</a></li>
                <li><a href="reissue.php">Certificate Reissue</a></li>
                <li><a href="./patient_user/correction.html">Correction Request</a></li>
                <li><a href="reissue.html">Certificate Reissue</a></li>
                <li><a href="Patient_user/correctionRequest.html">Correction Request</a></li>
                <li><a href="index.html" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </Header>
    <section class="reissueSection">
        <div class="reissueContainer">
            <h2>Birth Certificate Reissue Form</h2>
            <form action="#" method="POST" class="reissueForm" onsubmit="submitForm(event)">

                <label for="Applicant Name">Applicant Name</label>
                <input type="text" name="ApplicantName" id="ApplicantName" required>



                <label for="birthRegNum">Birth Registration Number</label>
                <input type="text" name="birthRegNum" id="birthRegNum" required>

                <label for="contact">Contact Number</label>
                <input type="tel" name="contact" id="contact" required>

                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
         
                <label for="reason">Reason for Reissue</label>
                <select name="reason" id="reason" required>
                    <option value="">--Select Reason--</option>
                    <option value="lost">Lost Certificate</option>
                    <option value="damaged">Damaged Certificate</option>
                    <option value="correction">Correction in Details</option>
                    <option value="other">Other</option>
                </select>

                <label for="message">Additional Information</label>
                <textarea name="message" id="message" rows="4" placeholder="Any additional details..."></textarea>
                
                <button type="submit" name="submit" value="submit">Submit Application</button>
            </form>
        </div>
    </section>  


  
</body>
</html>

