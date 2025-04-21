<?php
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

include 'header.php';
include '.vscode/config.php'; 

$con = new mysqli($host, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

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

<!-- Page Content -->
 <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Management</title>
         <!-- Include Bootstrap CSS -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
         <!-- Include Bootstrap Icons -->
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
         <!-- Custom CSS -->
         <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
    </head>

    
    