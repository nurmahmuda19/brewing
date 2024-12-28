<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk - Brewing Bliss</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <!-- Navbar -->
  <div class="bg-gray-900 text-white p-4 flex flex-wrap justify-between items-center">
    <div class="text-xl font-bold">BREWING BLISS</div>
    <div class="flex space-x-4 text-sm mt-2 sm:mt-0">
      <a href="#" class="hover:text-gray-300">Home</a>
      <a href="#" class="hover:text-gray-300">Menu</a>
      <a href="#" class="hover:text-gray-300">Coffee Shop</a>
      <a href="#" class="hover:text-gray-300">About</a>
      <a href="#" class="hover:text-gray-300">Help</a>
    </div>
  </div>

  <!-- Form untuk Menambah Produk -->
  <div class="container mx-auto p-4 sm:p-6">
    <h1 class="text-xl font-bold mb-4">Tambah Produk Baru</h1>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
      <label for="name" class="block text-sm font-semibold mb-2">Nama Produk:</label>
      <input type="text" id="name" name="name" class="w-full p-2 border rounded mb-4" required>

      <label for="price" class="block text-sm font-semibold mb-2">Harga Produk:</label>
      <input type="number" id="price" name="price" class="w-full p-2 border rounded mb-4" required>

      <label for="description" class="block text-sm font-semibold mb-2">Deskripsi Produk:</label>
      <textarea id="description" name="description" class="w-full p-2 border rounded mb-4" required></textarea>

      <label for="image" class="block text-sm font-semibold mb-2">Gambar Produk:</label>
      <input type="file" id="image" name="image" class="w-full p-2 border rounded mb-4" accept="image/*" required>

      <button type="submit" name="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah Produk</button>
    </form>
  </div>

</body>
</html>
