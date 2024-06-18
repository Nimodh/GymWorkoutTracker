<?php
session_start();

// Check if the user is logged in, if not then redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymprogresstracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed"]);
    exit;
}

// Fetch max weight and date for the given exercise and user
$user_id = $_SESSION["id"]; // Assuming you store user id in session
$exercise_id = $_GET['exercise_id'];

$sql = "SELECT MAX(weight) AS max_weight, date 
        FROM history 
        WHERE user_id = ? AND exercise_id = ?
        GROUP BY date 
        ORDER BY max_weight DESC 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $exercise_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["success" => true, "max_weight" => $row['max_weight'], "date" => $row['date']]);
} else {
    echo json_encode(["success" => false, "message" => "No data found"]);
}

$stmt->close();
$conn->close();
?>
