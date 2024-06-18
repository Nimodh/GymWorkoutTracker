<?php
session_start();

// Check if the user is logged in, if not then redirect them to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymprogresstracker"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitize_input($conn, $data) {
    return htmlspecialchars(mysqli_real_escape_string($conn, $data));
}

// Retrieve form data
$user_id = $_SESSION["id"]; // Assuming you store user id in session
$date = date("Y-m-d"); // Current date
$exercise_id = sanitize_input($conn, $_POST['exercise']); // Exercise ID
$reps = sanitize_input($conn, $_POST['reps']); // Reps
$sets = sanitize_input($conn, $_POST['sets']); // Sets
$weight = isset($_POST['weight']) ? sanitize_input($conn, $_POST['weight']) : null; // Weight (optional)

// Prepare SQL statement to insert data into history table
$sql = "INSERT INTO history (user_id, date, exercise_id, reps, sets, weight)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isiiii", $user_id, $date, $exercise_id, $reps, $sets, $weight);
$stmt->execute();

// Check if the insertion was successful
if ($stmt->affected_rows > 0) {
    // Redirect back to the start_new_day.php page or any other page
    header("Location: start_new_day.php");
    exit();
} else {
    // Handle error if insertion fails
    echo "Error adding history.";
}

$stmt->close();
$conn->close();
?>
