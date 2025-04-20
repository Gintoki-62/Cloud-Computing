<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, phone, address, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $error_message = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("sssss", $username, $email, $phone, $address, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Execution failed: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'header.php'; ?>
    <style>
        .signup-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60vh;
        }
        .signup-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        .signup-card h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .signup-card .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
        }
        .signup-card button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            background-color: #F28123;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }
        .signup-card button:hover {
            background-color: #d76d13;
        }
        .signup-card p {
            text-align: center;
            margin-top: 20px;
        }
        .signup-card p a {
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
                    <p>Create Your Account</p>
                    <h1>Sign Up</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- signup section -->
<div class="signup-section mt-150 mb-150">
    <div class="container">
        <div class="signup-wrapper">
            <div class="signup-card">
                <h3>Create an Account</h3>
                <?php if (isset($error_message)) { echo "<p style='color: red; text-align:center;'>$error_message</p>"; } ?>
                <form action="signup.php" method="POST">
                    <div class="form-group mb-3">
                        <input type="text" name="username" placeholder="Username" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="phone" placeholder="Phone Number" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="address" placeholder="Address" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" required>
                    </div>
                    <button type="submit">Sign Up</button>
                </form>

                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
