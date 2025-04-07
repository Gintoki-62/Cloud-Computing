<?php
$host = "localhost";
$username = "root";
$password = ""; // XAMPP 默认没有密码
$database = "clouddb";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection Error:" . mysqli_connect_error());
}
?>
