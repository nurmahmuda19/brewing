<?php
include('db.php');

// Fungsi untuk menambah atau mengedit profile
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Menangani upload gambar
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploadprofile/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah gambar
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file;  // Simpan nama file gambar
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Jika ID ada, lakukan update, jika tidak insert data baru
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "UPDATE profiles SET name='$name', phone='$phone', address='$address', profile_picture='$profile_picture' WHERE id=$id";
    } else {
        $sql = "INSERT INTO profiles (name, phone, address, profile_picture) VALUES ('$name', '$phone', '$address', '$profile_picture')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Profile berhasil disimpan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$profile = null;

if ($id) {
    // Ambil data profile untuk edit
    $sql = "SELECT * FROM profiles WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Header -->
<header class="bg-brown-600 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <div class="text-2xl font-bold cursor-pointer">Brewing Bliss</div>
        <nav class="space-x-6 flex items-center">
            <a href="home.php" class="hover:text-gray-200">Home</a>
            <a href="menu.php" class="hover:text-gray-200">Menu</a>
            <a href="profile_form.php" class="hover:text-gray-200">Profile</a>
        </nav>
    </div>
</header>

<!-- Formulir Profile -->
<div class="container mx-auto mt-10 px-6 space-y-6">
  <h2 class="text-2xl font-semibold">Tambah / Edit Profile</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-4">
      <label for="name" class="block text-lg">Nama:</label>
      <input type="text" name="name" id="name" value="<?php echo isset($profile['name']) ? $profile['name'] : ''; ?>" class="w-full p-2 mt-1 border rounded" required>
    </div>

    <div class="mb-4">
      <label for="phone" class="block text-lg">Kontak:</label>
      <input type="text" name="phone" id="phone" value="<?php echo isset($profile['phone']) ? $profile['phone'] : ''; ?>" class="w-full p-2 mt-1 border rounded">
    </div>

    <div class="mb-4">
      <label for="address" class="block text-lg">Alamat:</label>
      <textarea name="address" id="address" class="w-full p-2 mt-1 border rounded"><?php echo isset($profile['address']) ? $profile['address'] : ''; ?></textarea>
    </div>

    <div class="mb-4">
      <label for="profile_picture" class="block text-lg">Foto Profil:</label>
      <input type="file" name="profile_picture" id="profile_picture" class="w-full p-2 mt-1 border rounded">
      <?php if (isset($profile['profile_picture']) && $profile['profile_picture'] != ''): ?>
        <div class="mt-2">
          <img src="<?php echo $profile['profile_picture']; ?>" alt="Profile Picture" class="w-20 h-20 object-cover rounded-full">
        </div>
      <?php endif; ?>
    </div>

    <button type="submit" name="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Simpan</button>
  </form>
</div>

</body>
</html>
