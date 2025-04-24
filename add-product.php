<?php
include 'headerr.php';
include '.vscode/config.php';

// Establish database connection
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize variables
$prod_id = $prod_name = $prod_type = $prod_price = $prod_quantity = $prod_description = '';
$prod_image = '';
$errors = array();
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $prod_id = trim($_POST['prod_id']);
    $prod_name = trim($_POST['prod_name']);
    $prod_type = trim($_POST['prod_type']);
    $prod_price = trim($_POST['prod_price']);
    $prod_quantity = trim($_POST['prod_quantity']);
    $prod_description = trim($_POST['prod_description']);
    
    // Basic validation
    if (empty($prod_id)) $errors[] = "Product ID is required";
    if (empty($prod_name)) $errors[] = "Product name is required";
    if (empty($prod_type)) $errors[] = "Product type is required";
    if (empty($prod_price) || !is_numeric($prod_price)) $errors[] = "Valid price is required";
    if (empty($prod_quantity) || !is_numeric($prod_quantity)) $errors[] = "Valid quantity is required";
    
    // Check if product ID already exists
    $check_sql = "SELECT prod_id FROM product WHERE prod_id = ?";
    $stmt = $con->prepare($check_sql);
    $stmt->bind_param("s", $prod_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errors[] = "Product ID already exists";
    }
    $stmt->close();
    
    // Handle file upload
    if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "assets/img/products/";
        $imageFileType = strtolower(pathinfo($_FILES["prod_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["prod_image"]["tmp_name"]);
        if ($check === false) {
            $errors[] = "File is not an image.";
        }
        
        // Check file size (5MB max)
        if ($_FILES["prod_image"]["size"] > 5000000) {
            $errors[] = "Sorry, your file is too large (max 5MB).";
        }
        
        // Allow certain file formats
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
        
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["prod_image"]["tmp_name"], $target_file)) {
                $prod_image = $target_file;
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $errors[] = "Product image is required";
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        $insert_sql = "INSERT INTO product (prod_id, prod_name, prod_image, prod_type, prod_price, prod_quantity, prod_description) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insert_sql);
        $stmt->bind_param("ssssdis", $prod_id, $prod_name, $prod_image, $prod_type, $prod_price, $prod_quantity, $prod_description);
        
        if ($stmt->execute()) {
            $success = "Product added successfully!";
            // Clear form
            $prod_id = $prod_name = $prod_type = $prod_price = $prod_quantity = $prod_description = '';
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$con->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .breadcrumb-section {
            margin-bottom: 50px;
        }
        .product-form-wrap {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        .form-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .form-label {
            font-weight: 600;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .image-preview-container {
            text-align: center;
            margin: 20px 0;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border: 2px dashed #ddd;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        .btn-submit {
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-back {
            padding: 10px 25px;
            font-weight: 600;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text animate__animated animate__fadeInDown">
                        <p>Admin Panel</p>
                        <h1>Add New Product</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- add product form section -->
    <div class="mt-150 mb-150 animate__animated animate__fadeIn">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="product-form-wrap">
                        <div class="form-header d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0"><i class="bi bi-plus-circle me-2"></i>PRODUCT INFORMATION</h3>
                            <a href="admin-product.php" class="btn btn-secondary btn-back">
                                <i class="bi bi-arrow-left me-1"></i> Back to Products
                            </a>
                        </div>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger animate__animated animate__shakeX">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success animate__animated animate__fadeIn">
                                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="add-product.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prod_id" class="form-label required-field">Product ID</label>
                                        <input type="text" class="form-control" id="prod_id" name="prod_id" 
                                               value="<?php echo htmlspecialchars($prod_id); ?>" required>
                                        <div class="form-text">Unique identifier for the product</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="prod_name" class="form-label required-field">Product Name</label>
                                        <input type="text" class="form-control" id="prod_name" name="prod_name" 
                                               value="<?php echo htmlspecialchars($prod_name); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="prod_type" class="form-label required-field">Product Type</label>
                                        <select class="form-select" id="prod_type" name="prod_type" required>
                                            <option value="" disabled selected>Select a type</option>
                                            <option value="Bear" <?php echo ($prod_type == 'Bear') ? 'selected' : ''; ?>>Bear</option>
                                            <option value="Bouquet" <?php echo ($prod_type == 'Bouquet') ? 'selected' : ''; ?>>Bouquet</option>
                                            <option value="Uniform" <?php echo ($prod_type == 'Uniform') ? 'selected' : ''; ?>>Uniform</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prod_price" class="form-label required-field">Price (RM)</label>
                                        <input type="number" step="0.01" class="form-control" id="prod_price" name="prod_price" 
                                               value="<?php echo htmlspecialchars($prod_price); ?>" min="0" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="prod_quantity" class="form-label required-field">Quantity in Stock</label>
                                        <input type="number" class="form-control" id="prod_quantity" name="prod_quantity" 
                                               value="<?php echo htmlspecialchars($prod_quantity); ?>" min="0" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="prod_image" class="form-label required-field">Product Image</label>
                                        <input type="file" class="form-control" id="prod_image" name="prod_image" accept="image/*" required>
                                        <div class="form-text">Max file size: 5MB. Allowed types: JPG, JPEG, PNG, GIF</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prod_description" class="form-label">Description</label>
                                <textarea class="form-control" id="prod_description" name="prod_description" rows="3"><?php echo htmlspecialchars($prod_description); ?></textarea>
                            </div>
                            
                            <div class="image-preview-container">
                                <img id="imagePreview" src="#" alt="Image Preview" class="image-preview">
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="reset" class="btn btn-outline-secondary me-3">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-submit">
                                    <i class="bi bi-save me-1"></i> Save Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end add product form section -->

    <?php include 'footer.php'; ?>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview functionality
        document.getElementById('prod_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Generate a simple product ID if field is empty
        document.getElementById('prod_id').addEventListener('focus', function() {
            if (this.value === '') {
                const randomId = 'PRD' + Math.floor(1000 + Math.random() * 9000);
                this.value = randomId;
            }
        });
    </script>
</body>
</html>