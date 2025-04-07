<!DOCTYPE html>
<body>
    <?php
    include 'config.php';

    // 示例查询
    $result = mysqli_query($conn, "SELECT * FROM product");

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['prod_name'] . "<br>";
    }
    ?>

    <h1>lajiddd</h1>

</body>
