<?php
session_start();
include 'filekoneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Ambil status pesanan berdasarkan user_id
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id DESC");
    $stmt->bindParam(':user_id', $user_id);
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
    <title>Status Pesanan - Brewing Bliss</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold">Brewing Bliss</div>
            <div class="header-buttons">
                <a href="menu.php" class="px-4 py-2 bg-blue-500 text-white rounded-md">Menu</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Status Pesanan Anda</h1>

        <table class="table-auto w-full bg-white shadow-lg rounded-md">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Order ID</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $order['order_id']; ?></td>
                        <td class="border px-4 py-2">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                        <td class="border px-4 py-2"><?php echo ucfirst($order['status']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>
</html>
