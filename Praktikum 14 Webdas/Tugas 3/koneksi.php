<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_webdasar";

$conn_temp = new mysqli($host, $user, $pass);
$conn_temp->query("CREATE DATABASE IF NOT EXISTS $db");
$conn_temp->select_db($db);

$conn_temp->query("CREATE TABLE IF NOT EXISTS buku_tamu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    asal VARCHAR(100) NOT NULL,
    komentar TEXT NOT NULL,
    waktu DATETIME DEFAULT CURRENT_TIMESTAMP
)");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi PDO Gagal: " . $e->getMessage());
}

$conn = $conn_temp; 
?>