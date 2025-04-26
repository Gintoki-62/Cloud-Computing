<?php
include 'headerr.php';
include '.vscode/config.php';

// Establish database connection
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize variables
$product = null;
$errors = [];
$success = false;

// Check if we have a product ID to edit
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $prod_id = $con->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM product WHERE prod_id = '$prod_id'";
    $result = $con->query($sql);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_object();
    } else {
        $errors[] = "Product not found.";
    }
} else {
    $errors[] = "No product ID specified.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update'])) {
    // Validate and sanitize inputs
    $prod_id = $con->real_escape_string(trim($_POST['prod_id']));
    $prod_name = $con->real_escape_string(trim($_POST['prod_name']));
    $prod_type = $con->real_escape_string(trim($_POST['prod_type']));
    $prod_price = $con->real_escape_string(trim($_POST['prod_price']));
    $prod_quantity = $con->real_escape_string(trim($_POST['prod_quantity']));
    
    // Basic validation
    if (empty($prod_name)) $errors[] = "Product name is required.";
    if (empty($prod_type)) $errors[] = "Product type is required.";
    if (!is_numeric($prod_price) || $prod_price <= 0) $errors[] = "Price must be a positive number.";
    if (!is_numeric($prod_quantity)) $errors[] = "Quantity must be a number.";
    
    // Handle file upload
    $image_path = $product->prod_image; // Default to existing image
    
    if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "assets/img/products/";
        $imageFile = basename($_FILES["prod_image"]["name"]);
        $target_file = $target_dir . $imageFile;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["prod_image"]["tmp_name"]);
        if ($check === false) {
            $errors[] = "File is not an image.";
        }
        
        // Check file size (5MB max)
        if ($_FILES["prod_image"]["size"] > 5000000) {
            $errors[] = "Sorry, your file is too large (max 5MB).";
        }
        
        // Allow certain file formats
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        
        // If no errors, upload file
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["prod_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
                
                // Delete old image if it's not the default and not the new image
                if ($product->prod_image != $image_path && 
                    !empty($product->prod_image) && 
                    file_exists($product->prod_image) &&
                    strpos($product->prod_image, 'default-product.jpg') === false) {
                    unlink($product->prod_image);
                }
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }
    }
    
    // Update database if no errors
    if (empty($errors)) {
        $sql = "UPDATE product SET 
                prod_name = '$prod_name',
                prod_image = '$image_path',
                prod_type = '$prod_type',
                prod_price = '$prod_price',
                prod_quantity = '$prod_quantity'
                WHERE prod_id = '$prod_id'";
        
        if ($con->query($sql)) {
            $success = true;
            // Refresh product data
            $result = $con->query("SELECT * FROM product WHERE prod_id = '$prod_id'");
            $product = $result->fetch_object();
        } else {
            $errors[] = "Database error: " . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
    <style>
        .product-image-preview {
            max-width: 200px;
            max-height: 200px;
            display: block;
            margin: 10px 0;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 4px;
        }
        .breadcrumb-section {
            margin-bottom: 50px;
        }
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-submit {
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-cancel {
            padding: 10px 25px;
            font-weight: 600;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
        }
        .success-message {
            color: #198754;
            font-weight: 600;
        }
        .image-upload-container {
            border: 2px dashed #dee2e6;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .image-upload-container:hover {
            border-color: #adb5bd;
        }
    </style>
</head>
<body>
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Admin Panel</p>
                        <h1>Edit Product</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- edit product section -->
    <div class="mt-150 mb-150">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Product updated successfully!
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($product): ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="prod_id" value="<?= htmlspecialchars($product->prod_id) ?>">
                            
                            <div class="mb-3">
                                <label for="prod_id" class="form-label">Product ID</label>
                                <input type="text" class="form-control" id="prod_id" 
                                       value="<?= htmlspecialchars($product->prod_id) ?>" disabled>
                                <small class="text-muted">Product ID cannot be changed</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prod_name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="prod_name" name="prod_name" 
                                       value="<?= htmlspecialchars($product->prod_name) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prod_type" class="form-label">Product Type *</label>
                                <select class="form-select" id="prod_type" name="prod_type" required>
                                    <option value="">Select a type</option>
                                    <option value="Bear" <?= $product->prod_type == 'Bear' ? 'selected' : '' ?>>Bear</option>
                                    <option value="Bouquet" <?= $product->prod_type == 'Bouquet' ? 'selected' : '' ?>>Bouquet</option>
                                    <option value="Uniform" <?= $product->prod_type == 'Uniform' ? 'selected' : '' ?>>Uniform</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prod_price" class="form-label">Price (RM) *</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="prod_price" name="prod_price" 
                                       value="<?= htmlspecialchars($product->prod_price) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prod_quantity" class="form-label">Quantity in Stock *</label>
                                <input type="number" min="0" class="form-control" id="prod_quantity" name="prod_quantity" 
                                       value="<?= htmlspecialchars($product->prod_quantity) ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Product Image</label>
                                <div class="image-upload-container">
                                    <?php if (!empty($product->prod_image) && file_exists($product->prod_image)): ?>
                                        <img src="<?= htmlspecialchars($product->prod_image) ?>" 
                                             alt="Current Product Image" 
                                             class="product-image-preview mb-3">
                                    <?php else: ?>
                                        <div class="alert alert-warning mb-3">
                                            <i class="bi bi-exclamation-triangle"></i> No image currently set
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mb-2">
                                        <input type="file" class="form-control" id="prod_image" name="prod_image" 
                                               accept="image/*">
                                    </div>
                                    <small class="text-muted">Max size: 5MB. Allowed types: JPG, JPEG, PNG, GIF.</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="admin-product.php" class="btn btn-secondary btn-cancel">
                                    <i class="bi bi-arrow-left me-1"></i> Cancel
                                </a>
                                <button type="submit" name="btn_update" class="btn btn-primary btn-submit">
                                    <i class="bi bi-save me-1"></i> Update Product
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end edit product section -->

    <?php include 'footer.php'; ?>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview image before upload
        document.getElementById('prod_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.product-image-preview');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        // Create new preview if none exists
                        const container = document.querySelector('.image-upload-container');
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'product-image-preview mb-3';
                        container.insertBefore(img, container.firstChild);
                        
                        // Remove any existing warning
                        const warning = document.querySelector('.alert.alert-warning');
                        if (warning) warning.remove();
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
if (isset($con)) {
    $con->close();
}
?>