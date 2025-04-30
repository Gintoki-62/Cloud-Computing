<?php
ob_start();
include '.vscode/config.php';
include 'headerr.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($userQuery);

$address = !empty($user['address']) ? $user['address'] : '';

$cartQuery = mysqli_query($conn, "SELECT c.*, p.prod_name, p.prod_price, p.prod_image 
                                  FROM cart c 
                                  JOIN product p ON c.prod_id = p.prod_id 
                                  WHERE c.user_id = '$user_id'");
$cartItems = [];
$subtotal = 0;
while ($row = mysqli_fetch_assoc($cartQuery)) {
    $totalPrice = $row['prod_price'] * $row['quantity'];
    $subtotal += $totalPrice;
    $row['total'] = $totalPrice;
    $cartItems[] = $row;
}
$shipping = 5.90;
$grandTotal = $subtotal + $shipping;
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');

    body {
        font-family: 'Open Sans', sans-serif;
        background-color: #fff7f0;
        margin: 0;
        padding: 0;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .checkout-section {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
    }

    .checkout-left,
    .checkout-right {
        flex: 1;
        background-color: #fff3e6;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    h2 {
        color: #b85c38;
        margin-bottom: 30px;
    }

    h4 {
        color: #b85c38;
        margin-bottom: 10px;
    }

    .checkout-input {
        width: 100%;
        padding: 12px 15px;
        margin: 12px 0;
        border: 1px solid #e0c3a3;
        border-radius: 8px;
        background-color: #fffefb;
    }

    .checkout-summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .checkout-summary-table th,
    .checkout-summary-table td {
        padding: 12px;
        border-bottom: 1px solid #e7d5c0;
        vertical-align: middle;
    }

    .checkout-summary-table th {
        background-color: #ffe5cc;
        color: #8c3f2c;
        text-align: left;
    }

    .checkout-summary-table tr:last-child td {
        border-bottom: none;
    }

    .checkout-button {
        background: #d9744d;
        color: white;
        padding: 15px;
        border: none;
        border-radius: 8px;
        width: 100%;
        margin-top: 30px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .checkout-button:hover {
        background: #b85c38;
    }

    .productImage {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 10px;
        vertical-align: middle;
    }

    .checkout-left h4:nth-of-type(2),
    .checkout-left h4:nth-of-type(3) {
        margin-top: 30px;
    }

    .checkout-section {
        align-items: flex-start;
    }

    .checkout-right {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
</style>

<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Purchase Your Favorite Product</p>
                    <h1>Place Order</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="checkout-container">
    <h2>Checkout</h2>
    <?php if (empty($cartItems)): ?>
        <p>There is nothing in your cart. Press the button to start your shopping.</p>
        <a href="product.php">
            <button class="checkout-button" type="button">Go to Shopping</button>
        </a>
    <?php else: ?>
    <form method="post" onsubmit="return validateCheckout()">
        <div class="checkout-section">
            <div class="checkout-left">
                <h4>Personal Information</h4>
                <input class="checkout-input" type="text" name="name" value="<?= htmlspecialchars($user['username']) ?>" readonly required>
                <input class="checkout-input" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly required>
                <input class="checkout-input" type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" readonly required>

                <h4>Shipping Address</h4>
                <input class="checkout-input" type="text" name="address" placeholder="Full Address" value="<?= htmlspecialchars($address) ?>" readonly required>

                <h4>Card Details</h4>
                <input class="checkout-input" type="text" name="card_number" placeholder="Card Number" maxlength="16" required>
                <input class="checkout-input" type="text" name="card_name" placeholder="Cardholder Name" required>
                <input class="checkout-input" type="text" name="expiry" placeholder="MM/YY" required>
                <input class="checkout-input" type="text" name="cvv" placeholder="CVV" maxlength="4" required>
            </div>

            <div class="checkout-right">
                <h4>Order Summary</h4>
                <table class="checkout-summary-table">
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <img class="productImage" src="<?php echo $item['prod_image']; ?>" alt="">
                                    <?= $item['prod_name'] ?>
                                </div>
                            </td>
                            <td style="text-align: center;"><?= $item['quantity'] ?></td>
                            <td style="text-align: right;">RM <?= number_format($item['prod_price'], 2) ?></td>
                            <td style="text-align: right;">RM <?= number_format($item['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">Subtotal</td>
                        <td style="text-align: right;">RM <?= number_format($subtotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">Shipping</td>
                        <td style="text-align: right;">RM <?= number_format($shipping, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Grand Total</strong></td>
                        <td style="text-align: right;"><strong>RM <?= number_format($grandTotal, 2) ?></strong></td>
                    </tr>
                </table>

                <button class="checkout-button" type="submit" name="place_order">Place Order</button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
    function validateCheckout() {
        const cardNumber = document.getElementsByName("card_number")[0].value;
        const expiry = document.getElementsByName("expiry")[0].value;
        const cvv = document.getElementsByName("cvv")[0].value;

        if (!/^\d{16}$/.test(cardNumber)) {
            alert("Card number must be 16 digits.");
            return false;
        }
        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            alert("Expiry must be in MM/YY format.");
            return false;
        }
        if (!/^\d{3,4}$/.test(cvv)) {
            alert("CVV must be 3 or 4 digits.");
            return false;
        }
        return true;
    }
</script>

<?php
if (isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $orderQuery = "INSERT INTO orders (user_id, address, total_amount) 
                   VALUES ('$user_id', '$address', '$grandTotal')";
    if (mysqli_query($conn, $orderQuery)) {
        $orderID = mysqli_insert_id($conn);

        foreach ($cartItems as $item) {
            $productID = $item['prod_id'];
            $quantity = $item['quantity'];
            $price = $item['prod_price'];
            $total = $item['total'];

            mysqli_query($conn, "INSERT INTO order_items (order_id, prod_id, quantity, Unit_price) 
                                 VALUES ('$orderID', '$productID', '$quantity', '$price')");
        }

        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
        echo "<script>alert('Order placed successfully!'); window.location='payment_successful.php?order_id=$orderID';</script>";
    } else {
        echo "<script>alert('Failed to place order.');</script>";
    }
}
?>

<?php ob_end_flush(); ?>
