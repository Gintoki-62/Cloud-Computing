<?php
include '.vscode/config.php';

// Establish database connection
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if product ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $prod_id = $con->real_escape_string($_GET['id']);
    
    // First, get the product details
    $sql_select = "SELECT * FROM product WHERE prod_id = '$prod_id'";
    $result = $con->query($sql_select);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Check if the form was submitted to confirm deletion
        if (isset($_POST['confirm_delete'])) {
            // Delete the product from database
            $sql_delete = "DELETE FROM product WHERE prod_id = '$prod_id'";
            
            if ($con->query($sql_delete)) {
                // If deletion was successful, delete the associated image file
                if (!empty($product['prod_image'])) {
                    $image_file = basename($product['prod_image']);
                    $full_image_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/' . $image_file;
                    
                    if (file_exists($full_image_path)) {
                        unlink($full_image_path); // Delete the image file
                    }
                }
                
                // Redirect back with success message
                header("Location: admin-product.php?delete_success=1");
                $con->close();
                exit();
            } else {
                // Database error
                $error = "Error deleting product: " . $con->error;
            }
        }
        
        // Display confirmation page
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirm Product Deletion</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .product-info {
                    border: 1px solid #ddd;
                    padding: 20px;
                    margin-bottom: 20px;
                    border-radius: 5px;
                }
                .product-image {
                    max-width: 200px;
                    max-height: 200px;
                }
                .buttons {
                    margin-top: 20px;
                }
                .btn {
                    padding: 8px 15px;
                    margin-right: 10px;
                    text-decoration: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                .btn-delete {
                    background-color: #dc3545;
                    color: white;
                    border: none;
                }
                .btn-cancel {
                    background-color: #6c757d;
                    color: white;
                    border: none;
                }
            </style>
        </head>
        <body>
            <h1>Confirm Product Deletion</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['prod_name']); ?></h2>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($product['prod_id']); ?></p>
                <p><strong>Price:</strong> RM<?php echo htmlspecialchars($product['prod_price']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($product['prod_type']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($product['prod_quantity']); ?></p>
                
                <?php if (!empty($product['prod_image'])): ?>
                    <p><strong>Image:</strong></p>
                    <img src="<?php echo htmlspecialchars($product['prod_image']); ?>" alt="Product Image" class="product-image">
                <?php endif; ?>
            </div>
            
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            
            <form method="post" class="buttons">
                <button type="submit" name="confirm_delete" class="btn btn-delete">Confirm Delete</button>
                <a href="admin-product.php" class="btn btn-cancel">Cancel</a>
            </form>
        </body>
        </html>
        <?php
        $con->close();
        exit();
        
    } else {
        // Product not found
        $con->close();
        header("Location: admin-product.php?error=Product not found");
        exit();
    }
} else {
    // No ID provided
    $con->close();
    header("Location: admin-product.php?error=No product specified");
    exit();
}
?>