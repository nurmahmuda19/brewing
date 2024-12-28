<?php
session_start();
include 'filekoneksi.php';

// Cek apakah ada produk di keranjang
if (empty($_SESSION['cart'])) {
    header("Location: minum.php");
    exit;
}

// Menghitung total harga
$total_price = 0;
foreach ($_SESSION['cart'] as $product) {
    $total_price += $product['price'] * $product['quantity'];
}

// Jika formulir checkout sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir pengiriman
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $user_id = $_SESSION['user_id'];  // Pastikan ada user yang login

    try {
        // Simpan informasi pesanan ke tabel 'orders'
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, address, contact, total_amount, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $address, $contact, $total_price, 'Pending']); // Menggunakan execute dengan array

        // Ambil ID pesanan yang baru saja dimasukkan
        $order_id = $conn->lastInsertId();

        // Masukkan produk dari keranjang ke tabel 'order_items'
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $product['quantity'], $product['price']]); // Menggunakan execute dengan array
        }

        // Kosongkan keranjang setelah checkout berhasil
        $_SESSION['cart'] = [];

        // Redirect ke halaman admin_orders.php untuk melihat pesanan
        header('Location: admin_orders.php');
        exit;

    } catch (Exception $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Brewing Bliss</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold cursor-pointer">Brewing Bliss</div>
            <a href="minum.php" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Kembali ke Menu</a>
        </div>
    </header>

    <!-- Checkout Form -->
    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        <!-- Ringkasan Pesanan -->
        <h2 class="text-2xl font-bold mb-4">Ringkasan Pesanan</h2>
        <div class="cart-items space-y-6">
            <?php foreach ($_SESSION['cart'] as $product) { ?>
                <div class="cart-item bg-white p-4 shadow-lg rounded-lg flex justify-between">
                    <div>
                        <h3 class="text-xl"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-lg"><?php echo "Rp " . number_format($product['price'], 0, ',', '.'); ?></p>
                        <p>Jumlah: <?php echo $product['quantity']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Total Harga -->
        <div class="mt-6 flex justify-between items-center font-bold text-lg">
            <span>Total Harga:</span>
            <span><?php echo "Rp " . number_format($total_price, 0, ',', '.'); ?></span>
        </div>

        <!-- Formulir Pengiriman -->
        <h2 class="text-2xl font-bold mt-8 mb-4">Data Pengiriman</h2>
        <form action="checkout.php" method="POST" class="bg-white p-6 shadow-lg rounded-lg">
            <div class="mb-4">
                <label for="name" class="block text-lg">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-lg">Alamat Pengiriman</label>
                <textarea id="address" name="address" class="w-full p-2 border border-gray-300 rounded" rows="4" required></textarea>
            </div>
            <div class="mb-4">
                <label for="contact" class="block text-lg">Nomor Kontak</label>
                <input type="text" id="contact" name="contact" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                Konfirmasi Pesanan
            </button>
        </form>
    </main>

    <footer class="bg-[#8B4513] text-white shadow-md mt-16">
        <div class="container mx-auto py-4 text-center">
            ©️ 2024 Brewing Bliss
        </div>
    </footer>

</body>
</html>
