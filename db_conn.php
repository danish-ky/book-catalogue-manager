<?php
// Database configuration
$sName = "localhost";
$uName = "root";
$pass = "";
$db_name = "book_catalogue_manager";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Verify required tables exist
    $requiredTables = ['admin', 'books', 'authors', 'categories'];
    foreach ($requiredTables as $table) {
        $conn->query("SELECT 1 FROM $table LIMIT 1");
    }
} catch (PDOException $e) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ]));
}
?>