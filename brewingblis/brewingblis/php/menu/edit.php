<?php
// Data produk hardcoded (array)
$cards = [
    [
        "id" => 1,
        "title" => "Beragam rasa, cocok untuk Mesin Pod",
        "price" => "Rp 35,000",
        "description" => "Beragam rasa, cocok untuk Mesin Pod",
        "image" => "https://via.placeholder.com/150"
    ],
    [
        "id" => 2,
        "title" => "Coklat, karamel & hazelnut",
        "price" => "Rp 40,000",
        "description" => "Coklat, karamel & hazelnut",
        "image" => "https://via.placeholder.com/150"
    ],
    [
        "id" => 3,
        "title" => "Coklat hitam, fudge & vanilla",
        "price" => "Rp 50,000",
        "description" => "Coklat hitam, fudge & vanilla",
        "image" => "https://via.placeholder.com/150"
    ],
];

// Mengambil produk berdasarkan ID
$productId = $_GET['id'] ?? null;
$product = null;
if ($productId) {
    foreach ($cards as $card) {
        if ($card['id'] == $productId) {
            $product = $card;
            break;
        }
    }
}

// Menangani form update produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'update' && $product) {
        $updateId = $_POST['id'];
        foreach ($cards as &$card) {
            if ($card['id'] == $updateId) {
                $card['title'] = $_POST['title'];
                $card['price'] = $_POST['price'];
                $card['description'] = $_POST['description'];
                $card['image'] = $_POST['image'] ?: $card['image'];
                break;
            }
        }
        // Redirect setelah update
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Brewing Bliss</title>
    <!-- Link Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Form Edit Produk -->
    <div class="container mx-auto p-4 sm:p-6 mt-8 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800">Edit Produk</h2>
        <?php if ($product): ?>
            <form method="POST" class="mt-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']); ?>">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Nama Produk</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($product['title']); ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Harga</label>
                    <input type="text" id="price" name="price" value="<?= htmlspecialchars($product['price']); ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required><?= htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700">URL Gambar</label>
                    <input type="text" id="image" name="image" value="<?= htmlspecialchars($product['image']); ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        <?php else: ?>
            <p class="text-red-500">Produk tidak ditemukan.</p>
        <?php endif; ?>
    </div>

</body>
</html>
