<?php
include 'header.php';
include '.vscode/config.php';

// Establish database connection
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin-product.php");
    exit();
}

$prod_id = $con->real_escape_string($_GET['id']);

// Get product details
$sql = "SELECT * FROM product WHERE prod_id = '$prod_id'";
$result = $con->query($sql);

if ($result->num_rows == 0) {
    echo "<script>
            alert('Product not found.');
            window.location.href='admin-product.php';
          </script>";
    exit();
}

$product = $result->fetch_object();

// Get just the filename without any path
$imageFile = basename($product->prod_image);
$imagePath = "assets/img/" . $imageFile;
$fullImagePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagePath;

// Determine stock status class
$stockClass = '';
$stockText = '';
if ($product->prod_quantity > 20) {
    $stockClass = 'stock-high';
    $stockText = 'In Stock ('.$product->prod_quantity.')';
} elseif ($product->prod_quantity > 5) {
    $stockClass = 'stock-medium';
    $stockText = 'Low Stock ('.$product->prod_quantity.')';
} elseif ($product->prod_quantity > 0) {
    $stockClass = 'stock-low';
    $stockText = 'Very Low ('.$product->prod_quantity.')';
} else {
    $stockClass = 'stock-out';
    $stockText = 'Out of Stock';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product - <?= htmlspecialchars($product->prod_name) ?></title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .product-image-large {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .product-image-large:hover {
            transform: scale(1.02);
        }
        .product-details-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .product-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .product-body {
            padding: 30px;
        }
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        .detail-value {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        .price-tag {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
        }
        .stock-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
            display: inline-block;
            min-width: 150px;
            text-align: center;
        }
        .stock-high {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .stock-medium {
            background-color: #fff3cd;
            color: #664d03;
        }
        .stock-low {
            background-color: #f8d7da;
            color: #842029;
        }
        .stock-out {
            background-color: #f1f1f1;
            color: #6c757d;
            text-decoration: line-through;
        }
        .action-buttons .btn {
            min-width: 100px;
            margin: 5px;
        }
        .breadcrumb-section {
            margin-bottom: 50px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #6c757d;
        }
        .back-link:hover {
            color: #0d6efd;
            text-decoration: none;
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
                        <h1>Product Details</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- product view section -->
    <div class="mt-150 mb-150 animate__animated animate__fadeIn">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <a href="admin-product.php" class="back-link animate__animated animate__fadeIn">
                        <i class="bi bi-arrow-left me-1"></i> Back to All Products
                    </a>
                    
                    <div class="card product-details-card animate__animated animate__fadeInUp">
                        <div class="product-header">
                            <h3 class="mb-0">
                                <i class="bi bi-box-seam me-2"></i>
                                <?= htmlspecialchars($product->prod_name) ?>
                            </h3>
                        </div>
                        
                        <div class="product-body">
                            <div class="row">
                                <div class="col-md-5 mb-4 mb-md-0">
                                    <?php if (!empty($imageFile) && file_exists($fullImagePath)) : ?>
                                        <img src="<?= $imagePath ?>" 
                                             alt="<?= htmlspecialchars($product->prod_name) ?>" 
                                             class="product-image-large img-fluid">
                                    <?php else : ?>
                                        <div class="text-danger py-5 text-center border rounded">
                                            <i class="bi bi-image-alt" style="font-size: 3rem;"></i>
                                            <div class="mt-2">Image not found</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-7">
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <div class="detail-label">Product ID</div>
                                            <div class="detail-value"><?= htmlspecialchars($product->prod_id) ?></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="detail-label">Product Type</div>
                                            <div class="detail-value">
                                                <span class="badge bg-secondary"><?= htmlspecialchars($product->prod_type) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="detail-label">Description</div>
                                        <div class="detail-value">
                                            <?= !empty($product->prod_description) ? 
                                                nl2br(htmlspecialchars($product->prod_description)) : 
                                                '<span class="text-muted">No description provided</span>' ?>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <div class="detail-label">Price</div>
                                            <div class="price-tag">RM<?= number_format($product->prod_price, 2) ?></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="detail-label">Stock Status</div>
                                            <div class="detail-value">
                                                <span class="stock-status <?= $stockClass ?>">
                                                    <?= $stockText ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <div class="detail-label">Date Added</div>
                                            <div class="detail-value">
                                                <?= !empty($product->date_added) ? 
                                                    date('F j, Y', strtotime($product->date_added)) : 
                                                    'N/A' ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="detail-label">Last Updated</div>
                                            <div class="detail-value">
                                                <?= !empty($product->last_updated) ? 
                                                    date('F j, Y', strtotime($product->last_updated)) : 
                                                    'N/A' ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="action-buttons mt-4 pt-3 border-top">
                                        <a href="edit-product.php?id=<?= $product->prod_id ?>" 
                                           class="btn btn-primary">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <a href="delete-product.php?id=<?= $product->prod_id ?>" 
                                           class="btn btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </a>
                                        <a href="admin-product.php" class="btn btn-outline-secondary">
                                            <i class="bi bi-list-ul me-1"></i> View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end product view section -->

    <?php include 'footer.php'; ?>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connection
$con->close(); 
?>