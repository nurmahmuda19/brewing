<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus gambar jika ada
    $sql = "SELECT profile_picture FROM profiles WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();
        if ($profile['profile_picture'] != '') {
            unlink($profile['profile_picture']);  // Hapus gambar dari server
        }
    }

    // Hapus data dari database
    $sql = "DELETE FROM profiles WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Profile berhasil dihapus";
    } else {
        echo "Error: " . $conn->error;
    }
}

header('Location: profile_list.php');
?>
