<?php
// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "crud_brewingbliss";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "koneksi gagal bro" . $e->getMessage();
}
?>