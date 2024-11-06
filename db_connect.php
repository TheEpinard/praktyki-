<?php
// db_connect.php
$host = 'localhost';
$dbname = 'lotto';
$username = 'root'; 
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Błąd połączenia: ' . $e->getMessage();
    exit();
}
?>