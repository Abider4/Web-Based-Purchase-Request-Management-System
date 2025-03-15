<?php
$host = 'localhost';
$db = 'purchase_system';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn){
    echo "connected succesfully";
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
