<?php
header("Content-Type: application/json");

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your DB password
$dbname = "birthrecordsystem"; // Updated DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit();
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON"]);
    exit();
}

// Extract fields sent from ESP32
$weight = isset($data['weight']) ? floatval($data['weight']) : 0;
$gestation = isset($data['gestation']) ? $conn->real_escape_string($data['gestation']) : "";
$gender = isset($data['gender']) ? $conn->real_escape_string($data['gender']) : "";
$location = isset($data['location']) ? $conn->real_escape_string($data['location']) : "";

// Generate a unique 16-digit birth registration number
do {
    $reg_number = mt_rand(1000000000000, 9999999999999); // 16-digit number
    $result = $conn->query("SELECT COUNT(*) as cnt FROM newborn_t WHERE birthRegistrationNum='$reg_number'");
    $row = $result->fetch_assoc();
} while ($row['cnt'] > 0);

// Insert device data into newborn_t; leave other fields NULL for later
$stmt = $conn->prepare("
    INSERT INTO newborn_t (
        birthRegistrationNum, weight, gestation, gender, location
    ) VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("idsss", $reg_number, $weight, $gestation, $gender, $location);
$stmt->execute();
$stmt->close();
$conn->close();

// Return JSON response with registration number
echo json_encode([
    "status" => "success",
    "reg_number" => $reg_number
]);
?>
