<!DOCTYPE html>
<html>
    <body>
    <head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

	<!-- title -->
	<title>GrandStore</title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="assets/img/logo.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="assets/css/responsive.css">

    </head>

    <?php
    include '.vscode/config.php'; 
    session_start(); 

        $adminPhoto = 'default.jpg'; // Default photo
        $folder = "./assets/img/" . $adminPhoto;

        $admin_id = $_SESSION['admin_id'] ?? null;
        
        if (isset($_SESSION['admin_id'])) {
            $query = "SELECT admin_photo FROM admins WHERE admin_id = '$admin_id'";
            $result = mysqli_query($conn, $query);
        
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
        
                if (!empty($row['admin_photo'])) {
                    $adminPhoto = $row['admin_photo'];
                    $folder = "./assets/img/" . $adminPhoto;
                }
            }
        }
    
    ?>

    <!--PreLoader-->
    <!-- <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div> -->
    <!-- PreLoader Ends -->
    
	<header>
        <!-- header -->
        <div class="top-header-area" id="sticker">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 text-center">
                        <div class="main-menu-wrap">
                            <!-- logo -->
                            <div class="site-logo">
                                <a href="admin-product.php">
                                    <img src="assets/img/logo.png" alt="">
                                </a>
                            </div>
                            <!-- logo --> 

                            <!-- menu start -->
                            <nav class="main-menu">
                                <ul>
                                    <li><a href="admin-product.php">Product</a></li>
                                    <li><a href="admin-user.php">Users</a></li>
                                    <li>
                                        <div class="header-icons">
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="adminProfile.php">
                                            <img src="<?php echo "$folder"; ?>" title="Profile" style="width: 50px; 
                                                                                                            height: 50px;
                                                                                                            border-radius: 30px;
                                                                                                            padding: 0px;"/>
                                            
                                            </a>
                                            <!-- <a href="login.php" class="login-btn"><i class="fas fa-user"></i> Login</a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="logout.php" class="login-btn"><i class="fas fa-user"></i> LogOut</a> -->
<?php
if (isset($_SESSION['admin_id'])) {
    // User is logged in: show Logout icon
    echo '
    <a href="logout.php" class="login-btn">
        <i class="fas fa-user"></i> LogOut
    </a>';
} else {
    // User not logged in: show Login icon
    echo '
    <a href="login.php" class="login-btn">
        <i class="fas fa-user"></i> Login
    </a>';
}
?>

                                        </div>
                                    </li>
                                </ul>
                            </nav>
                            <a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
                            <div class="mobile-menu"></div>
                            <!-- menu end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end header -->

        


        
    <!-- Search Area -->
    <div class="search-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <span class="close-btn"><i class="fas fa-window-close"></i></span>
                    <div class="search-bar">
                        <div class="search-bar-tablecell">
                            <h3>Search For:</h3>
                            <form method="GET" action="search.php">
                                <input type="text" name="keyword" placeholder=" " required>
                                <button type="submit">Search <i class="fas fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end search area -->

        </header>
    </body>
</html>