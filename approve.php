<?php
session_start();


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'manager') {
    header("Location: index.php");
    exit();
}

include 'db.php';


$query = "SELECT pr.id, pr.item, pr.quantity, pr.cost, pr.timestamp, u.name 
          FROM PurchaseRequests pr 
          JOIN Users u ON pr.user_id = u.id 
          WHERE pr.status = 'pending'";
$result = $conn->query($query);


if (isset($_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action']; 

    
    $status = ($action === 'approve') ? 'approved' : 'declined';
    $update_query = "UPDATE PurchaseRequests SET status = '$status' WHERE id = $request_id";
    if ($conn->query($update_query)) {
        echo "Request $request_id has been $status.";
    } else {
        echo "Error updating request: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Requests</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Approve Purchase Requests</h1>
        <a href="dashboard.php">Back to Dashboard</a>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Requested By</th>
                        <th>Timestamp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['item']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                            <td>
                                <form action="approve.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="approve">Approve</button>
                                </form>
                                <form action="approve.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="decline">Decline</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending requests.</p>
        <?php endif; ?>
    </div>
</body>
</html>