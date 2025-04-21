<?php
// Database configuration and connection
include 'header.php';
include '.vscode/config.php';

$con = new mysqli($host, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Table headers
$header = [
    "prod_id" => "Product ID",
    "prod_name" => "Product Name",
    "prod_image" => "Image",
    "prod_type" => "Type",
    "prod_price" => "Price",
    "prod_quantity" => "Stock",
    "action" => "Actions"
];

// Handle multiple delete
if (isset($_POST["btn_delete_selected"]) && isset($_POST["selected_id"])) {
    $checked = $_POST["selected_id"];
    if (!empty($checked)) {
        $escaped = array_map([$con, 'real_escape_string'], $checked);
        $sql = "DELETE FROM product WHERE prod_id IN('" . implode("','", $escaped) . "')";
        
        if ($con->query($sql)) {
            printf("<script>
                    alert('%d product(s) have been deleted.');
                    window.location.href='product-details.php';
                    </script>", $con->affected_rows);
        } else {
            echo "Error: Database Issue" . $sql . "<br>" . $con->error;
        }
    }
}

// Handle search functionality
$search_query = isset($_GET['search_query']) ? $con->real_escape_string($_GET['search_query']) : '';
$sql = "SELECT * FROM product"; 

if (!empty($search_query)) {
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="assets/css/styles.css" rel="stylesheet">
    
    <style>
        /* Product Card Styles */
        .product-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Table Styles */
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            color: white;
        }
        
        .table th {
            border: none;
            padding: 15px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 12px 15px;
        }
        
        /* Button Styles */
        .action-buttons .btn {
            margin: 0 3px;
            min-width: 70px;
        }
        
        .boxed-btn {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .boxed-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF0000 100%);
            border: none;
        }
        
        /* Image Styles */
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .no-image {
            width: 80px;
            height: 80px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            color: #6c757d;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-in-stock {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-low-stock {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-out-of-stock {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Breadcrumb Section */
        .breadcrumb-section {
            background-color: #f8f9fa;
            padding: 50px 0;
            margin-bottom: 50px;
        }
        
        .breadcrumb-text h1 {
            font-weight: 700;
            color: #2c3e50;
        }
        
        .breadcrumb-text p {
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 14px;
        }
        
        /* Utility Classes */
        .search-box {
            max-width: 400px;
        }
    </style>
</head>

<body>
    <!-- Breadcrumb Section -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Admin Panel</p>
                        <h1>Product Management</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card product-card">
                    <div class="card-body">
                        <!-- Header Section -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="card-title mb-0">
                                <i class="bi bi-box-seam me-2"></i>Product Inventory
                            </h3>
                            <div>
                                <a href="add-product.php" class="boxed-btn">
                                    <i class="bi bi-plus-circle"></i> Add New Product
                                </a>
                            </div>
                        </div>
                        
                        <!-- Search Form -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form action="" method="GET" class="d-flex">
                                    <div class="input-group search-box">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="search" name="search_query" class="form-control border-start-0" 
                                               placeholder="Search products..." 
                                               value="<?= htmlspecialchars($search_query) ?>">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Products Table -->
                        <form action="" method="POST">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <input type="submit" class="btn btn-danger" name="btn_delete_selected" 
                                           value="Delete Selected" 
                                           onclick="return confirm('This will delete all checked products. Are you sure?')"/>
                                </div>
                                <div class="text-muted">
                                    <span class="badge bg-primary">
                                        Total Products: <?= $result->num_rows ?>
                                    </span>
                                </div>
                            </div>
                            
                            <?php if ($result->num_rows > 0) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th width="40px">
                                                <input type="checkbox" id="selectAll" onclick="toggleCheckboxes(this)">
                                            </th>
                                            <?php foreach ($header as $value) : ?>
                                                <th><?= $value ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_object()) : 
                                            // Determine stock status
                                            $stock_status = match(true) {
                                                $row->prod_quantity > 20 => 'status-in-stock',
                                                $row->prod_quantity > 0 => 'status-low-stock',
                                                default => 'status-out-of-stock'
                                            };
                                            
                                            $status_text = match($stock_status) {
                                                'status-in-stock' => 'In Stock',
                                                'status-low-stock' => 'Low Stock',
                                                default => 'Out of Stock'
                                            };
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_id[]" value="<?= $row->prod_id ?>">
                                            </td>
                                            <td><strong><?= htmlspecialchars($row->prod_id) ?></strong></td>
                                            <td><?= htmlspecialchars($row->prod_name) ?></td>
                                            <td>
                                                <?php if (!empty($row->prod_image)) : ?>
                                                    <img src="assets/img/<?= htmlspecialchars($row->prod_image) ?>" 
                                                         class="product-image"
                                                         alt="<?= htmlspecialchars($row->prod_name) ?>">
                                                <?php else : ?>
                                                    <div class="no-image">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($row->prod_type) ?></td>
                                            <td class="fw-bold text-primary">
                                                $<?= number_format($row->prod_price, 2) ?>
                                            </td>
                                            <td>
                                                <?= $row->prod_quantity ?>
                                                <span class="status-badge <?= $stock_status ?>">
                                                    <?= $status_text ?>
                                                </span>
                                            </td>
                                            <td class="action-buttons">
                                                <a href="edit-product.php?id=<?= $row->prod_id ?>" 
                                                   class="btn btn-sm btn-primary"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Edit">
                                                   <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete-product.php?id=<?= $row->prod_id ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to delete this product?')"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Delete">
                                                   <i class="bi bi-trash"></i>
                                                </a>
                                                <a href="view-product.php?id=<?= $row->prod_id ?>" 
                                                   class="btn btn-sm btn-info"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="View">
                                                   <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else : ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-exclamation-circle display-4 text-muted"></i>
                                    <h3 class="mt-3">No products found</h3>
                                    <p class="text-muted">Try adjusting your search or add a new product</p>
                                    <a href="add-product.php" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle"></i> Add Product
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle all checkboxes
        function toggleCheckboxes(source) {
            const checkboxes = document.querySelectorAll('input[name="selected_id[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>

<?php
// Close database connection
$result->free(); 
$con->close(); 
?>