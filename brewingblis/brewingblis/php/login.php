<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "brewingblis";

$conn = new mysqli($host, $username, $password, $database);

$error_message = ""; // Variabel untuk menyimpan pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Mencari pengguna berdasarkan email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username']; // Menyimpan username untuk sesi

            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                header("Location: admin.php"); // Admin akan diarahkan ke admin.php
            } else {
                header("Location: homi.html"); // Pengguna biasa akan diarahkan ke homi.html
            }
            exit(); // Pastikan untuk berhenti eksekusi setelah redirect
        } else {
            // Password salah
            $error_message = "Password salah!";
        }
    } else {
        // Email tidak ditemukan
        $error_message = "Email tidak ditemukan!";
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
    <title>Halaman Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="flex flex-row w-full">
        <div class="w-1/2 bg-white rounded-lg shadow-md p-10 hidden sm:block">
            <img src="2.png" alt="Login Illustration" class="w-full h-full object-cover rounded-lg">
        </div>
        <div class="w-full sm:w-1/2 bg-white p-10 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Masuk</h2>
            
            <!-- Menampilkan pesan error jika ada -->
            <?php if ($error_message): ?>
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label class="block mb-2 text-sm font-medium text-gray-700">Email:</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address">

                <label class="block mb-2">Password:</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4">

                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Login</button>
            </form>

            <div class="mt-4 text-center">
                <a href="registrasiuser.php" class="text-blue-500 hover:underline">Belum punya akun? Daftar disini</a>
            </div>
            <div class="mt-4 text-center">
                <a href="lupa2.php" class="text-blue-500 hover:underline">Lupa password</a>
            </div>
        </div>
    </div>
</body>

</html>
