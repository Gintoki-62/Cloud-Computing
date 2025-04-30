<?php
ob_start();
include '.vscode/config.php';
include 'headerr.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT order_id, order_date, payment_method, payment_status, total_amount FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        color: #333;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #b85c38;
    }

    .order-history-container {
        max-width: 1400px;
        margin: 40px auto;
        border-radius: 12px;
    }

    .order-history-box {
        background-color: #fff6f0;
        padding: 50px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0e0d0;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        font-size: 20px;
        table-layout: auto;
    }

    th {
        background-color: #c75d14;
        color: white;
        padding: 25px;
        font-size: 20px;
        vertical-align: middle;
    }

    td {
        padding: 25px;
        color: #555;
        font-size: 19px;
        vertical-align: middle;
    }

    tr {
        border-bottom: 1px solid #f0dfce;
    }

    thead {
        border-bottom: 2px solid #d6b5a0;
    }

    .btn-sm {
        background-color: #c75d14;
        color: white;
        border-radius: 999px;
        padding: 12px 20px;
        font-size: 16px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-sm:hover {
        background-color: #9e470f;
        color: white;
    }

    .no-orders {
        text-align: center;
        padding: 20px;
        font-size: 18px;
        color: #7a3e2e;
    }

    .go-to-shop {
        text-align: center;
        margin-top: 30px;
    }
</style>

<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Your Order History</p>
                    <h1>My Orders</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="order-history-container">
    <h2>Order History</h2>

    <div class="order-history-box">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Amount (RM)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="vertical-align: middle;"><?= $row['order_id'] ?></td>
                            <td style="vertical-align: middle;"><?= date('Y-m-d H:i', strtotime($row['order_date'])) ?></td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($row['payment_status']) ?></td>
                            <td style="vertical-align: middle;"><?= number_format($row['total_amount'], 2) ?></td>
                            <td style="vertical-align: middle;">
                                <a href="order_details.php?order_id=<?= $row['order_id'] ?>" class="btn-sm">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-orders">
                <p>You haven't started your order yet. Click below to start shopping.</p>
                <div class="go-to-shop">
                    <a href="product.php" class="btn-sm">Go to Shop</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php ob_end_flush(); ?>
