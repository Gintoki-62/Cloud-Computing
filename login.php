<?php
session_start();
include '.vscode/config.php'; 

$error_message = "";  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Please fill in both fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                header("Location: homepage.php");
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "No user found with that username.";
        }
    }
}

$conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'header.php'; ?>
	<style>
		.login-wrapper {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 60vh;
		}
		.login-card {
			background-color: #fff;
			border-radius: 12px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
			padding: 40px;
			max-width: 450px;
			width: 100%;
		}
		.login-card h3 {
			text-align: center;
			margin-bottom: 30px;
			color: #333;
		}
		.login-card .form-control {
			border-radius: 8px;
			padding: 12px 15px;
			font-size: 16px;
		}
		.login-card button {
			width: 100%;
			padding: 12px;
			font-size: 16px;
			border-radius: 8px;
			background-color: #F28123;
			color: white;
			border: none;
			transition: background-color 0.3s ease;
		}
		.login-card button:hover {
			background-color: #d76d13;
		}
		.login-card p {
			text-align: center;
			margin-top: 20px;
		}
		.login-card p a {
			color: #F28123;
			text-decoration: none;
		}
	</style>
</head>
<body>

<!-- breadcrumb -->
<div class="breadcrumb-section breadcrumb-bg">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 offset-lg-2 text-center">
				<div class="breadcrumb-text">
					<p>Login to Your Account</p>
					<h1>Login</h1>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- login section -->
<div class="login-section mt-150 mb-150">
	<div class="container">
		<div class="login-wrapper">
			<div class="login-card">
				<h3>Login to Your Account</h3>
				<?php if (isset($error_message)) { echo "<p style='color: red; text-align:center;'>$error_message</p>"; } ?>
				<form action="login.php" method="POST">
					<div class="form-group mb-3">
						<input type="text" name="username" placeholder="Username" class="form-control" required>
					</div>
					<div class="form-group mb-4">
						<input type="password" name="password" placeholder="Password" class="form-control" required>
					</div>
					<button type="submit">Login</button>
				</form>
				<p>Don't have an account? <a href="signup.php">Sign Up</a></p>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
