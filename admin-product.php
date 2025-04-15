<?php
session_start();
include '.vscode/config.php';
include 'header.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}

$addmessage = '';
$addmessageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    try {
        // Validate inputs
        $prod_name = htmlspecialchars(trim($_POST['prod_name']));
        $prod_description = htmlspecialchars(trim($_POST['prod_description']));
        $prod_price = filter_input(INPUT_POST, 'prod_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $prod_quantity = filter_input(INPUT_POST, 'prod_quantity', FILTER_SANITIZE_NUMBER_INT);
        $prod_type = htmlspecialchars(trim($_POST['prod_type']));
        
        // Handle file upload
        $imagePath = '';
        if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $detectedType = mime_content_type($_FILES['prod_image']['tmp_name']);
            
            if (!in_array($detectedType, haystack: $allowedTypes)) {
                throw new Exception('Only JPG, PNG, and GIF files are allowed');
            }

            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filename = uniqid() . '_' . basename($_FILES['prod_image']['name']);
            $targetPath = $uploadDir . $filename;

            if (!move_uploaded_file($_FILES['prod_image']['tmp_name'], $targetPath)) {
                throw new Exception('Failed to upload image');
            }
            
            $imagePath = $targetPath;
        }

        // Basic validation
        $requiredFields = [
            'Product Name' => $prod_name,
            'Price' => $prod_price,
            'Quantity' => $prod_quantity,
            'Type' => $prod_type
        ];

        foreach ($requiredFields as $field => $value) {
            if (empty($value)) {
                throw new Exception("$field is a required field");
            }
        }

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO products 
                            (name, description, price, quantity, type, image, created_at) 
                            VALUES (:name, :description, :price, :quantity, :type, :image, NOW())");
        
        $stmt->execute([
            ':name' => $prod_name,
            ':description' => $prod_description,
            ':price' => $prod_price,
            ':quantity' => $prod_quantity,
            ':type' => $prod_type,
            ':image' => $imagePath
        ]);

        $addmessage = 'Product added successfully!';
        $addmessageClass = 'success';
        
        // Clear form inputs
        $_POST = array();

    } catch (Exception $e) {
        $addmessage = 'Error: ' . $e->getMessage();
        $addmessageClass = 'error';
    }
}
?>

<!-- HTML Form -->
<div class="container">
    <h2>Add New Product</h2>
    
    <?php if ($addmessage): ?>
        <div class="<?= $addmessageClass ?>"><?= $addmessage ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" name="prod_name" required value="<?= $_POST['prod_name'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="prod_description"><?= $_POST['prod_description'] ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Price:</label>
            <input type="number" step="0.01" name="prod_price" required value="<?= $_POST['prod_price'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="prod_quantity" required value="<?= $_POST['prod_quantity'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label>Product Type:</label>
            <select name="prod_type" required>
                <option value="">Select Type</option>
                <option value="Electronics" <?= ($_POST['prod_type'] ?? '') === 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                <option value="Clothing" <?= ($_POST['prod_type'] ?? '') === 'Clothing' ? 'selected' : '' ?>>Clothing</option>
                <option value="Books" <?= ($_POST['prod_type'] ?? '') === 'Books' ? 'selected' : '' ?>>Books</option>
            </select>
        </div>

        <div class="form-group">
            <label>Product Image:</label>
            <input type="file" name="prod_image" accept="image/*">
        </div>

        <button type="submit" name="submit">Add Product</button>
    </form>
</div>

<?php include 'footer.php'; ?>