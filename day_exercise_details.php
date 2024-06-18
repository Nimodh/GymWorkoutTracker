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

// Fetch the detailed exercise history for the selected date
$user_id = $_SESSION["id"]; // Assuming you store user id in session
$date = isset($_GET['date']) ? $_GET['date'] : '';
$sql = "SELECT e.name, h.reps, h.sets, h.weight
        FROM history h
        JOIN exercises e ON h.exercise_id = e.id
        WHERE h.user_id = ? AND h.date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$result = $stmt->get_result();
$exercises = array();
while ($row = $result->fetch_assoc()) {
    $exercises[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Details for <?php echo htmlspecialchars($date); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Exercise Details for <?php echo htmlspecialchars($date); ?></h1>
        
        <?php if (count($exercises) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Exercise</th>
                        <th>Reps</th>
                        <th>Sets</th>
                        <th>Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exercises as $exercise): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($exercise['name']); ?></td>
                            <td><?php echo htmlspecialchars($exercise['reps']); ?></td>
                            <td><?php echo htmlspecialchars($exercise['sets']); ?></td>
                            <td><?php echo htmlspecialchars($exercise['weight']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No exercises found for this date.</p>
        <?php endif; ?>

        <p><a href="history.php">Back to History</a></p>
    </div>
</body>
</html>
