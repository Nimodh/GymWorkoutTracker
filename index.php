<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Progress Tracker</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .buttons {
            margin-top: 20px;
        }
        .buttons a {
            text-decoration: none;
            margin: 0 10px;
        }
        .btn {
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-register {
            background-color: #28a745;
        }
        .btn-register:hover {
            background-color: #218838;
        }
    </style>
</head>
<body onload="typeWriter()">
    <div class="container">
        <h1>Gym Progress Tracker</h1>
        <div class="buttons">
            <a href="register.html" class="btn btn-register">Register Now</a>
            <a href="login.html" class="btn">Log In</a>
        </div>
    </div>

<script>
var i = 0;
var txt = 'Gym Progress Tracker';
var speed = 50;

function typeWriter() {
  if (i < txt.length) {
    document.getElementById("h1").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}
</script>
</body>
</html>
