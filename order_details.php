<?php
ob_start();
date_default_timezone_set('Asia/Kuala_Lumpur');
include '.vscode/config.php';
include 'headerr.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    header("Location: order_history.php");
    exit();
}

$user_stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

$order_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND order_id = ?");
$order_stmt->bind_param("ii", $user_id, $order_id);
$order_stmt->execute();
$order = $order_stmt->get_result()->fetch_assoc();

$orderItemsQuery = mysqli_query($conn, "SELECT o.prod_id, o.quantity, o.unit_price, p.prod_name, p.prod_image 
                    FROM order_items o 
                    JOIN product p ON o.prod_id = p.prod_id
                    WHERE o.order_id = '$order_id'");
$orderItems = [];
$subtotal = 0;
while ($row = mysqli_fetch_assoc($orderItemsQuery)) {
    $totalPrice = $row['unit_price'] * $row['quantity'];
    $subtotal += $totalPrice;
    $row['total'] = $totalPrice;
    $orderItems[] = $row;
}
$shipping = 5.90;

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
        height: 100%;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        align-items: stretch;
    }

    .checkout-section {
        display: flex;
        height: 100%;
        flex-wrap: wrap;
        gap: 40px;
        align-items: stretch;
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

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #5483B3;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: #052659;
        transform: translateX(-3px);
    }

    .order-status-container {
        background: #fff3e6;
        border-radius: 12px;
        padding: 2.5rem;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .order-status-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, rgb(255, 98, 1), rgb(247, 176, 125));
    }

    .order-status {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .order-status li {
        list-style: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .order-status li .icon {
        font-size: 2.2rem;
        color: #052659;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .order-status li .text {
        font-size: 1rem;
        font-weight: 500;
        color: #052659;
        text-align: center;
        max-width: 120px;
    }

    .order-status li .progress {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgb(246, 195, 144);
        margin: 1.5rem 0;
        display: grid;
        place-items: center;
        color: white;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .progress::after {
        content: " ";
        position: absolute;
        width: calc(100% + 140px);
        height: 4px;
        background-color: rgb(241, 129, 16);
        right: 50px;
        top: 50%;
        transform: translateY(-50%);
        z-index: -1;
        transition: all 0.3s ease;
    }

    .one::after {
        width: 0;
        height: 0;
    }

    .order-status li .progress p {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .uil-check:before {
        content: "✓";
    }

    .uil-arrow-left:before {
        content: "←";
    }

    .uil-file-check-alt:before {
        content: "📄";
    }

    .uil-box:before {
        content: "📦";
    }

    .uil-truck:before {
        content: "🚚";
    }

    .uil-map-marker:before {
        content: "📍";
    }

    .uil-check-circle:before {
        content: "✓";
    }

    .order-status li .active {
        background-color: rgb(241, 129, 16);
        transform: scale(1.1);
    }

    .order-status li .active::after {
        background-color: #5483B3;
    }

    .order-status li .active p {
        display: none;
    }

    .order-status li .active .icon {
        display: flex;
    }

    .order-status li .active~li .progress::after {
        background-color: #e2e8f0;
    }

    @media (max-width: 980px) {
        .order-status {
            flex-direction: column;
            align-items: flex-start;
            padding-left: 2rem;
        }

        .order-status li {
            flex-direction: row;
            align-items: center;
            margin-bottom: 2rem;
            width: 100%;
        }

        .order-status li .progress {
            margin: 0 1.5rem;
        }

        .progress::after {
            width: 4px;
            height: 60px;
            bottom: 50px;
            left: 25px;
            top: auto;
            transform: none;
        }

        .one::after {
            height: 0;
        }

        .order-status li .icon {
            margin: 0;
            order: 3;
            margin-left: auto;
        }

        .order-status li .text {
            order: 4;
            margin-left: 1rem;
            text-align: left;
            max-width: none;
        }
    }

    @media (max-width: 768px) {
        .order-status-container {
            padding: 1.5rem;
        }

        .order-item {
            flex-direction: column;
        }

        .order-item-thumbnail {
            width: 100%;
            height: auto;
            aspect-ratio: 1/1;
        }
    }
</style>
<div><div class="breadcrumb-section breadcrumb-bg">
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
<div class="order-title" style="width: 100%; text-align: center; margin-top: 50px;">
    <h2>Order #<?php echo $order_id ?></h2>
    <h4>Place on <?php echo $order['order_date'] ?></h4>
</div>

<div class="checkout-container">
    <div class="order-status-container">
        <ul class="order-status">
            <li>
                <span class="icon uil uil-file-check-alt"></span>
                <div class="progress one active">
                    <p>1</p>
                    <span class="uil uil-check"></span>
                </div>
                <p class="text">Order Placed</p>
            </li>

            <li>
                <span class="icon uil uil-box"></span>
                <div class="progress two">
                    <p>2</p>
                    <span class="uil uil-check"></span>
                </div>
                <p class="text">Preparing Order</p>
            </li>

            <li>
                <span class="icon uil uil-truck"></span>
                <div class="progress three">
                    <p>3</p>
                    <span class="uil uil-check"></span>
                </div>
                <p class="text">In Transit</p>
            </li>

            <li>
                <span class="icon uil uil-map-marker"></span>
                <div class="progress four">
                    <p>4</p>
                    <span class="uil uil-check"></span>
                </div>
                <p class="text">Out for Delivery</p>
            </li>

            <li>
                <span class="icon uil uil-check-circle"></span>
                <div class="progress five">
                    <p>5</p>
                    <span class="uil uil-check"></span>
                </div>
                <p class="text">Delivered</p>
            </li>
        </ul>
    </div>
    <div class="checkout-section">
        <div class="checkout-left">
            <h4>Personal Information</h4>
            <input class="checkout-input" type="text" name="name" value="<?= htmlspecialchars($user['username']) ?>"
                readonly>
            <input class="checkout-input" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
                readonly>
            <input class="checkout-input" type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"
                readonly>

            <h4>Shipping Info</h4>
            <input class="checkout-input" type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>"
                readonly>
        </div>

        <div class="checkout-right">
            <h4>Order Summary</h4>
            <table class="checkout-summary-table">
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <img class="productImage" src="<?php echo $item['prod_image']; ?>" alt="">
                                <?= $item['prod_name'] ?>
                            </div>
                        </td>
                        <td>RM <?php echo number_format($item['unit_price'], 2); ?></td>
                        <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                        <td style="text-align: right;">RM <?php echo number_format($item['total'], 2); ?></td>
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
                    <td style="text-align: right;"><strong>RM <?= number_format($order['total_amount'], 2) ?></strong>
                    </td>
                </tr>
            </table>

        </div>
        <button type="button" class="checkout-button" onclick="window.location.href='order_history.php'">Back</button>
    </div>
</div></div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusElements = {
            one: document.querySelector(".one"),
            two: document.querySelector(".two"),
            three: document.querySelector(".three"),
            four: document.querySelector(".four"),
            five: document.querySelector(".five")
        };

        const paymentTimestamp = <?php echo strtotime($order['order_date']) * 1000; ?>;

        function checkPaymentStatus() {
            const currentTimestamp = new Date().getTime();
            const timeDifference = (currentTimestamp - paymentTimestamp) / 1000;

            console.log("Time difference:", timeDifference);

            Object.values(statusElements).forEach(el => el.classList.remove('active'));

            if (timeDifference > 50) {
                statusElements['five'].classList.add('active');
                statusElements['four'].classList.add('active');
                statusElements['three'].classList.add('active');
                statusElements['two'].classList.add('active');
                statusElements['one'].classList.add('active');
            } else if (timeDifference > 40) {
                statusElements['four'].classList.add('active');
                statusElements['three'].classList.add('active');
                statusElements['two'].classList.add('active');
                statusElements['one'].classList.add('active');
            } else if (timeDifference > 30) {
                statusElements['three'].classList.add('active');
                statusElements['two'].classList.add('active');
                statusElements['one'].classList.add('active');
            } else if (timeDifference > 20) {
                statusElements['two'].classList.add('active');
                statusElements['one'].classList.add('active');
            } else if (timeDifference > 2) {
                statusElements['one'].classList.add('active');
            }
        }

        checkPaymentStatus();
        setInterval(checkPaymentStatus, 2000);
    });

</script>




<?php include 'footer.php'; ?>


<?php ob_end_flush(); ?>