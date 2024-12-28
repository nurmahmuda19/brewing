<?php
// Data coffee shop hardcoded (array)
$cards = [
    [
        "id" => 1,
        "name" => "Cikole Coffee",
        "rating" => 4.8,
        "reviews" => "Tempat yang nyaman dengan kopi yang nikmat. Sangat direkomendasikan untuk pecinta kopi.",
        "distance" => "500 meter",
        "image" => "cikole.jpg",
        "address" => "Jalan Cikole No. 123, Bandung",
        "menu" => ["Kopi Tubruk", "Cappuccino", "Espresso", "Croissant"]
    ],
    [
        "id" => 2,
        "name" => "Eighten Cafe",
        "rating" => 4.6,
        "reviews" => "Pelayanan ramah dan pilihan menu kopi yang beragam. Cocok untuk hangout bersama teman.",
        "distance" => "800 meter",
        "image" => "eighten.jpg",
        "address" => "Jalan Eighten No. 45, Bandung",
        "menu" => ["Latte", "Americano", "Cheesecake", "Muffin"]
    ],
    [
        "id" => 3,
        "name" => "Kisah Manis",
        "rating" => 4.7,
        "reviews" => "Dekorasi interior yang modern dan rasa kopi yang luar biasa. Pasti akan kembali lagi.",
        "distance" => "1.2 km",
        "image" => "kisah.jpeg",
        "address" => "Jalan Manis No. 10, Bandung",
        "menu" => ["Macchiato", "Mocha", "Donut", "Brownies"]
    ],
    [
        "id" => 4,
        "name" => "Golden Pine",
        "rating" => 4.9,
        "reviews" => "Kualitas kopi yang konsisten dan suasana yang tenang. Tempat favorit untuk bekerja.",
        "distance" => "2 km",
        "image" => "golden.jpg",
        "address" => "Jalan Golden No. 77, Bandung",
        "menu" => ["Flat White", "Espresso", "Bagel", "Scone"]
    ],
    [
        "id" => 5,
        "name" => "Espresso Lane",
        "rating" => 4.5,
        "reviews" => "Pilihan pastry yang enak dipadukan dengan kopi yang mantap. Sangat memuaskan.",
        "distance" => "3.5 km",
        "image" => "cafe.jpg",
        "address" => "Jalan Espresso No. 5, Bandung",
        "menu" => ["Affogato", "Hot Chocolate", "Sandwich", "Pancake"]
    ]
];

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Cari coffee shop berdasarkan ID
$coffeeShop = array_filter($cards, fn($shop) => $shop['id'] === $id);
$coffeeShop = array_shift($coffeeShop); // Ambil data pertama dari hasil filter

if (!$coffeeShop) {
    echo "Coffee shop tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Coffee Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4"><?= htmlspecialchars($coffeeShop['name']); ?></h1>
        <img src="<?= htmlspecialchars($coffeeShop['image']); ?>" alt="<?= htmlspecialchars($coffeeShop['name']); ?>" class="w-64 h-64 mx-auto rounded mb-4">
        <p><strong>Alamat:</strong> <?= htmlspecialchars($coffeeShop['address']); ?></p>
        <p><strong>Rating:</strong> <?= htmlspecialchars($coffeeShop['rating']); ?> ⭐</p>
        <p><strong>Ulasan:</strong> <?= htmlspecialchars($coffeeShop['reviews']); ?></p>
        <h2 class="text-lg font-bold mt-4">Menu:</h2>
        <ul class="list-disc ml-6">
            <?php foreach ($coffeeShop['menu'] as $menuItem): ?>
                <li><?= htmlspecialchars($menuItem); ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="index.php" class="block bg-blue-500 text-white text-center rounded py-2 mt-6">Kembali</a>
    </div>
</body>
</html>