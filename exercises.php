<?php
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

// Query to fetch exercise names categorized by target muscle group
$sql = "SELECT target_muscle_group, GROUP_CONCAT(name SEPARATOR ', ') AS exercises 
        FROM exercises 
        GROUP BY target_muscle_group";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise List</title>
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
        .muscle-group {
            margin-bottom: 15px;
        }
        .muscle-group h3 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .exercise-list {
            margin-left: 20px;
        }
        .exercise-list p {
            margin-bottom: 5px;
        }
        .add-exercise-btn {
            text-align: center;
            margin-top: 20px;
        }
        .add-exercise-btn a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .add-exercise-btn a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Exercise List by Target Muscle Group</h2>

        <?php
        // Check if there are results from the query
        if ($result->num_rows > 0) {
            // Output data grouped by target muscle group
            while ($row = $result->fetch_assoc()) {
                echo '<div class="muscle-group">';
                echo '<h3>' . htmlspecialchars($row['target_muscle_group']) . '</h3>';
                echo '<div class="exercise-list">';
                
                // Split exercises into an array and list them
                $exercises = explode(', ', $row['exercises']);
                foreach ($exercises as $exercise) {
                    // Output each exercise name as a clickable link
                    echo '<p><a href="exercise_details.php?name=' . urlencode($exercise) . '">' . htmlspecialchars($exercise) . '</a></p>';
                }
                
                echo '</div>'; // Close exercise-list
                echo '</div>'; // Close muscle-group
            }
        } else {
            echo '<p>No exercises found.</p>';
        }

        // Close the database connection
        $conn->close();
        ?>

        <!-- Add Exercise Button -->
        <div class="add-exercise-btn">
            <a href="add_exercise.php">Add Exercise</a>
        </div>

    </div>
</body>
</html>
