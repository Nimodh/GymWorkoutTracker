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

// Fetch the exercise history summary
$user_id = $_SESSION["id"]; // Assuming you store user id in session
$sql = "SELECT date, COUNT(*) as exercise_count
        FROM history
        WHERE user_id = ?
        GROUP BY date
        ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$history = array();
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise History</title>
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
        <h1>Exercise History</h1>
        
        <?php if (count($history) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Exercise Count</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $day): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($day['date']); ?></td>
                            <td><?php echo htmlspecialchars($day['exercise_count']); ?></td>
                            <td><a href="day_exercise_details.php?date=<?php echo htmlspecialchars($day['date']); ?>">View Details</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No exercise history found.</p>
        <?php endif; ?>

        <p><a href="welcome.php">Back to Welcome Page</a></p>
    </div>
</body>
</html>
