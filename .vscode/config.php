<?php
$host = "localhost";
$username = "root";
$password = ""; 
$database = "clouddb";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection Error:" . mysqli_connect_error());
}
?>
