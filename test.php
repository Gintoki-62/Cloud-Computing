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

    <h1>lajiddd垃圾</h1>

</body>
