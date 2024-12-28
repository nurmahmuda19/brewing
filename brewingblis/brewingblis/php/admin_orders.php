<?php
session_start();
include 'filekoneksi.php';

// Cek apakah pengguna adalah admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

try {
    // Ambil daftar semua pesanan
    $stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_id DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Kesalahan: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders - Brewing Bliss</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold">Brewing Bliss - Admin Orders</div>
            <div class="header-buttons">
                <a href="minum.php" class="px-4 py-2 bg-green-500 text-white rounded-md">Dashboard</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Daftar Pesanan</h1>
        
        <table class="table-auto w-full bg-white shadow-lg rounded-md">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Order ID</th>
                    <th class="border px-4 py-2">User ID</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Action</th>
                    <th class="border px-4 py-2">Detail Pesanan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $order['order_id']; ?></td>
                        <td class="border px-4 py-2"><?php echo $order['user_id']; ?></td>
                        <td class="border px-4 py-2">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                        <td class="border px-4 py-2"><?php echo ucfirst($order['status']); ?></td>
                        <td class="border px-4 py-2">
                            <a href="update_status.php?order_id=<?php echo $order['order_id']; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-md">Update Status</a>
                        </td>
                        <td class="border px-4 py-2">
                            <a href="order_detail.php?order_id=<?php echo $order['order_id']; ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-md">Detail</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>
</html>
