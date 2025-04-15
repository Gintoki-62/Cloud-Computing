<?php
include 'header.php';
include '.vscode/config.php';

$product = [];
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["prod_id"])) {
    // Establish a db connection
   $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Connection Error:" . mysqli_connect_error());
    }

    $prod_id = mysqli_real_escape_string($conn, trim($_GET["prod_id"]));
    $sql = "SELECT * FROM product WHERE prod_id = '$prod_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
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
                    
                    <?php if (!empty($product)) : ?>
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Type</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Stock</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php htmlspecialchars($product['prod_id']) ?></td>
                                <td><?php htmlspecialchars($product['prod_name']) ?></td>
                                <td>
                                    <?php if (!empty($product['prod_image'])) : ?>
                                    <img src="assets/img<?php htmlspecialchars($product['prod_image']) ?>" 
                                         alt="<?php htmlspecialchars($product['prod_name']) ?>" 
                                         style="max-width: 80px; height: auto;">
                                    <?php else : ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['prod_type']) ?></td>
                                <td class="text-right">$<?php number_format($product['proprice'], 2) ?></td>
                                <td class="text-right"><?php $product['quantity'] ?></td>
                                <td class="text-right">
                                    <a href="edit-product.php?id=<?php $product['prod_id'] ?>" 
                                       class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete-product.php?id=<?php $product['prod_id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php else : ?>
                        <div class="alert alert-warning text-center">No product found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end product details section -->

<?php include 'footer.php'; ?>