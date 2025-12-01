<?php
$servername = "localhost";
$username = "root";       // Default XAMPP username
$password = "";           // Default XAMPP password is empty
$dbname = "study buddy";  // The name of the database you created in phpMyAdmin

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>