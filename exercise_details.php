<?php
session_start();

// Database connection parameters
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

// Fetch exercise details based on the exercise name from URL parameter
$exercise_name = $_GET['name'];
$sql = "SELECT * FROM exercises WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $exercise_name);
$stmt->execute();
$result = $stmt->get_result();

// Check if exercise exists
if ($result->num_rows > 0) {
    $exercise = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($exercise['name']); ?> Details</title>
    <style>
        /* Basic styling for demonstration purposes */
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
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .exercise-details {
            margin-bottom: 20px;
        }
        .exercise-details h3 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .exercise-details p {
            margin-bottom: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($exercise['name']); ?> Details</h2>

        <div class="exercise-details">
            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($exercise['description'])); ?></p>
        </div>

        <div class="exercise-details">
            <h3>Target Muscle Group</h3>
            <p><?php echo htmlspecialchars($exercise['target_muscle_group']); ?></p>
        </div>

        <div class="exercise-details">
            <h3>Exercise Type</h3>
            <p><?php echo htmlspecialchars($exercise['exercise_type']); ?></p>
        </div>

        <div class="exercise-details">
            <h3>Equipment Required</h3>
            <p><?php echo htmlspecialchars($exercise['equipment_required']); ?></p>
        </div>

        <div class="exercise-details">
            <h3>Mechanics</h3>
            <p><?php echo htmlspecialchars($exercise['mechanics']); ?></p>
        </div>

        <div class="exercise-details">
            <h3>Force Type</h3>
            <p><?php echo htmlspecialchars($exercise['force_type']); ?></p>
        </div>

        <div class="exercise-details">
            <h3>Experience Level</h3>
            <p><?php echo htmlspecialchars($exercise['experience_level']); ?></p>
        </div>

        <div class="exercise-details">
            <h3>Secondary Muscles</h3>
            <p><?php echo htmlspecialchars($exercise['secondary_muscles']); ?></p>
        </div>

        <!-- New Part: Display the date and weight done by the user for this particular exercise -->
        <div class="exercise-details">
            <h3>Your Exercise History</h3>
            <?php
            // Fetch exercise history for the specific exercise and user
            $user_id = $_SESSION["id"]; // Assuming you store user id in session
            $exercise_id = $exercise['id']; // Use the exercise id
            $sql = "SELECT date, weight FROM history WHERE user_id = ? AND exercise_id = ? ORDER BY date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $exercise_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['weight']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No exercise history found for this exercise.</p>
            <?php endif; 
            $stmt->close();
            $conn->close();
            ?>
        </div>

        <p><a href="exercise_list.php">Back to Exercise List</a></p>
    </div>
</body>
</html>

<?php
} else {
    // Exercise not found
    echo '<p>Exercise not found.</p>';
    $stmt->close();
    $conn->close();
}
?>
