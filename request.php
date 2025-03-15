<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Submit Purchase Request</h1>
        <form action="includes/functions.php" method="POST">
            <input type="text" name="item" placeholder="Item Name" required>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="number" name="cost" placeholder="Cost" required>
            <button type="submit" name="submit_request">Submit</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>