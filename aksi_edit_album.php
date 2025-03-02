<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_POST['albumid'])) {
    // Pastikan sesi sudah dimulai sebelum mengakses $_SESSION
    if (!isset($_SESSION['user_id'])) {
        echo "Gagal mengedit album: User ID tidak tersedia.";
        exit();
    }

    $albumId = $_POST['albumid'];
    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi'];

    // Memperbarui informasi album di database hanya jika album milik user
    $sql = "UPDATE album SET namaalbum = ?, deskripsi = ? WHERE albumid = ? AND userid = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssii", $namaalbum, $deskripsi, $albumId, $_SESSION['user_id']); // Mengikat parameter

        if ($stmt->execute()) {
            header("Location: album.php?message=Album berhasil diperbarui.");
            exit();
        } else {
            echo "Gagal mengedit album: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Gagal menyiapkan statement: " . $conn->error;
    }
} else {
    echo "ID album tidak ditentukan.";
}

$conn->close();
?>
