<?php
include('db.php');

// Ambil data profil pengguna
session_start();
$user_id = $_SESSION['id_user'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $profile_image = $_FILES['profile_image']['name'];

    // Proses upload gambar
    if ($profile_image) {
        move_uploaded_file($_FILES['profile_image']['tmp_name'], "uploads/" . $profile_image);
        // Update gambar dalam database
        $stmt = $conn->prepare("UPDATE users SET name = :name, contact = :contact, address = :address, profile_image = :profile_image WHERE id = :id");
        $stmt->bindParam(':profile_image', $profile_image);
    } else {
        // Jika tidak ada gambar baru, update tanpa gambar
        $stmt = $conn->prepare("UPDATE users SET name = :name, contact = :contact, address = :address WHERE id = :id");
    }

    // Update data pengguna
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();

    // Redirect ke halaman profil setelah berhasil update
    header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white w-11/12 max-w-4xl p-10 rounded-lg shadow-2xl">
        <h2 class="text-3xl font-semibold text-center mb-6">Edit Profile</h2>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="name" class="block text-lg">Name</label>
                <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>" class="w-full p-3 border border-gray-300 rounded" required>
            </div>
            <div>
                <label for="contact" class="block text-lg">Contact</label>
                <input type="text" name="contact" id="contact" value="<?php echo $user['contact']; ?>" class="w-full p-3 border border-gray-300 rounded">
            </div>
            <div>
                <label for="address" class="block text-lg">Address</label>
                <input type="text" name="address" id="address" value="<?php echo $user['address']; ?>" class="w-full p-3 border border-gray-300 rounded">
            </div>
            <div>
                <label for="profile_image" class="block text-lg">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" class="w-full p-3 border border-gray-300 rounded">
            </div>
            <div class="flex justify-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg">Save Changes</button>
            </div>
        </form>
    </div>

</body>
</html>
