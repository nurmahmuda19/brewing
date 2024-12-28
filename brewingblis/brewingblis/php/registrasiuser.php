<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "brewingblis";

$conn = new mysqli($host, $username, $password, $database);

// Cek jika ada error koneksi ke database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'user'; // Set role default menjadi 'user'

    // Cek apakah email sudah terdaftar
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika email sudah ada, tampilkan pesan error
        $message = "Email sudah terdaftar. Silakan gunakan email lain.";
    } else {
        // Jika email belum terdaftar, lanjutkan dengan registrasi
        $query = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $email, $password, $role);

        if ($stmt->execute()) {
            $message = "Registrasi berhasil!";
        } else {
            $message = "Gagal registrasi: " . $conn->error;
        }
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Daftar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="flex flex-row w-full">
        <div class="w-1/2 bg-white rounded-lg shadow-md p-10 hidden sm:block">
            <img src="2.png" alt="Register Illustration" class="w-full h-full object-cover rounded-lg">
        </div>
        
        <div class="w-full sm:w-1/2 bg-white p-10 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Form Registrasi</h2>
            <form method="POST">
                <label class="block mb-2 text-sm font-medium text-gray-700">Email:</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address">

                <label class="block mb-2 text-sm font-medium text-gray-700">Password:</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4">

                <!-- Role field is removed, and default role is set to 'user' in PHP -->

                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Daftar</button>
            </form>

            <?php if (isset($message)) : ?>
                <p class="mt-4 text-center <?php echo ($message === 'Registrasi berhasil!') ? 'text-green-500' : 'text-red-500'; ?>">
                    <?php echo $message; ?>
                </p>
            <?php endif; ?>

            <div class="mt-4 text-center">
                <p class="text-sm">Sudah punya akun? <a href="login.php" class="text-blue-500 hover:underline">Login disini</a></p>
            </div>
        </div>
    </div>
</body>

</html>
