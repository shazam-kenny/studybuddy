<?php
session_start();
session_unset();
session_destroy();
header('Location: /club_restaurant_system/pages/login.php');
exit;
<?php include('../includes/navbar.php'); ?>
