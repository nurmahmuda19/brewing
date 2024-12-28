<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Menampilkan semua error

session_start();  // Memastikan session dimulai

// Debug session untuk memastikan id_user ada
echo "<pre>";
var_dump($_SESSION);  // Melihat semua data di session
echo "</pre>";

// Cek apakah session 'id_user' ada
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user']; // Ambil id_user dari session
    include('db.php'); // Menghubungkan ke database

    // Mengambil data profil pengguna berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id_user);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-profile {
            background-color: #b36a50;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex items-center justify-center">

<header class="bg-brown-600 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <div class="text-2xl font-bold cursor-pointer">Brewing Bliss</div>
        <nav class="space-x-6 flex items-center">
            <a href="home.php" class="hover:text-gray-200">Home</a>
            <a href="menu.php" class="hover:text-gray-200">Menu</a>
            <a href="profile.php" class="hover:text-gray-200">Profile</a>
        </nav>
    </div>
</header>

    <!-- Container Profile -->
    <div class="bg-profile w-11/12 max-w-4xl p-10 rounded-lg shadow-2xl flex flex-col items-center text-white relative">
        
        <!-- Bagian Foto Profil -->
        <div class="flex flex-col items-center space-y-6">

            <!-- Back Button -->
            <a href="home.php" class="absolute top-4 left-4 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                ← Back to Homepage
            </a>
            <div>
                <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile"
                    class="w-48 h-48 rounded-full object-cover shadow-lg">
            </div>
            <p class="text-2xl font-bold text-center"><?php echo htmlspecialchars($user['name']); ?></p>
        </div>
        
        <!-- Bagian Detail Profil -->
        <div class="mt-6 space-y-4 text-center">
            <p class="text-lg"><span class="font-semibold">Kontak:</span> <?php echo htmlspecialchars($user['contact']); ?></p>
            <p class="text-lg"><span class="font-semibold">Alamat:</span> <?php echo htmlspecialchars($user['address']); ?></p>
        </div>

        <!-- Tombol -->
        <div class="mt-8 flex flex-col space-y-4 w-full items-center">
            <button
                class="w-1/2 px-6 py-3 bg-white text-black font-bold rounded-lg hover:bg-gray-100 transition duration-300" onclick="window.location.href='editprofile.php'">
                Edit Profile
            </button>
            <button
                class="w-1/2 px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition duration-300" onclick="window.location.href='logout.php'">
                Log Out
            </button>
        </div>
    </div>
</body>
</html>