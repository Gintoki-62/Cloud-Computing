<!DOCTYPE html>
<html>
    <body>
        <?php
        include 'header.php';
        include '.vscode/config.php';

        $searchResults = [];

        if (isset($_GET['keyword'])) {
            $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
            $query = "SELECT * FROM product WHERE prod_name LIKE '%$keyword%' OR prod_type LIKE '%$keyword%'";
            $result = mysqli_query($conn, $query);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row;
                }
            }
        }
        ?>

<!-- Display Search Results -->
            <?php if (isset($_GET['keyword'])): ?>
                <!-- breadcrumb-section -->
            <div class="breadcrumb-section breadcrumb-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 text-center">
                            <div class="breadcrumb-text">
                            <p>Search Result for : <em> <?php echo htmlspecialchars($_GET['keyword']); ?></em></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end breadcrumb section -->
            <div class="container mt-5">
                <?php if (count($searchResults) > 0): ?>
                    <div class="row product-lists">
                        <?php foreach ($searchResults as $row): ?>
                            <div class="col-lg-4 col-md-6 text-center <?php echo $row['prod_type']; ?>">
                                <div class="single-product-item">
                                    <div class="product-image">
                                        <a href="single-product.html">
                                            <img src="assets/img/products/<?php echo $row['prod_image']; ?>" alt="">
                                        </a>
                                    </div>
                                    <h3><?php echo $row['prod_name']; ?></h3>
                                    <p class="product-price"><span><?php echo $row['prod_price']; ?></span> 85$</p>
                                    <a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
       
                                    <div class="breadcrumb-text">
                                    <p>No Products found matching your search.</p>
                                    </div>
                                    <br/><br/><br/>
      
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php include 'footer.php'; ?>
    </body>
</html>
