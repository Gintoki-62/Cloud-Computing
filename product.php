<!DOCTYPE html>
<html lang="en">

<body>
	
<?php include 'header.php'; ?>
	
	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Welcome !</p>
						<h1>Graduation Products</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- products -->
	<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            <li data-filter=".Bouquet">Flower Bouquets</li>
                            <li data-filter=".Bear">Graduation Bear</li>
                            <li data-filter=".Uniform">Graduation Uniform</li>
                        </ul>
                    </div>
                </div>
            </div>

			<div class="row product-lists">
				<?php include '.vscode/config.php'; ?>
				<?php $result = mysqli_query($conn, "SELECT * FROM product"); ?>
				<?php while ($row = mysqli_fetch_assoc($result)) { ?>
					
				<div class="col-lg-4 col-md-6 text-center <?php echo $row['prod_type']; ?>">
					<div class="single-product-item">
						<div class="product-image">
							<img src="assets/img/products/<?php echo $row['prod_image']; ?>" alt="">
						</div>
						<h3><?php echo $row['prod_name']; ?></h3>
						<p class="product-price"><span>Price</span> RM  <?php echo $row['prod_price']; ?></p>
						<!-- <a href="cart.php?action=add&id=<?php echo $row['prod_id']; ?>" class="cart-btn">
    						<i class="fas fa-shopping-cart"></i> Add to Cart</a> -->
							<form method="post" action="cart.php" class="add-to-cart-form">
								<input type="hidden" name="prod_id" value="<?php echo $row['prod_id']; ?>">
								<input type="hidden" name="prod_name" value="<?php echo $row['prod_name']; ?>">
								<input type="hidden" name="prod_image" value="<?php echo $row['prod_image']; ?>">
								<input type="hidden" name="prod_price" value="<?php echo $row['prod_price']; ?>">
								<input type="hidden" name="quantity" value="1">
								<button type="submit" class="cart-btn" >
									<i class="fas fa-shopping-cart"></i> Add to Cart
								</button>
							</form>

					</div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
	<!-- end products -->

	<?php include 'footer.php'; ?>
</body>
</html>