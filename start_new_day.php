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

// Prepare SQL statement to fetch username
$user_id = $_SESSION["id"]; // Assuming you store user id in session
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

// Function to fetch exercises from database
function fetchExercises($conn) {
    $sql = "SELECT id, name FROM exercises ORDER BY name";
    $result = $conn->query($sql);
    $exercises = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $exercises[] = $row;
        }
    }
    return $exercises;
}

// Fetch exercises
$exercises = fetchExercises($conn);

// Function to fetch today's exercises for the user
function fetchTodaysExercises($conn, $user_id) {
    $today = date("Y-m-d");
    $sql = "SELECT e.name, h.reps, h.sets, h.weight
            FROM history h
            JOIN exercises e ON h.exercise_id = e.id
            WHERE h.user_id = ? AND h.date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $todaysExercises = array();
    while ($row = $result->fetch_assoc()) {
        $todaysExercises[] = $row;
    }
    $stmt->close();
    return $todaysExercises;
}

// Fetch today's exercises
$todaysExercises = fetchTodaysExercises($conn, $user_id);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start New Day</title>
    <style>
        /* Basic styling for form and table */
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
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
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
        .max-weight-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Starting a New Day, <?php echo htmlspecialchars($username); ?>!</h1>
        
        <!-- Display Today's Exercises -->
        <h2>Today's Exercises</h2>
        <?php if (count($todaysExercises) > 0): ?>
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
                    <?php foreach ($todaysExercises as $exercise): ?>
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
            <p>No exercises recorded for today yet.</p>
        <?php endif; ?>

        <!-- Exercise Form -->
        <h2>Add New Exercise</h2>
        <form action="process_history.php" method="post">
            <label for="exercise">Select Exercise:</label>
            <select id="exercise" name="exercise" required>
                <option value="">Select an exercise...</option>
                <?php foreach ($exercises as $exercise): ?>
                    <option value="<?php echo htmlspecialchars($exercise['id']); ?>"><?php echo htmlspecialchars($exercise['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" id="maxWeightButton">Show Max Weight</button>
            <br>
            <div id="maxWeightInfo" class="max-weight-info" style="display: none;">
                <strong>Max Weight:</strong> <span id="maxWeight"></span><br>
                <strong>Date:</strong> <span id="maxWeightDate"></span>
            </div>
            <br>
            <label for="reps">Reps:</label>
            <input type="number" id="reps" name="reps" required>
            <br>
            <label for="sets">Sets:</label>
            <input type="number" id="sets" name="sets" required>
            <br>
            <label for="weight">Weight:</label>
            <input type="text" id="weight" name="weight">
            <br>
            <button type="submit">Add Exercise</button>
        </form>

        <p><a href="welcome.php">Back to Welcome Page</a></p>
    </div>

    <script>
        document.getElementById("maxWeightButton").addEventListener("click", function() {
            var exerciseId = document.getElementById("exercise").value;
            if (exerciseId) {
                fetch("max_weight.php?exercise_id=" + exerciseId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById("maxWeight").innerText = data.max_weight;
                            document.getElementById("maxWeightDate").innerText = data.date;
                            document.getElementById("maxWeightInfo").style.display = "block";
                        } else {
                            document.getElementById("maxWeightInfo").style.display = "none";
                            alert("No max weight data found for this exercise.");
                        }
                    });
            } else {
                alert("Please select an exercise.");
            }
        });
    </script>
</body>
</html>
