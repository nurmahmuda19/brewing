<?php
session_start();
include 'filekoneksi.php'; // Pastikan ini adalah koneksi PDO

// Cek apakah pengguna sudah login dan memiliki hak akses yang benar
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Menghapus produk berdasarkan ID dengan menggunakan PDO
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Jika produk berhasil dihapus dari database, hapus produk dari keranjang
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);  // Menghapus produk dari keranjang
            }

            // Redirect ke halaman utama dengan parameter notifikasi
            header("Location: minum.php?deleted=true");
            exit;
        } else {
            // Jika gagal menghapus produk dari database
            header("Location: minum.php?deleted=false");
            exit;
        }
    } catch (PDOException $e) {
        // Jika error terjadi, arahkan ke halaman utama dengan pesan error
        header("Location: minum.php?deleted=false");
        exit;
    }
} else {
    // Jika ID tidak ditemukan
    header("Location: minum.php?deleted=false");
    exit;
}
