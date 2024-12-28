<?php
// Data produk hardcoded (array)
$cards = [
    [
        "id" => 1,
        "title" => "Espresso Coffee",
        "price" => "Rp 25,000",
        "description" => "Rasakan kehangatan sejati dalam setiap tegukan espresso kami. Kopi dengan kekuatan rasa yang kaya dan aroma memikat.",
        "image" => "espresso.jpg",
        "category" => "coffee"
    ],
    [
        "id" => 2,
        "title" => "Latte Coffee",
        "price" => "Rp 20,000",
        "description" => "Coklat, karamel & hazelnut",
        "image" => "latte.jpg",
        "category" => "coffee"
    ],
    [
        "id" => 3,
        "title" => "Dalgona Coffee",
        "price" => "Rp 20,000",
        "description" => "Coklat hitam, fudge & vanilla",
        "image" => "dalgona.jpg",
        "category" => "coffee"
    ],
    [
        "id" => 4,
        "title" => "Arabica Coffee",
        "price" => "Rp 20,000",
        "description" => "Kopi dengan sentuhan coklat mocha yang lezat.",
        "image" => "arabica.jpg",
        "category" => "coffee"
    ],
    [
        "id" => 5,
        "title" => "Jus Alpukat",
        "price" => "Rp 8,000",
        "description" => "Rasakan kehangatan sejati dalam setiap tegukan jus alpukat kami.",
        "image" => "jus alpukat.jpg",
        "category" => "jus"
    ],
    [
        "id" => 6,
        "title" => "Jus Jambu",
        "price" => "Rp 8,000",
        "description" => "Coklat, karamel & hazelnut",
        "image" => "jus jambu.jpg",
        "category" => "jus"
    ],
    [
        "id" => 7,
        "title" => "Jus Mangga",
        "price" => "Rp 8,000",
        "description" => "Coklat hitam, fudge & vanilla",
        "image" => "jus mangga.jpg",
        "category" => "jus"
    ],
    [
        "id" => 8,
        "title" => "Jus Wortel",
        "price" => "Rp 8,000",
        "description" => "Kopi dengan sentuhan coklat mocha yang lezat.",
        "image" => "jus wortel.jpg",
        "category" => "jus"
    ],
    [
        "id" => 9,
        "title" => "Milkshake Mango",
        "price" => "Rp 15,000",
        "description" => "Rasakan kehangatan sejati dalam setiap tegukan milkshake mangga kami.",
        "image" => "mango milkshake.jpg",
        "category" => "milkshake"
    ],
    [
        "id" => 10,
        "title" => "Milkshake Matcha",
        "price" => "Rp 15,000",
        "description" => "Coklat, karamel & hazelnut",
        "image" => "latte.jpg",
        "category" => "milkshake"
    ],
    [
        "id" => 11,
        "title" => "Milkshake Oreo",
        "price" => "Rp 15,000",
        "description" => "Coklat hitam, fudge & vanilla",
        "image" => "milkshake oreo.jpg",
        "category" => "milkshake"
    ],
    [
        "id" => 12,
        "title" => "Milkshake Strawberry",
        "price" => "Rp 15,000",
        "description" => "Kopi dengan sentuhan coklat mocha yang lezat.",
        "image" => "milkshake strawberry.jpg",
        "category" => "milkshake"
    ],
];

// Ambil parameter pencarian dan kategori
$searchQuery = isset($_GET['search']) ? strtolower($_GET['search']) : '';
$selectedCategory = isset($_GET['category']) ? strtolower($_GET['category']) : '';

