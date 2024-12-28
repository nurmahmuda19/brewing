<?php
session_start();
include 'filekoneksi.php';

// Cek apakah pengguna adalah admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Cek apakah order_id ada di URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    echo "Order ID tidak ditemukan!";
    exit;
}

try {
    // Ambil pesanan berdasarkan order_id
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Pastikan pesanan ditemukan
    if (!$order) {
        echo "Pesanan tidak ditemukan!";
        exit;
    }

    // Jika formulir disubmit, update status
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
        // Ambil status baru dari formulir
        $new_status = $_POST['status'];

        // Validasi status
        $valid_status = ['pending', 'in_progress', 'shipped', 'delivered', 'completed']; // Status baru 'completed' ditambahkan
        if (!in_array($new_status, $valid_status)) {
            echo "Status yang dipilih tidak valid!";
            exit;
        }

        // Update status pesanan
        $update_stmt = $conn->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
        $update_stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
        $update_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        
        $result = $update_stmt->execute(); // Eksekusi query update

        if ($result) {
            // Redirect ke halaman admin orders setelah berhasil
            header('Location: admin_orders.php');
            exit;
        } else {
            echo "Terjadi kesalahan saat mengupdate status!";
        }
    }
} catch (PDOException $e) {
    echo "Kesalahan: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status - Brewing Bliss</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold">Brewing Bliss</div>
            <div class="header-buttons">
                <a href="admin_orders.php" class="px-4 py-2 bg-blue-500 text-white rounded-md">Admin Orders</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Update Status Pesanan</h1>

        <form method="post" class="bg-white p-6 shadow-lg rounded-md">
            <label for="status" class="block text-xl font-medium mb-2">Pilih Status</label>
            <select name="status" id="status" class="block w-full p-2 border rounded-md">
                <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="in_progress" <?php if ($order['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                <option value="shipped" <?php if ($order['status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                <option value="delivered" <?php if ($order['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                <option value="completed" <?php if ($order['status'] == 'completed') echo 'selected'; ?>>Completed</option> <!-- Status Completed -->
            </select>

            <button type="submit" name="update_status" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-md">Update Status</button>
        </form>
    </main>
</body>
</html>
