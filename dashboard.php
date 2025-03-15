<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';
$query = "SELECT pr.id, pr.item, pr.quantity, pr.cost, pr.timestamp, u.name ,pr.status
          FROM PurchaseRequests pr 
          JOIN Users u ON pr.user_id = u.id 
          WHERE pr.status = 'pending'";
$result = $conn->query($query);
$query = "SELECT pr.id, pr.item, pr.quantity, pr.cost, pr.timestamp, u.name ,pr.status, pr.deliverystatus
          FROM PurchaseRequests pr 
          JOIN Users u ON pr.user_id = u.id 
          WHERE pr.status = 'approved'";
$resul = $conn->query($query);
$query = "SELECT pr.id, pr.item, pr.quantity, pr.cost, pr.timestamp, u.name ,pr.status, pr.deliverystatus
          FROM PurchaseRequests pr 
          JOIN Users u ON pr.user_id = u.id 
          WHERE pr.status = 'approved'";
$resu = $conn->query($query);


$user = $_SESSION['user'];
if (isset($_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action']; // 'approve' or 'decline'

    // Update the request status
    $status = "Delivered";
    $update_query = "UPDATE PurchaseRequests SET deliverystatus = '$status' WHERE id = $request_id";
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>

table {
    width: 100%; 
    border-collapse: collapse;
    margin: 20px 0; 
    font-family: Arial, sans-serif; 
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15); 


th {
    background-color: #009879; 
    color: white; 
    text-align: left; 
    padding: 12px 15px; 
    font-weight: bold; 
    text-transform: uppercase; 


tr {
    border-bottom: 1px solid #dddddd; 
}


tr:nth-child(even) {
    background-color: #f3f3f3;
}


tr:hover {
    background-color: #f1f1f1; 
}


td {
    padding: 12px 15px; 
    color: #333; 
}

tr:last-child {
    border-bottom: 2px solid #009879;
}


table {
    border-radius: 8px;
    overflow: hidden; 
}</style>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $user['name']; ?></h1>
        <p>Role: <?php echo $user['role']; ?></p>
        <?php if ($user['role'] === 'employee'): ?>
            <a href="request.php">Submit Purchase Request</a>
            <?php if ($result->num_rows > 0): ?>
            <table>
                <h1>Pending Requests</h1>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Requested By</th>
                        <th>Timestamp</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr><?php if($row['name']==$user['name']):?>
                            <?php if($row['status']=="pending"):?>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['item']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                            
                            <?php else: ?>
                             no pending order
                             <?php endif; ?>
                             <?php endif; ?>
                        </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending requests.</p>
        <?php endif; ?>
        <?php if ($resul->num_rows > 0): ?>
            <table>
                <h1>Delivered Requests</h1>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Requested By</th>
                        <th>Timestamp</th>
                        <th>Delivery status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resul->fetch_assoc()): ?>
                        <tr><?php if($row['name']==$user['name']):?>
                            <?php if($row['deliverystatus']=="not_delivered"):?>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['item']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                            <td>
                            <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="approve">Delivered</button>
                                </form></td>
                            <?php else: ?>
                             no Delivered order
                             <?php endif; ?>
                             <?php endif; ?>
                        </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No Delivered requests.</p>
        <?php endif; ?>
        <?php else: ?>
            <a href="approve.php">Approve Requests</a>
            
           
            <?php if ($resu->num_rows > 0): ?>
            <table>
                <h1>Approved Requests</h1>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Requested By</th>
                        <th>Timestamp</th>
                        <th>Delivery 
                            status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resu->fetch_assoc()): ?>
                        <tr>
                            <?php if($row['status']=="approved"):?>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['item']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                            <td><?php echo $row['deliverystatus']; ?></td>
                            <?php else: ?>
                             no approved order
                           
                             <?php endif; ?>
                        </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No approved requests.</p>
        <?php endif; ?>
        <?php endif; ?>
        <a href="includes/auth.php?logout">Logout</a>
    </div>
</body>
</html>