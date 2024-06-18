<?php
// Start session
session_start();

// Replace these with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymprogresstracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Check if passwords match
    if ($pass != $confirm_pass) {
        echo "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "This username is already taken.";
        } else {
            // Hash the password
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $user, $hashed_password);

            if ($stmt->execute()) {
                echo "Registration successful. You can now <a href='login.html'>login</a>.";
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>
