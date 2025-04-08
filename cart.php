<!DOCTYPE html>
<html lang="en">

<body>
	<!-- JavaScript popup function (put in your HTML head or footer) -->
	<script>
	function showPopup(message) {
		const popup = document.createElement('div');
		popup.innerHTML = `
			<div style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; z-index:1000;">
				<div style="background:white; padding:25px; border-radius:10px; text-align:center;">
					<div style="font-size:20px; margin-bottom:20px;">${message}</div>
					<button onclick="this.parentElement.parentElement.remove()" style="padding:8px 16px; background:#6c757d; color:white; border:none; border-radius:4px; cursor:pointer;">
						CLOSE
					</button>
				</div>
			</div>
		`;
		document.body.appendChild(popup);
	}
	</script>

	<?php
		session_start();
		include '.vscode/config.php';
		include 'header.php';

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$prod_id = $_POST['prod_id'];
			$prod_name = $_POST['prod_name'];
			$prod_image = $_POST['prod_image'];
			$prod_price = $_POST['prod_price'];
			$quantity = $_POST['quantity'];

			// First check if product already exists in cart
			$check_stmt = $conn->prepare("SELECT quantity FROM cart WHERE prod_id = ?");
			$check_stmt->bind_param("s", $prod_id);
			$check_stmt->execute();
			$result = $check_stmt->get_result();
			
			if ($result->num_rows > 0) {
				// Product exists - update quantity
				$row = $result->fetch_assoc();
				$new_quantity = $row['quantity'] + $quantity;
				
				$update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE prod_id = ?");
				$update_stmt->bind_param("is", $new_quantity, $prod_id);
				
				if ($update_stmt->execute()) {
					echo "<script> showPopup('Successfully Added !');</script>";
				} else {
					echo "<script> showPopup('Failed to add product to cart');</script>";
				}
			} else {
				// Product doesn't exist - insert new record
				$insert_stmt = $conn->prepare("INSERT INTO cart (prod_id, prod_name, prod_image, prod_price, quantity) VALUES (?, ?, ?, ?, ?)");
				$insert_stmt->bind_param("sssdi", $prod_id, $prod_name, $prod_image, $prod_price, $quantity);
				
				if ($insert_stmt->execute()) {
					echo "<script> showPopup('Successfully Added !');</script>";
				} else {
					echo "<script> showPopup('Failed to add product to cart');</script>";
				}
			}
		}
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
									<th class="product-remove"></th>
									<th class="product-image">Product Image</th>
									<th class="product-name">Name</th>
									<th class="product-price">Price</th>
									<th class="product-quantity">Quantity</th>
									<th class="product-total">Total</th>
									<th class="product-total"></th>
									<th class="product-total"></th>
								</tr>
							</thead>

				<?php $result = mysqli_query($conn, "SELECT * FROM cart"); ?>
				<?php $counter = 1; ?>
				<?php $grand_total = 0; ?>
				<?php while ($row = mysqli_fetch_assoc($result)) { ?>
					<?php $total_price = $row['quantity'] * $row['prod_price']; ?>
					<?php $grand_total += $total_price; ?>
					<?php $last_total = $grand_total+5.90; ?>
							<tbody>
								<tr class="table-body-row">
									<td><?php echo $counter++; ?>.</td>
									<td class="product-image"><img src="assets/img/products/<?php echo $row['prod_image']; ?>" alt=""></td>
									<td class="product-name"><?php echo $row['prod_name']; ?></td>
									<td class="product-price">RM <?php echo $row['prod_price']; ?></td>
									<td class="product-price">
										<div class="quantity-control">
											<button type="button" class="qty-minus" data-id="<?= $row['prod_id'] ?>">-</button>
											<input type="number" name="quantity[<?= $row['prod_id'] ?>]" 
												value="<?= $row['quantity'] ?>" min="1" class="qty-input"
												data-price="<?= $row['prod_price'] ?>">
											<button type="button" class="qty-plus" data-id="<?= $row['prod_id'] ?>">+</button>
										</div>
									</td>

									<!-- <td class="product-quantity"><input type="number" placeholder="<?php echo $row['quantity']; ?>"></td> -->
									
									<td class="product-total">RM <?php echo number_format($total_price, 2); ?></td>
									<td><button type="submit" name="update" class="update-btn" title="Edit">
										<img src="assets/img/products/edit.png" /></button></td>
									<td class="product-remove"><a href="#" title="Delete"><img src="assets/img/products/delete.png" /></a></td>
								</tr>
							</tbody>
				<?php }?>
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
									<td>RM  <?php echo number_format($grand_total, 2); ?></td>
								</tr>
								<tr class="total-data">
									<td><strong>Shipping: </strong></td>
									<td>RM  5.90</td>
								</tr>
								<tr class="total-data">
									<td><strong>Grand Total: </strong></td>
									<td>RM  <?php echo number_format($last_total, 2); ?></td>
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

</body>
</html>