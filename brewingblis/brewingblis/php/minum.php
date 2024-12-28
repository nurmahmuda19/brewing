<?php
session_start();
include 'filekoneksi.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$host = "localhost"; 
$user = "root";      
$password = "";      
$dbname = "crud_brewingbliss"; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql_check_column = "SHOW COLUMNS FROM products LIKE 'uploaded_at'";
$result_check = $conn->query($sql_check_column);

if ($result_check->num_rows == 0) {
    $sql = "SELECT * FROM products ORDER BY file_name DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY uploaded_at DESC";
}

$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error);
}

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Menangani tombol "Tambah Keranjang"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Tambahkan produk ke dalam keranjang
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    }

    // Pesan sukses
    $success_message = "{$product_name} berhasil ditambahkan ke keranjang.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minuman - Brewing Bliss</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

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

        /* Custom styles for the header buttons */
        .header-buttons a {
            margin-right: 15px;
            padding: 10px 15px;
            background-color: #8B4513;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .header-buttons a:hover {
            background-color: #6A3E1F;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold cursor-pointer">Brewing Bliss</div>

            <!-- Header navigation buttons -->
            <div class="header-buttons">
                <a href="homi.html">Home</a>
                <a href="menu.html">Menu</a>
                <a href="admin_orders.php">Lihat Status Pesanan</a> <!-- Tombol Lihat Status Pesanan -->
            </div>
        </div>
    </header>

    <!-- Search Bar -->
    <div class="container mx-auto mt-6 px-6 flex items-center">
        <div class="relative bg-white border rounded-lg p-4 flex-grow">
            <input type="text" id="searchBar" placeholder="Search Menu" class="w-full border-none outline-none text-lg p-2" oninput="filterMenu()">
        </div>
    </div>

    <!-- Minuman Section -->
    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Minuman</h1>

        <?php if (isset($success_message)) { ?>
            <div class="popup-message show">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <script>
                // Sembunyikan pop-up setelah 5 detik
                setTimeout(function() {
                    document.querySelector('.popup-message').classList.remove('show');
                }, 5000);
            </script>
        <?php } ?>

        <div class="minuman grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productList">
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="menu-item bg-white shadow-lg rounded-lg p-4">
                        <img src="<?php echo htmlspecialchars($row['file_path']); ?>" alt="<?php echo htmlspecialchars($row['file_name']); ?>" class="w-36 h-36 mx-auto rounded-lg mb-4">
                        <h3 class="font-bold text-xl"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="text-gray-500"><?php echo "Rp " . number_format($row['price'], 0, ',', '.'); ?></p>
                        <div class="mt-4 flex justify-center space-x-4">
                            <a href="editproduk.php?id=<?php echo $row['id']; ?>" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Edit Product</a>
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">Delete Product</a>

                            <!-- Tombol Tambah Keranjang -->
                            <form method="post" class="inline-block">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                <button type="submit" name="add_to_cart" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                                    Tambah Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-center col-span-4 text-xl">Belum ada produk yang diunggah.</p>
            <?php } ?>
        </div>

        <!-- Tombol Tambah Produk -->
        <div class="flex justify-between mt-5">
            <a href="formcardbaru.php" class="btn btn-primary inline-block bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Tambah Produk</a>
            <a href="cart.php" class="btn btn-primary inline-block bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700">Keranjang</a> <!-- Tombol Keranjang -->
        </div>
    </main>

    <footer class="bg-[#8B4513] text-white shadow-md mt-16">
        <div class="container mx-auto py-4 text-center">
            ©️ 2024 Brewing Bliss
        </div>
    </footer>

    <script>
        // Menangani pencarian produk
        function filterMenu() {
            const searchInput = document.getElementById("searchBar").value.toLowerCase();
            const menuItems = document.querySelectorAll(".menu-item");

            menuItems.forEach(item => {
                const name = item.querySelector("h3").textContent.toLowerCase();
                if (name.includes(searchInput)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        }
    </script>
</body>

</html>
