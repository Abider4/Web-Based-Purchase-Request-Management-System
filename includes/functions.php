<?php
session_start();
include '../db.php';

if (isset($_POST['submit_request'])) {
    $user_id = $_SESSION['user']['id'];
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $cost = $_POST['cost'];

    $stmt = $conn->prepare("INSERT INTO PurchaseRequests (user_id, item, quantity, cost, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isid", $user_id, $item, $quantity, $cost);
    $stmt->execute();

    header("Location: ../dashboard.php");
    exit();
}
?>