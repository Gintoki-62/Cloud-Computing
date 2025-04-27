<?php
include '.vscode/config.php'; 
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
	<?php include 'headerr.php'; ?>

	<!-- Graduation Page Slider -->
	<div class="homepage-slider">
		<!-- Slide 1 -->
		<div class="single-homepage-slider homepage-bg-1">
			<img src="assets/img/grand9.png" width="100%" height="700px">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-7 offset-lg-1 offset-xl-0">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Celebrate Your Success</p>
								<h1>Graduation Essentials</h1>
								<div class="hero-btns">
									<a href="product.php" class="boxed-btn">View Products</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Slide 2 -->
		<div class="single-homepage-slider homepage-bg-2">
		<img src="assets/img/grand7.png" width="100%" height="710px">
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
		<img src="assets/img/grand10.png" width="100%" height="710px">
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

	<!-- about us -->
	<div class="list-section pt-80 pb-80" >
		<div class="container">
		<h2>About Us</h2>
		<p>We are a one-stop shop for all your graduation needs, offering high-quality gowns, caps, sashes, and more to help you celebrate your special day. <br/>Our mission is to provide students and graduates with premium products, fast delivery, and exceptional customer service. <br/><br/>Let us be a part of your success story!</p>
		<br/><br/>
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

	<!-- show Products -->
	<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div>
						<h3><span class="orange-text">Graduation</span> Products</h3>
						<p>Celebrate your moment with our top quality graduation merchandise.</p>
					</div>
				</div>
			</div>
			<a href="product.php" class="cart-btn" style="float:right"><i class="fas fa-shopping-cart"></i> Shop Now</a>
			<br/><br/><br/>
			<div class="row">
				<!-- Product 1 -->
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.php"><img src="assets/img/products/prod1.jpg" alt="Bouquet"></a>
						</div>
						<h3>Graduation Bouquet</h3>
						<p class="product-price"><span>Price</span>RM 150</p>
					</div>
				</div>

				<!-- Product 2 -->
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.php"><img src="assets/img/products/prod19.jpg" alt="Cap"></a>
						</div>
						<h3>Graduation Cap</h3>
						<p class="product-price"><span>Price</span>RM 24.9</p>
					</div>
				</div>

				<!-- Product 3 -->
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.php"><img src="assets/img/products/prod17.jpg" alt="Gown"></a>
						</div>
						<h3>Graduation Gown</h3>
						<p class="product-price"><span>Price</span>RM 200</p>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- find our location -->
	<div class="find-location blue-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<p> <i class="fas fa-map-marker-alt"></i> Find Our Location</p>
				</div>
			</div>
		</div>
	</div>
	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1985.8714787184929!2d100.28065170097443!3d5.455928608941299!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ac2bf6bd07b4b%3A0xa9258650c6c6801b!2s31%2C%20Lintang%20Lembah%20Permai%202%2C%20Tanjung%20Tokong%2C%2011200%20Tanjung%20Bungah%2C%20Pulau%20Pinang!5e0!3m2!1sen!2smy!4v1745484191416!5m2!1sen!2smy" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

	<!-- Footer -->
	<?php include 'footer.php'; ?>
</body>
</html>
