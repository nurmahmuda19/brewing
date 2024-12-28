<?php
// Data coffee shop hardcoded (array)
$cards = [
    [
        "id" => 1,
        "name" => "Cikole Coffee",
        "rating" => 4.8,
        "reviews" => "Tempat yang nyaman dengan kopi yang nikmat. Sangat direkomendasikan untuk pecinta kopi.",
        "distance" => "500 meter",
        "image" => "cikole.jpg"
    ],
    [
        "id" => 2,
        "name" => "Eighten Cafe",
        "rating" => 4.6,
        "reviews" => "Pelayanan ramah dan pilihan menu kopi yang beragam. Cocok untuk hangout bersama teman.",
        "distance" => "800 meter",
        "image" => "eighten.jpg"
    ],
    [
        "id" => 3,
        "name" => "Kisah Manis",
        "rating" => 4.7,
        "reviews" => "Dekorasi interior yang modern dan rasa kopi yang luar biasa. Pasti akan kembali lagi.",
        "distance" => "1.2 km",
        "image" => "kisah.jpeg"
    ],
    [
        "id" => 4,
        "name" => "Golden Pine",
        "rating" => 4.9,
        "reviews" => "Kualitas kopi yang konsisten dan suasana yang tenang. Tempat favorit untuk bekerja.",
        "distance" => "2 km",
        "image" => "golden.jpg"
    ],
    [
        "id" => 5,
        "name" => "Espresso Lane",
        "rating" => 4.5,
        "reviews" => "Pilihan pastry yang enak dipadukan dengan kopi yang mantap. Sangat memuaskan.",
        "distance" => "3.5 km",
        "image" => "cafe.jpg"
    ]
];

// Ambil parameter pencarian
$searchQuery = isset($_GET['search']) ? strtolower($_GET['search']) : '';

// Filter coffee shop berdasarkan pencarian
$filteredCards = array_filter($cards, function ($shop) use ($searchQuery) {
    return empty($searchQuery) || strpos(strtolower($shop['name']), $searchQuery) !== false;
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop - Brewing Bliss</title>
    <!-- Link Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .line-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Jumlah baris maksimum */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-gray-900 text-white flex justify-between items-center p-4">
        <h1 class="text-2xl font-bold">Brewing Bliss Coffee Shops</h1>
    </header>

    <!-- Pencarian -->
    <section class="p-4 bg-white shadow-md">
        <form method="GET" class="relative flex items-center">
            <input type="text" name="search" placeholder="Cari coffee shop..." class="border rounded px-4 py-2 w-full pr-14"
                   value="<?= htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                Cari
            </button>
        </form>
    </section>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto p-4 sm:p-6 flex flex-col">
        <div class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if (empty($filteredCards)): ?>
                    <p class="col-span-full text-center text-gray-600">Coffee shop tidak ditemukan.</p>
                <?php else: ?>
                    <?php foreach ($filteredCards as $card): ?>
                        <div class="flex flex-col bg-white rounded-lg shadow-md overflow-hidden h-[450px]">
                            <div class="flex justify-center items-center p-4">
                                <img src="<?= htmlspecialchars($card['image']); ?>" alt="Coffee Shop Image" class="w-36 h-36 mx-auto rounded-lg mb-4">
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($card['name']); ?></h2>
                                <p class="text-yellow-500 font-bold">Rating: <?= htmlspecialchars($card['rating']); ?> ⭐</p>
                                <p class="text-gray-600 text-sm mt-2 line-clamp" title="<?= htmlspecialchars($card['reviews']); ?>">
                                    <?= htmlspecialchars($card['reviews']); ?>
                                </p>
                                <p class="text-gray-500 mt-2">Jarak: <?= htmlspecialchars($card['distance']); ?></p>
                                <div class="mt-auto flex justify-center">
                                    <a href="detail.php?id=<?= htmlspecialchars($card['id']); ?>" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; 2024 Brewing Bliss Coffee Shop. All Rights Reserved.</p>
    </footer>

</body>
</html>