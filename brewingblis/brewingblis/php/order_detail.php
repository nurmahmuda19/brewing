<?php
session_start();
include 'filekoneksi.php';

// Cek apakah pengguna adalah admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Pastikan ada parameter order_id di URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    echo "Order ID tidak ditemukan!";
    exit;
}

try {
    // Ambil detail pesanan berdasarkan order_id
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "Pesanan tidak ditemukan!";
        exit;
    }

    // Ambil detail produk dalam pesanan (pastikan kolom dan tabel sesuai)
    $stmt_products = $conn->prepare("SELECT p.name AS product_name, o.quantity, p.price 
                                    FROM order_items o
                                    JOIN products p ON o.product_id = p.id
                                    WHERE o.order_id = :order_id");
    $stmt_products->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt_products->execute();
    $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Kesalahan: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Brewing Bliss</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold">Brewing Bliss - Order Detail</div>
            <div class="header-buttons">
                <a href="admin_orders.php" class="px-4 py-2 bg-green-500 text-white rounded-md">Back to Orders</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Detail Pesanan #<?php echo $order['order_id']; ?></h1>

        <!-- Tampilkan informasi pesanan -->
        <div class="bg-white p-6 shadow-lg rounded-md mb-6">
            <p><strong>Nama Penerima:</strong> <?php echo $order['name']; ?></p>
            <p><strong>Alamat Penerima:</strong> <?php echo $order['address']; ?></p>
            <p><strong>Nomor Kontak:</strong> <?php echo $order['contact']; ?></p>
            <p><strong>Total Pesanan:</strong> Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
            <p><strong>Tanggal Pesanan:</strong> <?php echo $order['order_date']; ?></p>
        </div>

        <!-- Tampilkan daftar produk dalam pesanan -->
        <h2 class="text-2xl font-bold mb-4">Daftar Produk</h2>
        <?php if (empty($products)) { ?>
            <p>Tidak ada produk dalam pesanan ini.</p>
        <?php } else { ?>
            <table class="table-auto w-full bg-white shadow-lg rounded-md">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Nama Produk</th>
                        <th class="border px-4 py-2">Harga</th>
                        <th class="border px-4 py-2">Jumlah</th>
                        <th class="border px-4 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) { ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $product['product_name']; ?></td>
                            <td class="border px-4 py-2">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                            <td class="border px-4 py-2"><?php echo $product['quantity']; ?></td>
                            <td class="border px-4 py-2">Rp <?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </main>
</body>
</html>
