<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_POST['tambah'])) {
    // Pastikan sesi sudah dimulai sebelum mengakses $_SESSION
    if (!isset($_SESSION['user_id'])) {
        echo "Gagal menambah album: User ID tidak tersedia.";
        exit();
    }

    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi'];
    $userid = $_SESSION['user_id']; // Ambil userid dari sesi
    $tanggalbuat = date('Y-m-d H:i:s'); // Mendapatkan tanggal dan waktu saat ini

    // Menyimpan informasi album ke database
    $sql = "INSERT INTO album (namaalbum, deskripsi, tanggalbuat, userid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) { // Memastikan statement berhasil disiapkan
        $stmt->bind_param("sssi", $namaalbum, $deskripsi, $tanggalbuat, $userid); // Menghapus parameter untuk foto

        if ($stmt->execute()) {
            header("Location: album.php?message=Album berhasil ditambahkan.");
            exit();
        } else {
            echo "Gagal menambah album: " . $conn->error;
        }
        $stmt->close(); // Tutup statement
    } else {
        echo "Gagal menyiapkan statement: " . $conn->error;
    }
}

$conn->close();
?>
