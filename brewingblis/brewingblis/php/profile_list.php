<?php
include('db.php');

// Menampilkan semua profile
$sql = "SELECT * FROM profiles";
$result = $conn->query($sql);

echo '<div class="container mx-auto mt-10 px-6">';
echo '<h2 class="text-2xl font-semibold">Daftar Profile</h2>';
echo '<table class="table-auto w-full mt-6 border border-gray-300">';
echo '<thead><tr><th class="px-4 py-2">Nama</th><th class="px-4 py-2">Kontak</th><th class="px-4 py-2">Aksi</th></tr></thead>';
echo '<tbody>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td class="px-4 py-2">' . $row['name'] . '</td>';
    echo '<td class="px-4 py-2">' . $row['phone'] . '</td>';
    echo '<td class="px-4 py-2">
            <a href="profile_form.php?id=' . $row['id'] . '" class="text-blue-600 hover:underline">Edit</a> |
            <a href="delete_profile.php?id=' . $row['id'] . '" class="text-red-600 hover:underline">Hapus</a>
          </td>';
    echo '</tr>';
}

echo '</tbody></table>';
echo '</div>';
?>
