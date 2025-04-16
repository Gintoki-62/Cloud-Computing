<?php
include 'header.php';
include '.vscode/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Establish database connection
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input data
    $prod_name = mysqli_real_escape_string($conn, trim($_POST['prod_name']));
    $prod_type = mysqli_real_escape_string($conn, trim($_POST['prod_type']));
    $prod_price = floatval($_POST['prod_price']);
    $prod_quantity = intval($_POST['prod_quantity']);
    $prod_image = '';

    // Handle image upload
    if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "assets/img/";
        $file_extension = pathinfo($_FILES['prod_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $filename;

        // Validate image file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES['prod_image']['tmp_name'], $target_file)) {
                $prod_image = $target_file;
            } else {
                $error = "Error uploading image file.";
            }
        } else {
            $error = "Invalid file type. Only JPG, JPEG, PNG & GIF allowed.";
        }
    }

    if (empty($error)) {
        // Insert product into database
        $sql = "INSERT INTO product (prod_name, prod_type, prod_price, prod_quantity, prod_image)
                VALUES ('$prod_name', '$prod_type', $prod_price, $prod_quantity, '$prod_image')";

        if (mysqli_query($conn, $sql)) {
            $success = "Product added successfully!";
            // Clear form fields
            $prod_name = $prod_type = $prod_price = $prod_quantity = '';
        } else {
            $error = "Error: " . mysqli_error($conn);
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
<!-- end breadcrumb section -->

<div class="mt-150 mb-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="order-details-wrap">
                    <h3 class="mb-4">ADD PRODUCT</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="prod_name" class="form-control" required 
                                   value="<?php echo isset($prod_name) ? $prod_name : '' ?>">
                        </div>

                        <div class="form-group">
                            <label>Product Type</label>
                            <input type="text" name="prod_type" class="form-control" required
                                   value="<?php echo isset($prod_type) ? $prod_type : '' ?>">
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.01" name="prod_price" class="form-control" required
                                   value="<?php echo isset($prod_price) ? $prod_price : '' ?>">
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="prod_quantity" class="form-control" required
                                   value="<?php echo isset($prod_quantity) ? $prod_quantity : '' ?>">
                        </div>

                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="prod_image" class="form-control-file" accept="image/*">
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Add Product</button>
                            <a href="products.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>