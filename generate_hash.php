<?php
$pass = 'AdminPW';
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
echo "happen: " . password_verify($pass, $hashed_password) . "<br>";
echo "Plain Password: " . $pass . "<br>";
echo "Hashed Password: " . $hashed_password . "<br>";
?>
