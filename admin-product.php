<?php
$header = array(
    "prod_id" => "Product ID",
    "prod_name" => "Product Name",
    "prod_image" => "Image",
    "prod_type" => "Type",
    "prod_price" => "Price",
    "prod_quantity" => "Stock Status", 
    "action" => "Actions"
);

include 'headerr.php';
include '.vscode/config.php';

// Establish database connection
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle multiple delete
if (isset($_POST["btn_delete_selected"])) {
    if (isset($_POST["selected_id"])) {
        $checked = $_POST["selected_id"];
        if (!empty($checked)) {
            $escaped = array();
            foreach ($checked as $value) {
                $escaped[] = $con->real_escape_string($value);
            }
            $sql = "DELETE FROM product WHERE prod_id IN('" . implode("','", $escaped) . "')";
            if ($con->query($sql)) {
                printf("<script>
                        alert('%d product(s) have been deleted.');
                        window.location.href='admin-product.php';
                        </script>", $con->affected_rows);
            } else {
                echo "Error: Database Issue" . $sql . "<br>" . $con->error;
            }
        }
    }
}

// Handle search functionality
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$sql = "SELECT * FROM product"; 
if (!empty($search_query)) {
    $search_query = $con->real_escape_string($search_query);
    $sql .= " WHERE prod_name LIKE '%$search_query%' OR prod_id LIKE '%$search_query%'";
}

// Handle single product lookup
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["prod_id"])) {
    $prod_id = $con->real_escape_string(trim($_POST["prod_id"]));
    $sql = "SELECT * FROM product WHERE prod_id = '$prod_id'";
}

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .product-image {
            max-width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
            transition: transform 0.3s ease;
        }
        .product-image:hover {
            transform: scale(1.1);
        }
        .image-container {
            text-align: center;
            padding: 10px;
        }
        .image-debug {
            font-size: 0.8rem;
            color: #666;
            display: none; /* Hidden by default, can be shown for debugging */
        }
        .text-danger {
            color: #dc3545;
        }
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(248, 249, 250, 0.7);
            transform: translateX(2px);
        }
        .action-buttons .btn {
            margin: 2px;
            min-width: 70px;
        }
        .search-box {
            max-width: 400px;
            margin: 0 auto 20px;
        }
        .breadcrumb-section {
            margin-bottom: 50px;
        }
        .order-details-wrap {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        .table th {
            font-weight: 600;
            border-top: none;
        }
        .table thead th {
            border-bottom: 2px solid #dee2e6;
        }
        .btn-add-product {
            padding: 8px 20px;
            font-weight: 500;
        }
        .btn-delete-selected {
            padding: 8px 20px;
            font-weight: 500;
        }
        .alert-no-products {
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        /* Stock status styles */
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
        .stock-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            min-width: 100px;
            text-align: center;
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

    <!-- product details section -->
    <div class="mt-150 mb-150 animate__animated animate__fadeIn">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="order-details-wrap">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0"><i class="bi bi-box-seam me-2"></i>PRODUCT DETAILS</h3>
                            <a href="add-product.php" class="boxed-btn btn-add-product">
                                <i class="bi bi-plus-circle me-1"></i> Add New Product
                            </a>
                        </div>
                        
                        <!-- Search form -->
                        <div class="search-box animate__animated animate__fadeIn">
                            <form action="" method="GET" class="mb-4">
                                <div class="input-group shadow-sm">
                                    <input type="search" name="search_query" class="form-control border-end-0" 
                                           placeholder="Search products by name or ID..." 
                                           value="<?= htmlspecialchars($search_query) ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <form action="" method="POST">
                            <button type="submit" class="btn btn-danger mb-4 btn-delete-selected" 
                                    name="btn_delete_selected" 
                                    onclick="return confirm('This will delete all checked products. Are you sure?')">
                                <i class="bi bi-trash me-1"></i> Delete Selected
                            </button>
                            
                            <?php if ($result->num_rows > 0) : ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="40"><input type="checkbox" id="selectAll"></th>
                                            <?php foreach ($header as $key => $value) : ?>
                                                <th><?= $value ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_object()) : 
                                            // Get just the filename without any path
                                            $imageFile = basename($row->prod_image);
                                            $imagePath = "assets/img/products/" . $imageFile;
                                            $fullImagePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagePath;
                                            
                                            // Determine stock status class
                                            $stockClass = '';
                                            $stockText = '';
                                            if ($row->prod_quantity > 20) {
                                                $stockClass = 'stock-high';
                                                $stockText = 'In Stock ('.$row->prod_quantity.')';
                                            } elseif ($row->prod_quantity > 5) {
                                                $stockClass = 'stock-medium';
                                                $stockText = 'Low Stock ('.$row->prod_quantity.')';
                                            } elseif ($row->prod_quantity > 0) {
                                                $stockClass = 'stock-low';
                                                $stockText = 'Very Low ('.$row->prod_quantity.')';
                                            } else {
                                                $stockClass = 'stock-out';
                                                $stockText = 'Out of Stock';
                                            }
                                        ?>
                                        <tr class="animate__animated animate__fadeIn">
                                            <td><input type="checkbox" name="selected_id[]" value="<?= $row->prod_id ?>"></td>
                                            <td class="fw-bold"><?= htmlspecialchars($row->prod_id) ?></td>
                                            <td><?= htmlspecialchars($row->prod_name) ?></td>
                                            <td class="image-container">
                                                <?php if (!empty($imageFile) && file_exists($fullImagePath)) : ?>
                                                    <img src="<?= $imagePath ?>" 
                                                         alt="<?= htmlspecialchars($row->prod_name) ?>" 
                                                         class="product-image img-thumbnail">
                                                    <div class="image-debug">Showing: <?= $imageFile ?></div>
                                                <?php else : ?>
                                                    <div class="text-danger"><i class="bi bi-image-alt"></i> Image not found</div>
                                                    <div class="image-debug">Looking for: <?= $imagePath ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row->prod_type) ?></span></td>
                                            <td class="text-right fw-bold text-success">RM<?= number_format($row->prod_price, 2) ?></td>
                                            <td class="text-center">
                                                <span class="stock-status <?= $stockClass ?>">
                                                    <?= $stockText ?>
                                                </span>
                                            </td>
                                            <td class="text-right action-buttons">
                                                <a href="view-product.php?id=<?= $row->prod_id ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit-product.php?id=<?= $row->prod_id ?>" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete-product.php?id=<?= $row->prod_id ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure?')"
                                                   title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else : ?>
                                <div class="alert alert-warning text-center alert-no-products animate__animated animate__fadeIn">
                                    <i class="bi bi-exclamation-circle me-2"></i> No products found.
                                    <?php if (!empty($search_query)) : ?>
                                        <a href="admin-product.php" class="alert-link ms-2">Clear search</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end product details section -->

    <?php include 'footer.php'; ?>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Select all checkboxes
        document.getElementById('selectAll').addEventListener('click', function(e) {
            var checkboxes = document.getElementsByName('selected_id[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = e.target.checked;
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
if (isset($result)) {
    $result->free(); 
}
$con->close(); 
?>