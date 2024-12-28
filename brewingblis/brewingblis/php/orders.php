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

$sql = "SELECT o.id, p.name AS product_name, o.quantity, o.total_price, o.status, o.order_date
        FROM orders o
        JOIN products p ON o.product_id = p.id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders - Brewing Bliss</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">

  <!-- Header -->
  <header class="bg-[#8B4513] text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <div class="text-2xl font-bold cursor-pointer" id="logo">Brewing Bliss</div>
    </div>
  </header>

  <!-- Orders Section -->
  <main class="container mx-auto mt-10 px-6">
    <h1 class="text-3xl font-bold mb-6">Daftar Orders</h1>
    <table class="min-w-full bg-white shadow-lg rounded-lg overflow-hidden">
      <thead>
        <tr class="bg-gray-200">
          <th class="px-4 py-2 text-left">Order ID</th>
          <th class="px-4 py-2 text-left">Product</th>
          <th class="px-4 py-2 text-left">Quantity</th>
          <th class="px-4 py-2 text-left">Total Price</th>
          <th class="px-4 py-2 text-left">Status</th>
          <th class="px-4 py-2 text-left">Order Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0) { ?>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['id']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['product_name']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['quantity']); ?></td>
              <td class="px-4 py-2"><?php echo "Rp " . number_format($row['total_price'], 0, ',', '.'); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['order_date']); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="6" class="text-center px-4 py-2">Belum ada order.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </main>

  <footer class="bg-[#8B4513] text-white shadow-md mt-16">
    <div class="container mx-auto py-4 text-center">
      ©️ 2024 Brewing Bliss
    </div>
  </footer>

</body>

</html>
