<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Jika bukan admin, arahkan ke halaman login
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "brewingblis";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menambahkan pengguna baru
if (isset($_POST['add_user'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $query = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $password, $role);
    if ($stmt->execute()) {
        echo "Pengguna baru berhasil ditambahkan!";
    } else {
        echo "Gagal menambahkan pengguna: " . $stmt->error;
    }
    $stmt->close();
}

// Menghapus pengguna
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $query = "DELETE FROM users WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        echo "Pengguna berhasil dihapus!";
    } else {
        echo "Gagal menghapus pengguna: " . $stmt->error;
    }
    $stmt->close();
}

// Mengambil daftar pengguna
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-screen-lg mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

        <!-- Tombol untuk kembali ke home.html -->
        <div class="mb-4">
            <a href="homi.html" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Kembali ke Home</a>
        </div>

        <!-- Form untuk menambah pengguna -->
        <h2 class="text-xl font-semibold mb-4">Tambah Pengguna</h2>
        <form method="POST" class="space-y-4">
            <label class="block">Email:</label>
            <input type="email" name="email" class="w-full px-4 py-2 border rounded-md" required>

            <label class="block">Password:</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded-md" required>

            <label class="block">Role:</label>
            <select name="role" class="w-full px-4 py-2 border rounded-md">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>

            <button type="submit" name="add_user" class="bg-blue-500 text-white px-4 py-2 rounded-md">Tambah Pengguna</button>
        </form>

        <!-- Daftar Pengguna -->
        <h2 class="text-xl font-semibold mt-10 mb-4">Daftar Pengguna</h2>
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Role</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?php echo $row['id_user']; ?></td>
                        <td class="px-4 py-2 border"><?php echo $row['email']; ?></td>
                        <td class="px-4 py-2 border"><?php echo $row['role']; ?></td>
                        <td class="px-4 py-2 border">
                            <a href="?delete_user=<?php echo $row['id_user']; ?>" class="text-red-500 hover:text-red-700">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>
