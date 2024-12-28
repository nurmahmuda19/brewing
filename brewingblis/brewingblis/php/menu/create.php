<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image]);

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>
    <form method="POST" class="space-y-4">
      <input type="text" name="name" placeholder="Nama Produk" class="w-full p-2 border rounded">
      <input type="text" name="price" placeholder="Harga Produk" class="w-full p-2 border rounded">
      <textarea name="description" placeholder="Deskripsi Produk" class="w-full p-2 border rounded"></textarea>
      <input type="text" name="image" placeholder="URL Gambar" class="w-full p-2 border rounded">
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
    </form>
  </div>
</body>
</html>
