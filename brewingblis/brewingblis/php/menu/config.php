<?php
// Koneksi ke database
$host = 'localhost'; // Nama host
$username = 'root';  // Username MySQL
$password = '';      // Password MySQL (kosong jika default XAMPP)
$dbname = 'brewing_bliss'; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "Koneksi berhasil!";
?>
