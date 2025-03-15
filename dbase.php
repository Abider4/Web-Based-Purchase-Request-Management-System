<?php

$host = 'localhost';
$db = 'purchase_system';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function executeQuery($conn, $query) {
    if ($conn->query($query) === TRUE) {
        echo "Query executed successfully: $query<br>";
    } else {
        echo "Error executing query: " . $conn->error . "<br>";
    }
}


$queries = [
    "CREATE DATABASE IF NOT EXISTS purchase_system",
    "USE purchase_system",
    "CREATE TABLE IF NOT EXISTS Users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        role ENUM('employee', 'manager'),
        password VARCHAR(255)
    )",
    "CREATE TABLE IF NOT EXISTS PurchaseRequests (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        item VARCHAR(100),
        quantity INT,
        cost DECIMAL(10, 2),
        status ENUM('pending', 'approved', 'declined', 'delivered'),
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Users(id)
    )",
    "CREATE TABLE IF NOT EXISTS Budget (
        id INT PRIMARY KEY AUTO_INCREMENT,
        total_budget DECIMAL(10, 2),
        remaining_budget DECIMAL(10, 2)
    )",
    "INSERT INTO Users (name, email, role, password) VALUES 
    ('amir ibrahim', 'mira@gmail.com', 'employee', 'password123'),
    ('abider Smith', 'abider@gmail.com', 'manager', 'password123')",
    "INSERT INTO Budget (total_budget, remaining_budget) VALUES (10000, 10000)"
];


foreach ($queries as $query) {
    executeQuery($conn, $query);
}

$conn->close();
?>