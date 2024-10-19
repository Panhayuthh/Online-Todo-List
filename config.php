<?php
try {
    $dsn = "mysql:host=localhost;dbname=management_system";
    $kunci = new PDO($dsn, "root", "Mysql");
    $kunci->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();  // Stop script execution if the connection fails
}
?>
