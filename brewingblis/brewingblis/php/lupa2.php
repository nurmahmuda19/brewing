<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "brewingblis";

$conn = new mysqli($host, $username, $password, $database);

$error_message = ""; // Variabel untuk menyimpan pesan error
$success_message = ""; // Variabel untuk menyimpan pesan sukses

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Cek apakah email terdaftar di database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika email ditemukan, kirimkan link reset password
        // Anda bisa menambahkan logika untuk mengirimkan email dengan link reset password.
        // Misalnya, menggunakan PHPMailer atau PHP mail() function.

        $reset_token = bin2hex(random_bytes(16)); // Membuat token reset password yang unik

        // Menyimpan token reset di database (untuk digunakan saat mereset password)
        $query = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $reset_token, $email);
        $stmt->execute();

        // Simulasikan pengiriman email untuk reset password
        // Anda bisa mengirim email dengan link reset password yang berisi token
        // Berikut hanya contoh logika tanpa email nyata:
        // Kirim email dengan link seperti: www.yoursite.com/reset-password.php?token=reset_token

        $success_message = "Link untuk reset password telah dikirim ke email Anda!";
    } else {
        // Jika email tidak ditemukan
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
    <title>Lupa Kata Sandi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="flex flex-row w-full">
        <div class="w-1/2 bg-white rounded-lg shadow-md p-10 hidden sm:block">
            <img src="2.png" alt="Login Illustration" class="w-full h-full object-cover rounded-lg">
        </div>
        
        <div class="w-full sm:w-1/2 bg-white p-10 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold mb-6">Lupa Kata Sandi</h3>
            <p class="text-gray-600 mb-6">Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan link untuk mereset kata sandi Anda.</p>
            
            <!-- Menampilkan pesan error jika ada -->
            <?php if ($error_message): ?>
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <!-- Menampilkan pesan sukses jika ada -->
            <?php if ($success_message): ?>
                <div class="bg-green-500 text-white p-3 rounded mb-4">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label class="block mb-2 text-sm font-medium text-gray-700">Email:</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address">
                
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Kirim Link Reset</button>
            </form>

            <div class="mt-4 text-center">
                <a href="login.php" class="text-blue-500 hover:underline">Sudah ingat kata sandi? Login disini</a>
            </div>
        </div>
    </div>
</body>

</html>
