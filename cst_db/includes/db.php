<?php include "includes/navbar.php"; ?>
<?php
// includes/db.php
require_once __DIR__ . '/../config/config.php';

function getDb() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("DB connect error: " . $e->getMessage());
        }
    }
    return $pdo;
}