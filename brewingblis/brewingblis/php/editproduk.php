<?php
session_start();
include 'filekoneksi.php';

// Cek apakah pengguna sudah login dan memiliki hak akses yang benar
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$host = "localhost"; 
$user = "root";      
$password = "";      
$dbname = "crud_brewingbliss"; 

$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID produk yang ingin diedit
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data produk berdasarkan ID
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "Produk tidak ditemukan.";
        exit;
    }

    $product = $result->fetch_assoc();
} else {
    echo "ID produk tidak valid.";
    exit;
}

$update_message = ''; // Pesan untuk update produk

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $price = $_POST['price'];
    $file_path = $product['file_path']; // Jaga file yang ada sebelumnya

    // Validasi file gambar
    if ($_FILES['fileToUpload']['name'] != '') { // Jika ada gambar baru yang diupload
        $file_path = "uploads/" . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Cek apakah file adalah gambar
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            $uploadOk = 0;
            $update_message = "File yang diunggah bukan gambar.";
        }

        // Cek ukuran file (maksimal 5MB)
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $uploadOk = 0;
            $update_message = "Maaf, file terlalu besar.";
        }

        // Cek tipe file
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            $update_message = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        }

        // Jika uploadOk = 1, lanjutkan untuk upload file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file_path)) {
                // File berhasil di-upload
            } else {
                $update_message = "Terjadi kesalahan saat mengunggah file.";
            }
        }
    }

    // Jika semua validasi gambar sukses, lanjutkan update data
    if ($update_message == '') {
        $sql = "UPDATE products SET name = '$name', price = '$price', file_path = '$file_path' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            $update_message = "Produk berhasil diperbarui!";

            // Update keranjang jika produk ada di dalam session cart
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['name'] = $name;  // Update nama produk
                $_SESSION['cart'][$id]['price'] = $price;  // Update harga produk
                $_SESSION['cart'][$id]['file_path'] = $file_path;  // Update file gambar produk
            }
        } else {
            $update_message = "Terjadi kesalahan: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-[#8B4513] text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold cursor-pointer" id="logo">Brewing Bliss</div>
        </div>
    </header>

    <!-- Edit Form -->
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-md">
        <h1 class="text-2xl font-bold mb-6">Edit Produk</h1>
        
        <form action="editproduk.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-lg font-medium">Nama Produk</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="price" class="block text-lg font-medium">Harga Produk</label>
                <input type="number" id="price" name="price" class="w-full p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="fileToUpload" class="block text-lg font-medium">Upload Gambar Baru (Opsional)</label>
                <input type="file" name="fileToUpload" id="fileToUpload" class="w-full p-2 border border-gray-300 rounded">
                <p class="text-sm text-gray-500">Biarkan kosong jika tidak ingin mengganti gambar</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">Perbarui Produk</button>
        </form>

        <!-- Tombol Kembali -->
        <a href="minum.php" class="inline-block mt-4 text-center bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700">
            Kembali ke Daftar Produk
        </a>
    </div>

    <!-- Pop-up Notifikasi -->
    <?php if ($update_message != '') { ?>
        <div id="popupMessage" class="fixed bottom-16 left-6 bg-green-600 text-white py-2 px-4 rounded-md shadow-lg opacity-0 transform translate-y-4 transition-all duration-300" style="display: block;">
            <?php echo $update_message; ?>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('popupMessage').classList.remove('opacity-0');
                document.getElementById('popupMessage').classList.add('opacity-100');
                document.getElementById('popupMessage').classList.remove('translate-y-4');
                document.getElementById('popupMessage').classList.add('translate-y-0');
            }, 100);
            setTimeout(function() {
                document.getElementById('popupMessage').classList.remove('opacity-100');
                document.getElementById('popupMessage').classList.add('opacity-0');
                document.getElementById('popupMessage').classList.remove('translate-y-0');
                document.getElementById('popupMessage').classList.add('translate-y-4');
            }, 5000);
        </script>
    <?php } ?>

</body>

</html>
