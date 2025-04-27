<?php
ob_start();
	include '.vscode/config.php';
	include 'headerr.php';

	// Get the logged-in user's ID
	$user_id = $_SESSION['user_id'] ?? null;

	if (!$user_id) {
		header("Location: login.php");
    	exit();
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
		$prod_id = $_POST['prod_id'];
		$quantity = (int)$_POST['quantity'];
		$cart_id = $_POST['cart_id'];
		
		$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE prod_id = ? ");
		$stmt->bind_param("is", $quantity, $prod_id);
		$stmt->execute();
		$stmt->close();
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
		$prod_id = $_POST['prod_id'];
		$cart_id = $_POST['cart_id'];
		
		$stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND prod_id = ? AND user_id = ?");
		$stmt->bind_param("iss", $cart_id, $prod_id, $user_id);
		$stmt->execute();
		$stmt->close();
	}

	// Get the user's current cart_id
	$cart_id = null;
	$cart_query = $conn->prepare("SELECT MAX(cart_id) FROM cart WHERE user_id = ?");
	$cart_query->bind_param("s", $user_id);
	$cart_query->execute();
	$cart_result = $cart_query->get_result();
	if ($cart_row = $cart_result->fetch_row()) {
		$cart_id = $cart_row[0];
	}
	$cart_query->close();
	ob_end_flush();
	?>

	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Your Favorite Product</p>
						<h1>Shopping Cart</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- cart -->
	<div class="cart-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="cart-table-wrap">
						<table class="cart-table">
							<thead class="cart-table-head">
								<tr class="table-head-row">
									<th class="product-remove">No.</th>
									<th class="product-image">Product</th>
									<th class="product-name">Name</th>
									<th class="product-price">Price</th>
									<th class="product-quantity">Quantity</th>
									<th class="product-total">Total</th>
									<th class="product-total"></th>
									<th class="product-total"></th>
								</tr>
							</thead>

							<?php 
							$counter = 1; 
							$grand_total = 0;
							$sub_total = 0;

							if ($cart_id) {
								// Get cart items for this user's cart
								$items_query = $conn->prepare("SELECT * FROM cart WHERE cart_id = ? AND user_id = ?");
								$items_query->bind_param("is", $cart_id, $user_id);
								$items_query->execute();
								$result = $items_query->get_result();

								if ($result->num_rows == 0) {
									echo "<tr><td colspan='8'><h5 style='color:#F28123;'>Your Shopping Cart is Empty!</h5></td></tr>";
								} else {
									while ($row = $result->fetch_assoc()) {
										$total_price = $row['quantity'] * $row['prod_price'];
										$sub_total += $total_price;
							?>
							<tbody>
								<tr class="table-body-row">
									<td><?php echo $counter++; ?>.</td>
									<td class="product-image"><img src="<?php echo $row['prod_image']; ?>" alt=""></td>
									<td class="product-name"><?php echo $row['prod_name']; ?></td>
									<td class="product-price">RM <?php echo $row['prod_price']; ?></td>

									<form method="POST" action="cart.php" id="cart" onsubmit="saveScrollPosition()">
									<input type="hidden" name="prod_id" value="<?= $row['prod_id'] ?>">
									<input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">

									<td class="product-price">
										<div class="quantity-display">
											<span class="quantity-text"><?= $row['quantity'] ?></span>
										</div>
										<div class="quantity-edit d-none">
											<button type="button" class="qty-minus" data-id="<?= $row['prod_id'] ?>">-</button>
											<input type="number" name="quantity" 
												value="<?= $row['quantity'] ?>" min="1" max="100"
												class="qty-input" data-price="<?= $row['prod_price'] ?>">
											<button type="button" class="qty-plus" data-id="<?= $row['prod_id'] ?>">+</button>
											<br><span class="error-msg"></span>
										</div>
									</td>

									<td class="product-total">RM <?php echo number_format($total_price, 2); ?></td>

									<td>
										<button type="button" name="update" class="update-btn" data-id="<?= $row['prod_id'] ?>" title="Edit">
										<img src="assets/img/products/edit.png" /></button>

										<button type="submit" name="save" id="saveBtn" class="save-btn d-none" data-id="<?= $row['prod_id'] ?>" title="Save">
										<img src="assets/img/products/yes.png" />
										</button>
										
									</td>

									<td class="product-remove"><button type="submit" name="delete" class="update-btn" title="Delete"><img src="assets/img/products/delete.png" /></button></td></form>
								</tr>
							</tbody>
							<?php 
									}
									$grand_total = $sub_total + 5.90;
								}
								$items_query->close();
							} else {
								echo "<tr><td colspan='8'><h5 style='color:#F28123;'>Your Shopping Cart is Empty!</h5></td></tr>";
							}
							?>
						</table>
					</div>
				</div>
			</div>
			<br/><br/>

			<div class="col-lg-4" style="float:right;text-align: end">
				<div class="total-section">
					<table class="total-table">
						<tr class="total-data">
							<td><strong>Subtotal: </strong></td>
							<td>RM <?php echo number_format($sub_total, 2); ?></td>
						</tr>
						<?php if ($sub_total > 0): ?>
						<tr class="total-data">
							<td><strong>Shipping: </strong></td>
							<td>RM 5.90</td>
						</tr>
						<?php endif; ?>
						<tr class="total-data">
							<td><strong>Grand Total: </strong></td>
							<td>RM <?php echo number_format($grand_total, 2); ?></td>
						</tr>
					</table>
					<div class="cart-buttons">
						<a href="checkout.html" class="boxed-btn black">Make the Payment</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end cart -->
	<br/><br/><br/><br/><br/><br/><br/>

	<?php include 'footer.php'; ?>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Edit button click
		document.addEventListener('click', function(e) {
			if (e.target.closest('.update-btn')) {
				const row = e.target.closest('tr');
				row.querySelector('.quantity-display').classList.add('d-none');
				row.querySelector('.quantity-edit').classList.remove('d-none');
				row.querySelector('.update-btn').classList.add('d-none');
				row.querySelector('.save-btn').classList.remove('d-none');
			}
		});

		// Save button click
		document.addEventListener('click', function(e) {
			if (e.target.closest('.save-btn')) {
				const row = e.target.closest('tr');
				const prodId = e.target.dataset.id;
				const input = row.querySelector('.qty-input');
				const newQty = parseInt(input.value);
				const price = parseFloat(input.dataset.price);
				
				// Update UI immediately
				row.querySelector('.quantity-text').textContent = newQty;
				row.querySelector('.product-total').textContent = 'RM ' + (price * newQty).toFixed(2);
				
				// Switch back to view mode
				row.querySelector('.quantity-display').classList.remove('d-none');
				row.querySelector('.quantity-edit').classList.add('d-none');
				row.querySelector('.update-btn').classList.remove('d-none');
				row.querySelector('.save-btn').classList.add('d-none');
			}
		});

		// Plus/Minus buttons
		document.addEventListener('click', function(e) {
			const row = e.target.closest('tr');
			if (!row) return;
			
			const input = row.querySelector('.qty-input');
			if (!input) return;
			
			let qty = parseInt(input.value) || 0;
			
			if (e.target.classList.contains('qty-plus')) {
				qty = Math.min(qty + 1, 100);
				input.value = qty;
				validateInput(input);
			} 
			else if (e.target.classList.contains('qty-minus')) {
				qty = Math.max(qty - 1, 1); // Minimum 1
				input.value = qty;
				validateInput(input);
			}
		});

		// Handle manual input
		document.addEventListener('input', function(e) {
			if (e.target.classList.contains('qty-input')) {
				validateInput(e.target);
			}
		});

		// Validation function
		function validateInput(input) {
			const value = parseInt(input.value) || 0;
			const plusButton = input.nextElementSibling.nextElementSibling; // Get the + button
			let errorMsg = plusButton.nextElementSibling;
			
			// Create error message element if it doesn't exist
			if (!errorMsg || !errorMsg.classList.contains('error-msg')) {
				errorMsg = document.createElement('span');
				errorMsg.style.color = 'red';
				errorMsg.style.fontSize = '0.8em';
				errorMsg.className = 'error-msg';
				plusButton.parentNode.insertBefore(errorMsg, plusButton.nextSibling);
			}
			
			// Validate and show message
			if (value < 1) {
				input.value = 1;
				errorMsg.textContent = 'Minimum is 1';
			} 
			else if (value > 100) {
				input.value = 100;
				errorMsg.textContent = 'Maximum is 100';
			} 
			else {
				errorMsg.textContent = '';
			}
		}
	});
	</script>
	</body>
</html>