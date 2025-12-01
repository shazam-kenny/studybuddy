<?php
session_start();
session_destroy(); // Destroy session
header("Location: index.php"); // Go back to login
?>