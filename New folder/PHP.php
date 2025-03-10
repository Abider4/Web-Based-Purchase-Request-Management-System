<?php
// Database Connection

$servername = "localhost";
$username = "root"; // Default XAMPP MySQL user
$password = ""; // Default is empty
$dbname = "db"; // Make sure this matches your phpMyAdmin database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";


// Handle Form Submission (Create Purchase Request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $quantity = (int) $_POST['quantity'];
    $price = (float) $_POST['price'];

    $sql = "INSERT INTO requests (item_name, quantity, price) VALUES ('$item_name', $quantity, $price)";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Purchase request submitted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Handle Approval/Rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $id = (int) $_POST['id'];
    $status = $conn->real_escape_string($_POST['status']);

    if (in_array($status, ["Approved", "Rejected"])) {
        $sql = "UPDATE requests SET status='$status' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Status updated successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid status!</p>";
    }
}

// Fetch all purchase requests
$sql = "SELECT * FROM requests ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>Purchase Request Management System</h2>

    <!-- Submit Purchase Request Form -->
    <h3>Request a Purchase</h3>
    <form method="POST">
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <button type="submit" name="submit_request">Submit Request</button>
    </form>

    <!-- Approve/Reject a Request Form -->
    <h3>Approve or Reject Request</h3>
    <form method="POST">
        <input type="number" name="id" placeholder="Request ID" required>
        <select name="status">
            <option value="Approved">Approve</option>
            <option value="Rejected">Reject</option>
        </select>
        <button type="submit" name="update_status">Update Status</button>
    </form>

    <!-- Display All Requests -->
    <h3>All Purchase Requests</h3>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['item_name'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No purchase requests found!</p>
    <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
