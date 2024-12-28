<?php
session_start();
include 'filekoneksi.php'; // Pastikan file ini berisi koneksi database

// Cek apakah pengguna sudah login dan memiliki hak akses yang benar
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Koneksi MySQLi
$host = "localhost"; 
$user = "root";      
$password = "";      
$dbname = "crud_brewingbliss"; 

$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$notification_message = '';
$notification_type = ''; // success or error
$product_added = false;  // Flag untuk menandai apakah produk sudah ditambahkan

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $price = $_POST['price'];
    $file_path = "uploads/" . basename($_FILES["fileToUpload"]["name"]);

    // Validasi file gambar
    $imageFileType = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $uploadOk = 1;
    $error_message = "";

    // Cek apakah file adalah gambar
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
        $error_message = "File yang diunggah bukan gambar.";
    }

    // Cek ukuran file (maksimal 5MB)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $uploadOk = 0;
        $error_message = "Maaf, file terlalu besar.";
    }

    // Cek tipe file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
        $error_message = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
    }

    // Cek jika uploadOk = 0 berarti ada masalah
    if ($uploadOk == 0) {
        $notification_message = "Maaf, file Anda tidak dapat diunggah. " . $error_message;
        $notification_type = 'error';
    } else {
        // Jika uploadOk = 1, lanjutkan untuk upload file
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file_path)) {
            // Jika upload file sukses, lakukan query insert ke database
            $sql = "INSERT INTO products (name, price, file_path) VALUES ('$name', '$price', '$file_path')";
            
            if ($conn->query($sql) === TRUE) {
                $notification_message = "Produk berhasil ditambahkan.";
                $notification_type = 'success';
                $product_added = true; // Menandai produk berhasil ditambahkan
            } else {
                // Menangani error jika query gagal
                $notification_message = "Terjadi kesalahan: " . $conn->error;
                $notification_type = 'error';
            }
        } else {
            $notification_message = "Terjadi kesalahan saat mengunggah file.";
            $notification_type = 'error';
        }
    }
}

// Ambil data produk terbaru dari database
$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .notification {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 50;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        .notification.hidden {
            opacity: 0;
            transform: translateY(-20px);
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Notification Pop-up -->
<?php if ($notification_message): ?>
<div id="notification" class="notification <?php echo $notification_type == 'error' ? 'bg-red-500' : 'bg-green-500'; ?> text-white p-4 rounded-md shadow-lg">
    <p><?php echo $notification_message; ?></p>
</div>
<script>
    // Menampilkan dan menyembunyikan notifikasi
    setTimeout(() => {
        const notification = document.getElementById('notification');
        notification.classList.add('hidden');
    }, 5000); // Hilangkan notifikasi setelah 5 detik
</script>
<?php endif; ?>

<!-- Form untuk tambah produk -->
<div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-md">
    <h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="name" class="block text-lg font-medium">Nama Produk</label>
            <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        
        <div class="mb-4">
            <label for="price" class="block text-lg font-medium">Harga Produk</label>
            <input type="number" id="price" name="price" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        
        <div class="mb-4">
            <label for="fileToUpload" class="block text-lg font-medium">Upload Gambar</label>
            <input type="file" name="fileToUpload" id="fileToUpload" class="w-full p-2 border border-gray-300 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">Tambah Produk</button>
    </form>

    <!-- Back Button -->
    <a href="minum.php" class="block mt-4 text-center bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700">
        Kembali ke Halaman Sebelumnya
    </a>
</div>

<!-- Tabel Produk Terbaru -->
<?php if ($product_added): ?>
    <div class="container mx-auto mt-6">
        <h2 class="text-xl font-bold mb-4">Produk Terbaru</h2>
        <table class="table-auto w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Harga</th>
                    <th class="px-4 py-2 border">Gambar</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-4 py-2 border"><?php echo $row['name']; ?></td>
                    <td class="px-4 py-2 border"><?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                    <td class="px-4 py-2 border">
                        <img src="<?php echo $row['file_path']; ?>" alt="product-image" class="w-16 h-16 object-cover rounded-md">
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

</body>
</html>
