<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
    $stmt->execute([$name, $price, $description, $image, $id]);

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Produk</h1>
    <form method="POST" class="space-y-4">
      <input type="text" name="name" value="<?php echo $product['name']; ?>" class="w-full p-2 border rounded">
      <input type="text" name="price" value="<?php echo $product['price']; ?>" class="w-full p-2 border rounded">
      <textarea name="description" class="w-full p-2 border rounded"><?php echo $product['description']; ?></textarea>
      <input type="text" name="image" value="<?php echo $product['image']; ?>" class="w-full p-2 border rounded">
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
    </form>
  </div>
</body>
</html>
