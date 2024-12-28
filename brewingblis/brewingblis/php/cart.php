<?php
session_start();

// Menangani penghapusan produk dari keranjang
if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];
    unset($_SESSION['cart'][$remove_id]);
    $message = "Produk berhasil dihapus dari keranjang.";
    $show_popup = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Brewing Bliss</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Floating notification style */
        .popup-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .popup-message.show {
            display: block;
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold cursor-pointer">Brewing Bliss</div>
            <a href="minum.php" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Kembali ke Menu</a>
        </div>
    </header>

    <!-- Keranjang -->
    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Keranjang Anda</h1>

        <!-- Notifikasi Pop-up -->
        <?php if (isset($message) && $show_popup): ?>
            <div class="popup-message show">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <script>
                // Menyembunyikan pop-up setelah 5 detik
                setTimeout(function() {
                    document.querySelector('.popup-message').classList.remove('show');
                }, 5000);
            </script>
        <?php endif; ?>

        <!-- Daftar Produk di Keranjang -->
        <div class="cart-items space-y-6">
            <?php if (empty($_SESSION['cart'])) { ?>
                <p>Keranjang Anda kosong.</p>
            <?php } else { ?>
                <?php foreach ($_SESSION['cart'] as $product_id => $product) { ?>
                    <div class="cart-item bg-white p-4 shadow-lg rounded-lg flex justify-between">
                        <div>
                            <h3 class="text-xl"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="text-lg"><?php echo "Rp " . number_format($product['price'], 0, ',', '.'); ?></p>
                            <p>Jumlah: <?php echo $product['quantity']; ?></p>
                        </div>
                        <div>
                            <a href="cart.php?remove_id=<?php echo $product_id; ?>" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">Hapus</a>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <div class="mt-6">
            <a href="checkout.php" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Checkout</a>
        </div>
    </main>

    <footer class="bg-[#8B4513] text-white shadow-md mt-16">
        <div class="container mx-auto py-4 text-center">
            ©️ 2024 Brewing Bliss
        </div>
    </footer>

</body>

</html>
