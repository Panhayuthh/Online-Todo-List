<?php
    $hostname = 'localhost';
    $dbname = 'todo_list_db';
    $username = 'root';
    $password = 'root';

    try {
        $dsn = "mysql:host=$hostname;dbname=$dbname";
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
        exit();    
    }
?>
