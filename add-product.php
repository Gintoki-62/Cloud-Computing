<?php
include 'header.php';
include '.vscode/config.php'; // Verify correct path to your config file

$error = '';
$success = '';
$prod_id = $prod_name = $prod_type = $prod_price = $prod_quantity = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize and validate inputs
    $prod_id = mysqli_real_escape_string($conn, trim($_POST['prod_id']));
    $prod_name = mysqli_real_escape_string($conn, trim($_POST['prod_name']));
    $prod_type = mysqli_real_escape_string($conn, trim($_POST['prod_type']));
    
    // Price validation
    $prod_price_input = trim($_POST['prod_price']);
    $prod_price = filter_var($prod_price_input, FILTER_VALIDATE_FLOAT);
    if ($prod_price === false || $prod_price <= 0) {
        $error = "Please enter a valid positive price.";
    }

    // Quantity validation
    $prod_quantity_input = trim($_POST['prod_quantity']);
    $prod_quantity = filter_var($prod_quantity_input, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0]
    ]);
    if ($prod_quantity === false) {
        $error = "Please enter a valid non-negative quantity.";
    }

    // Product ID validation
    if (empty($prod_id)) {
        $error = "Product ID is required.";
    } elseif (!preg_match('/^[A-Za-z0-9]{1,8}$/', $prod_id)) {
        $error = "ID must be 1-8 alphanumeric characters.";
    } else {
        // Check ID uniqueness
        $check_sql = "SELECT prod_id FROM product WHERE prod_id = '$prod_id'";
        $result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($result) > 0) {
            $error = "Product ID already exists.";
        }
    }

    // Product name validation
    if (empty($error)) {
        if (empty($prod_name)) {
            $error = "Product name is required.";
        } elseif (strlen($prod_name) < 3 || strlen($prod_name) > 100) {
            $error = "Name must be 3-100 characters.";
        } elseif (!preg_match('/^[\w\s\-.,()&\'"]+$/i', $prod_name)) {
            $error = "Invalid characters in product name.";
        }
    }

    // Product type validation
    $allowed_types = ['Bear', 'Bouquet', 'Uniform'];
    if (empty($error) && !in_array($prod_type, $allowed_types)) {
        $error = "Please select a valid product type.";
    }

    // Image validation
    $prod_image = '';
    if (empty($error)) {
        if (!isset($_FILES['prod_image']['error']) || $_FILES['prod_image']['error'] == UPLOAD_ERR_NO_FILE) {
            $error = "Product image is required.";
        } else {
            $target_dir = "assets/img/";
            $temp_file = $_FILES['prod_image']['tmp_name'];
            $file_size = $_FILES['prod_image']['size'];
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $temp_file);
            
            // Validate image file
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($mime_type, $allowed_mimes)) {
                $error = "Only JPG, PNG, and GIF images are allowed.";
            } elseif ($file_size > $max_size) {
                $error = "Image size exceeds 2MB limit.";
            } else {
                $ext = strtolower(pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION));
                $filename = uniqid('img_', true) . '.' . $ext;
                $target_file = $target_dir . $filename;
                
                if (!move_uploaded_file($temp_file, $target_file)) {
                    $error = "Failed to upload image.";
                } else {
                    $prod_image = $target_file;
                }
            }
            finfo_close($file_info);
        }
    }

    // Insert into database if no errors
    if (empty($error)) {
        $sql = "INSERT INTO product (prod_id, prod_name, prod_type, prod_price, prod_quantity, prod_image)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssdis", 
                $prod_id, 
                $prod_name, 
                $prod_type, 
                $prod_price, 
                $prod_quantity, 
                $prod_image
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Product added successfully!";
                // Clear form fields
                $prod_id = $prod_name = $prod_type = $prod_price = $prod_quantity = '';
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>

<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Admin Panel</p>
                    <h1>Add New Product</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-150 mb-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="order-details-wrap">
                    <h3 class="mb-4">ADD PRODUCT</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Product ID</label>
                            <input type="text" name="prod_id" class="form-control"
                                   placeholder="e.g. BQ1001" 
                                   pattern="[A-Za-z0-9]{1,8}" 
                                   title="1-8 alphanumeric characters"
                                   value="<?= htmlspecialchars($prod_id) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="prod_name" class="form-control" 
                                   minlength="3" maxlength="100"
                                   value="<?= htmlspecialchars($prod_name) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Product Type</label>
                            <select name="prod_type" class="form-control" required>
                                <option value="">Select Type</option>
                                <?php foreach (['Bear', 'Bouquet', 'Uniform'] as $type): ?>
                                <option value="<?= $type ?>" 
                                    <?= ($prod_type === $type) ? 'selected' : '' ?>>
                                    <?= $type ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Price (RM)</label>
                            <input type="number" name="prod_price" class="form-control" 
                                   step="0.01" min="0.01" 
                                   value="<?= htmlspecialchars($prod_price) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="prod_quantity" class="form-control" 
                                   min="0" value="<?= htmlspecialchars($prod_quantity) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="prod_image" 
                                   class="form-control-file" accept="image/*" required>
                            <small class="form-text text-muted">
                                Max 2MB (JPEG, PNG, GIF)
                            </small>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Add Product</button>
                            <a href="admin-product.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>