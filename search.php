<!DOCTYPE html>
<html>
    <body>
        <?php
        include '.vscode/config.php';

        $searchResults = [];

        if (isset($_GET['keyword'])) {
            $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
            $query = "SELECT * FROM product WHERE prod_name LIKE '%$keyword%' OR prod_type LIKE '%$keyword%'";
            $result = mysqli_query($conn, $query);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row;
                }
            }
        }
        ?>
        <script>
		function showPopup(message) {
			const popup = document.createElement('div');
			popup.innerHTML = `
				<div style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; z-index:1000;">
					<div style="background:white; padding:25px; border-radius:10px; text-align:center;">
						<div style="font-size:20px; margin-bottom:20px;">${message}</div>
						<div onclick="this.parentElement.parentElement.remove()" style="padding:8px 16px; background:#25FD41; color:black; border:none; border-radius:4px; cursor:pointer;">
							CLOSE
						</div>
					</div>
				</div>
			`;
			document.body.appendChild(popup);
		}
	</script>
    
	<?php
		session_start();
		include '.vscode/config.php';

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
					echo "<script> showPopup('Successfully Added to Cart!');</script>";
				} else {
					echo "<script> showPopup('Failed to add to cart');</script>";
				}
			} else {
				// Product doesn't exist - insert new record
				$insert_stmt = $conn->prepare("INSERT INTO cart (prod_id, prod_name, prod_image, prod_price, quantity) VALUES (?, ?, ?, ?, ?)");
				$insert_stmt->bind_param("sssdi", $prod_id, $prod_name, $prod_image, $prod_price, $quantity);
				
				if ($insert_stmt->execute()) {
					echo "<script> showPopup('Successfully added to Cart !');</script>";
				} else {
					echo "<script> showPopup('Failed to add to cart');</script>";
				}
			}
		}
		
		include 'header.php';
	?>

<!-- Display Search Results -->
            <?php if (isset($_GET['keyword'])): ?>
                <!-- breadcrumb-section -->
            <div class="breadcrumb-section breadcrumb-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 text-center">
                            <div class="breadcrumb-text">
                            <p>Search Result for : <em> <?php echo htmlspecialchars($_GET['keyword']); ?></em></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end breadcrumb section -->
            <div class="container mt-5">
                <?php if (count($searchResults) > 0): ?>
                    <div class="row product-lists">
                        <?php foreach ($searchResults as $row): ?>
                            <div class="col-lg-4 col-md-6 text-center <?php echo $row['prod_type']; ?>">
                                <div class="single-product-item">
                                    <div class="product-image">
                                        <a href="single-product.html">
                                            <img src="assets/img/products/<?php echo $row['prod_image']; ?>" alt="">
                                        </a>
                                    </div>
                                    <h3><?php echo $row['prod_name']; ?></h3>
                                    <p class="product-price"><span>Price</span>RM  <?php echo $row['prod_price']; ?></p>
                                    <form method="post" action="product.php" class="add-to-cart-form" onsubmit="saveScrollPosition()">
                                        <input type="hidden" name="prod_id" value="<?php echo $row['prod_id']; ?>">
                                        <input type="hidden" name="prod_name" value="<?php echo $row['prod_name']; ?>">
                                        <input type="hidden" name="prod_image" value="<?php echo $row['prod_image']; ?>">
                                        <input type="hidden" name="prod_price" value="<?php echo $row['prod_price']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="cart-btn" id="cartBtn">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                                    <div class="breadcrumb-text">
                                    <p>No Products found matching your search.</p>
                                    </div>
                                    <br/><br/><br/>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php include 'footer.php'; ?>
    </body>
</html>
