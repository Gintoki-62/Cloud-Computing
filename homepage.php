<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Graduation Store</title>
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
	<?php include 'header.php'; ?>

	<!-- Graduation Page Slider -->
	<div class="homepage-slider">
		<!-- Slide 1 -->
		<div class="single-homepage-slider homepage-bg-1">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-7 offset-lg-1 offset-xl-0">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Celebrate Your Success</p>
								<h1>Graduation Essentials</h1>
								<div class="hero-btns">
									<a href="product.php" class="boxed-btn">View Products</a>
									<a href="contact.php" class="bordered-btn">Get in Touch</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Slide 2 -->
		<div class="single-homepage-slider homepage-bg-2">
			<div class="container">
				<div class="row">
					<div class="col-lg-10 offset-lg-1 text-center">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Limited Time</p>
								<h1>Graduation Sale is Live!</h1>
								<div class="hero-btns">
									<a href="product.php" class="boxed-btn">Shop Now</a>
									<a href="contact.php" class="bordered-btn">Contact Us</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Slide 3 -->
		<div class="single-homepage-slider homepage-bg-3">
			<div class="container">
				<div class="row">
					<div class="col-lg-10 offset-lg-1 text-right">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Class of 2025</p>
								<h1>Memories that Last Forever</h1>
								<div class="hero-btns">
									<a href="product.php" class="boxed-btn">Explore Collection</a>
									<a href="contact.php" class="bordered-btn">Reach Out</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Features -->
	<div class="list-section pt-80 pb-80">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon"><i class="fas fa-shipping-fast"></i></div>
						<div class="content">
							<h3>Fast Delivery</h3>
							<p>Nationwide within 3-5 days</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon"><i class="fas fa-headset"></i></div>
						<div class="content">
							<h3>Support</h3>
							<p>Available 24/7 for you</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon"><i class="fas fa-thumbs-up"></i></div>
						<div class="content">
							<h3>Quality Assured</h3>
							<p>Premium Graduation Gear</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Products -->
	<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">
						<h3><span class="orange-text">Graduation</span> Products</h3>
						<p>Celebrate your moment with our top quality graduation merchandise.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<!-- Product 1 -->
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.php"><img src="assets/img/products/prod1.jpg" alt="Bouquet"></a>
						</div>
						<h3>Graduation Bouquet</h3>
						<p class="product-price"><span>Per Piece</span> $50</p>
						<a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
					</div>
				</div>

				<!-- Product 2 -->
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.php"><img src="assets/img/products/prod19.jpg" alt="Cap"></a>
						</div>
						<h3>Graduation Cap</h3>
						<p class="product-price"><span>Per Piece</span> $20</p>
						<a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
					</div>
				</div>

				<!-- Product 3 -->
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.php"><img src="assets/img/products/prod17.jpg" alt="Gown"></a>
						</div>
						<h3>Graduation Gown</h3>
						<p class="product-price"><span>Per Piece</span> $15</p>
						<a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Banner -->
	<section class="shop-banner">
    	<div class="container text-center">
        	<h3>Special Offer for <span class="orange-text">Graduates!</span></h3>
        	<div class="sale-percent"><span>Get</span> 30% <span>Off</span></div>
        	<a href="product.php" class="cart-btn btn-lg mt-3">Shop Now</a>
        </div>
    </section>

	<!-- Footer -->
	<?php include 'footer.php'; ?>
</body>
</html>