// Filter produk berdasarkan pencarian dan kategori
$filteredCards = array_filter($cards, function ($product) use ($searchQuery, $selectedCategory) {
    $matchesSearch = empty($searchQuery) || strpos(strtolower($product['title']), $searchQuery) !== false;
    $matchesCategory = empty($selectedCategory) || strtolower($product['category']) === $selectedCategory;
    return $matchesSearch && $matchesCategory;
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Produk - Brewing Bliss</title>
    <!-- Link Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-gray-900 text-white flex justify-between items-center p-4">
        <div class="flex items-center">
            <img src="logo.png" alt="Logo Brewing Bliss" class="h-10 w-10 mr-3">
            <h1 class="text-2xl font-bold">Brewing Bliss</h1>
        </div>
        <nav class="hidden md:flex space-x-6">
            <a href="#home" class="hover:text-gray-400">Home</a>
            <a href="#menu" class="hover:text-gray-400">Menu</a>
            <a href="#coffee-shop" class="hover:text-gray-400">Coffee Shop</a>
            <a href="#help" class="hover:text-gray-400">Help</a>
            <a href="#about" class="hover:text-gray-400">About</a>
        </nav>
        <div class="flex items-center space-x-4">
            <button class="relative flex items-center justify-center w-8 h-8">
                <img src="User Circle.png" alt="Notifikasi" class="w-full h-full object-contain hover:opacity-75">
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1">3</span>
            </button>
            <img src="cart.png" alt="Keranjang Belanja" class="w-8 h-8 object-contain hover:opacity-75">
        </div>
    </header>

    <!-- Pencarian -->
    <section class="p-4 bg-white shadow-md">
        <form method="GET" class="relative flex items-center">
            <input type="text" name="search" placeholder="Cari produk..." class="border rounded px-4 py-2 w-full pr-14"
                   value="<?= htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="absolute right-10 top-2 text-gray-500 hover:text-gray-700">
                <img src="search.png" alt="Keranjang Belanja" class="w-8 h-8 object-contain hover:opacity-75">
            </button>
            <button type="button" id="filterButton" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                <img src="filtere.png" alt="Keranjang Belanja" class="w-8 h-8 object-contain hover:opacity-75">
            </button>
        </form>
    <!-- Filter Dropdown -->
    <div id="filterMenu" class="hidden mt-2 bg-white shadow-lg rounded border">
      <ul class="p-2">
        <li><a href="#" onclick="filterProducts('coffee')" class="block p-2 hover:bg-gray-200">Coffee</a></li>
        <li><a href="#" onclick="filterProducts('jus')" class="block p-2 hover:bg-gray-200">Jus</a></li>
        <li><a href="#" onclick="filterProducts('milkshake')" class="block p-2 hover:bg-gray-200">Milkshake</a></li>
        <li><a href="#" onclick="filterProducts('')" class="block p-2 hover:bg-gray-200">Semua</a></li>
      </ul>
    </div>
    </section>

    <!-- Main Content Wrapper -->
    <main class="flex-1 container mx-auto p-4 sm:p-6 flex flex-col">
        <div class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if (empty($filteredCards)): ?>
                    <p class="col-span-full text-center text-gray-600">Produk tidak ditemukan.</p>
                <?php else: ?>
                    <?php foreach ($filteredCards as $card): ?>
                        <div class="flex flex-col bg-white rounded-lg shadow-md overflow-hidden h-[450px]">
                            <div class="flex justify-center items-center p-4">
                                <img src="<?= htmlspecialchars($card['image']); ?>" alt="Product Image" class="w-36 h-36 mx-auto rounded-lg mb-4">
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($card['title']); ?></h2>
                                <p class="text-red-500 font-bold"><?= htmlspecialchars($card['price']); ?></p>
                                <p class="text-gray-600 text-sm mt-2"><?= htmlspecialchars($card['description']); ?></p>
                                <div class="mt-auto flex justify-between">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($card['id']); ?>">
                                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Hapus</button>
                                    </form>
                                    <a href="edit.php?id=<?= htmlspecialchars($card['id']); ?>" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">Edit</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-6 flex justify-center">
            <a href="#form" class="bg-blue-500 px-6 py-3 rounded text-white hover:bg-blue-600">Tambah Produk</a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; 2024 Brewing Bliss. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript untuk filter -->
    <script>
        document.getElementById('filterButton').addEventListener('click', function() {
            const menu = document.getElementById('filterMenu');
            menu.classList.toggle('hidden');
        });

        function filterProducts(category) {
    // Tambahkan parameter kategori di URL
    window.location.href = '?search=<?= urlencode($searchQuery); ?>&category=' + category;
    }
    </script>

</body>
</html>