<?php
$header = array(
    "prod_id" => "Product ID",
    "prod_name" => "Product Name",
    "prod_image" => "Image",
    "prod_type" => "Type",
    "prod_price" => "Price",
    "prod_quantity" => "Stock",
    "action" => "Actions"
);

include 'header.php';
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
                        window.location.href='product-details.php';
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
    <style>
        .product-image {
            max-width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .image-container {
            text-align: center;
        }
        .image-debug {
            font-size: 0.8rem;
            color: #666;
        }
        .text-danger {
            color: #dc3545;
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
                        <h1>Product Details</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- product details section -->
    <div class="mt-150 mb-150">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="order-details-wrap">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>PRODUCT DETAILS</h3>
                            <a href="add-product.php" class="boxed-btn">Add New Product</a>
                        </div>
                        
                        <!-- Search form -->
                        <form action="" method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="search" name="search_query" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($search_query) ?>">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                        
                        <form action="" method="POST">
                            <input type="submit" class="btn btn-danger mb-3" name="btn_delete_selected" value="Delete Selected" onclick="return confirm('This will delete all checked products. Are you sure?')"/>
                            
                            <?php if ($result->num_rows > 0) : ?>
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <?php foreach ($header as $key => $value) : ?>
                                            <th><?= $value ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_object()) : 
                                        // Get just the filename without any path
                                        $imageFile = basename($row->prod_image);
                                        $imagePath = "assets/img/" . $imageFile;
                                        $fullImagePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagePath;
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" name="selected_id[]" value="<?= $row->prod_id ?>"></td>
                                        <td><?= htmlspecialchars($row->prod_id) ?></td>
                                        <td><?= htmlspecialchars($row->prod_name) ?></td>
                                        <td class="image-container">
                                            <?php if (!empty($imageFile) && file_exists($fullImagePath)) : ?>
                                                <img src="<?= $imagePath ?>" 
                                                     alt="<?= htmlspecialchars($row->prod_name) ?>" 
                                                     class="product-image">
                                                <div class="image-debug">Showing: <?= $imageFile ?></div>
                                            <?php else : ?>
                                                <div class="text-danger">Image not found</div>
                                                <div class="image-debug">Looking for: <?= $imagePath ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row->prod_type) ?></td>
                                        <td class="text-right">$<?= number_format($row->prod_price, 2) ?></td>
                                        <td class="text-right"><?= $row->prod_quantity ?></td>
                                        <td class="text-right">
                                            <a href="edit-product.php?id=<?= $row->prod_id ?>" 
                                               class="btn btn-sm btn-primary">Edit</a>
                                            <a href="delete-product.php?id=<?= $row->prod_id ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php else : ?>
                                <div class="alert alert-warning text-center">No products found.</div>
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
</body>
</html>

<?php
// Close database connection
if (isset($result)) {
    $result->free(); 
}
$con->close(); 
?>