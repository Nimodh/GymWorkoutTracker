<?php
// Include your database connection file
require_once 'db_connection.php'; // Adjust the path if necessary

// Retrieve form data
$exercise_name = $_POST['exercise_name'];
$description = $_POST['description'];
$target_muscle_group = $_POST['target_muscle_group'];
$exercise_type = $_POST['exercise_type'];
$equipment_required = $_POST['equipment_required'];
$mechanics = $_POST['mechanics'];
$force_type = $_POST['force_type'];
$experience_level = $_POST['experience_level'];
$secondary_muscles = $_POST['secondary_muscles'];

// Prepare SQL statement to insert data into your `exercises` table
$sql = "INSERT INTO exercises 
        (name, description, target_muscle_group, exercise_type, equipment_required, mechanics, force_type, experience_level, secondary_muscles) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);

// Execute the prepared statement with the provided form data
$result = $stmt->execute([$exercise_name, $description, $target_muscle_group, $exercise_type, $equipment_required, $mechanics, $force_type, $experience_level, $secondary_muscles]);

// Check if the insertion was successful
if ($result) {
    // Redirect back to the welcome page or any other page
    header("Location: exercises.php");
    exit();
} else {
    // Handle error if insertion fails
    echo "Error adding exercise.";
}
?>
