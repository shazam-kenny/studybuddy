<?php
// config.php
$host = "localhost";
$user = "root";
$pass = "";           // default XAMPP local password is empty
$dbname = "club_restaurant_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "DB connected"; // uncomment to test, then remove