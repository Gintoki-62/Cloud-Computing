<?php
ob_start();
include '.vscode/config.php';
include 'headerr.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
	header("Location: login.php");
	exit();
}

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
	header("Location: product.php");
	exit();
}

$paymentDetails = [];

$sql = "SELECT total_amount, payment_method, order_date
        FROM orders
        WHERE order_id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	$paymentDetails = [
		'amount' => $row['total_amount'],
		'method' => $row['payment_method'],
		'date' => date('Y-m-d H:i:s', strtotime($row['order_date'])),
		'orderId' => $order_id
	];
} else {
	header("Location: product.php");
	exit();
}
?>


<style>
	#paymentContainer {
		background: rgb(251, 247, 244);
		border-radius: 12px;
		padding: 30px 60px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		margin: 50px auto;
		width: fit-content;
		max-width: 90%;
		text-align: center;
	}

	.tick {
		font-size: 60px;
		color: green;
	}

	.amount {
		font-size: 32px;
		font-weight: bold;
		margin: 15px 0;
	}

	.details {
		margin-top: 30px;
		text-align: left;
	}

	.details .detail-row {
		display: flex;
		justify-content: space-between;
		padding: 10px 0;
		font-size: 18px;
	}

	h1.success-title {
		font-size: 36px;
		color: #333;
		margin-top: 20px;
	}

	.back-btn {
		width: 100%;
		margin-top: 30px;
		padding: 14px 0;
		background-color: #28a745;
		color: white;
		border: none;
		border-radius: 6px;
		cursor: pointer;
		font-size: 18px;
		text-align: center;
		text-decoration: none;
		display: block;
	}

	.back-btn:hover {
		background-color: #218838;
		color: white;
	}
</style>

<div class="breadcrumb-section breadcrumb-bg">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 offset-lg-2 text-center">
				<div class="breadcrumb-text">
					<p>Thank you for your purchase. Please come again.</p>
					<h1>Thank You</h1>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container" id="paymentContainer">
	<div class="tick">✔</div>
	<div class="transferred" style="font-size: 24px; margin-top:10px;">Transferred</div>

	<div class="amount">
		RM <?= $paymentDetails['amount'] ?? '0.00' ?>
	</div>

	<h1 class="success-title">Payment Successful</h1>

	<div class="details" style="max-width: 400px; margin: 0 auto;">
		<div class="detail-row">
			<div class="detail-label" style="font-weight: bold;">Payment Method</div>
			<div class="detail-value"><?= $paymentDetails['method'] ?? 'N/A' ?></div>
		</div>
		<div class="detail-row">
			<div class="detail-label" style="font-weight: bold;">Payment Date</div>
			<div class="detail-value"><?= $paymentDetails['date'] ?? 'N/A' ?></div>
		</div>
		<div class="detail-row">
			<div class="detail-label" style="font-weight: bold;">Order ID</div>
			<div class="detail-value"><?= $paymentDetails['orderId'] ?? 'N/A' ?></div>
		</div>
	</div>

	<a class="back-btn" href="product.php">Continue Shopping</a>
</div>
<?php include 'footer.php'; ?>

<?php ob_end_flush(); ?>