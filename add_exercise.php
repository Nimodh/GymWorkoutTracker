<!-- add_exercise.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Exercise</title>
    <style>
        /* Basic styling for demonstration purposes */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Exercise</h2>
        <form action="process_exercise.php" method="POST">
            <label for="exercise_name">Exercise Name:</label>
            <input type="text" id="exercise_name" name="exercise_name" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            
            <label for="target_muscle_group">Target Muscle Group:</label>
            <input type="text" id="target_muscle_group" name="target_muscle_group" required>
            
            <label for="exercise_type">Exercise Type:</label>
            <select id="exercise_type" name="exercise_type" required>
                <option value="Strength">Strength</option>
                <option value="Cardio">Cardio</option>
                <!-- Add more options as needed -->
            </select>
            
            <label for="equipment_required">Equipment Required:</label>
            <input type="text" id="equipment_required" name="equipment_required" required>
            
            <label for="mechanics">Mechanics:</label>
            <input type="text" id="mechanics" name="mechanics" required>
            
            <label for="force_type">Force Type:</label>
            <input type="text" id="force_type" name="force_type" required>
            
            <label for="experience_level">Experience Level:</label>
            <select id="experience_level" name="experience_level" required>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
                <!-- Add more options as needed -->
            </select>
            
            <label for="secondary_muscles">Secondary Muscles:</label>
            <input type="text" id="secondary_muscles" name="secondary_muscles" required>
            
            <input type="submit" value="Add Exercise">
        </form>
    </div>
</body>
</html>
