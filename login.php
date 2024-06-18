<?php
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

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Debugging output
        echo "Entered Username: " . $user . "<br>";
        echo "Entered Password: " . $pass . "<br>";
        echo "Stored Hashed Password: " . $hashed_password . "<br>";

        if (password_verify($pass, $hashed_password)) {
            // Password is correct, start a new session
            session_start();

            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;

            // Redirect user to welcome page
            header("location: welcome.php");
        } else {
            // Display an error message if password is not valid
            echo "The password you entered was not valid.";
        }
    } else {
        // Display an error message if username doesn't exist
        echo "No account found with that username.";
    }

    $stmt->close();
}


$conn->close();
?>
