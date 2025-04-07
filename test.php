<!DOCTYPE html>
<body>
    <?php
    include 'config.php'; // 引入数据库连接

    // 示例查询
    $result = mysqli_query($conn, "SELECT * FROM product");

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['prod_name'] . "<br>";
    }
    ?>

    <h1>fffffffffffffffffjjjjjjllllll</h1>

</body>
