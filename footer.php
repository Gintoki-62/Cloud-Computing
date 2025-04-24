<!DOCTYPE html>
<html>
    <body>
        <!-- footer -->
	<div class="footer-area">
		<div class="container">
			<div class="row">
				<table style="width: 100%;">
					<tr>
						<td style="width:20%; text-align: left; padding-right: 50px">
							<img src="assets/img/logo.png"  width="200px" height="100px"/>
						</td>
						<td style="width:20%">
							<div class="footer-box pages">
							<h2 class="widget-title">Pages</h2>
							<ul>
								<li><a href="homepage.php">Home</a></li>
								<li><a href="product.php">Product</a></li>
								<li><a href="news.php">Check Out</a></li>
							</ul>
							</div>
						</td>
						<td style="width:30%">
							<div class="footer-box get-in-touch">
							<div class="contact-form-box">
								<h2 class="widget-title"><i class="far fa-clock"></i> Shop Hours</h2>
								<p>MONDAY - FRIDAY: <br/>8.00 to 9.00 PM <br><br/> SATURDAY - SUNDAY: <br/>10.00 to 8.00 PM </p>
							</div>
						</div>
						<td>
							<div class="footer-box get-in-touch">
							<div class="contact-form-box">
								<h2 class="widget-title"><i class="fas fa-address-book"></i> Contact</h2>
								<p>Phone: +60 111 222 3333 <br> Email: grandstore888@gmail.com</p>
							</div>
							</div>
						</td>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<!-- end footer -->
	
	<!-- copyright -->
	<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<p>Copyrights &copy; 2025 - <a href="https://imransdesign.com/">GRANDSTORE</a>,  All Rights Reserved.</p>
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end copyright -->
	
	<!-- jquery -->
	<script src="assets/js/jquery-1.11.3.min.js"></script>
	<!-- bootstrap -->
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- count down -->
	<script src="assets/js/jquery.countdown.js"></script>
	<!-- isotope -->
	<script src="assets/js/jquery.isotope-3.0.6.min.js"></script>
	<!-- waypoints -->
	<script src="assets/js/waypoints.js"></script>
	<!-- owl carousel -->
	<script src="assets/js/owl.carousel.min.js"></script>
	<!-- magnific popup -->
	<script src="assets/js/jquery.magnific-popup.min.js"></script>
	<!-- mean menu -->
	<script src="assets/js/jquery.meanmenu.min.js"></script>
	<!-- sticker js -->
	<script src="assets/js/sticker.js"></script>
	<!-- main js -->
	<script src="assets/js/main.js"></script>

	<script>
		function saveScrollPosition() {
			sessionStorage.setItem('scrollPos', window.scrollY);
		}

		// Save scroll position when navigating away
		window.addEventListener('beforeunload', saveScrollPosition);

		// On page load: hide loader + restore scroll
		window.addEventListener('load', function() {
			document.querySelector('.loader').style.display = 'none';
			
			const savedScrollPos = sessionStorage.getItem('scrollPos');
			if (savedScrollPos !== null) {
				window.scrollTo(0, parseInt(savedScrollPos));
				sessionStorage.removeItem('scrollPos');
			}
		});
	</script>

    </body>
</html>