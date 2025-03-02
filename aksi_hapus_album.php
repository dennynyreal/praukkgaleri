<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_GET['albumid'])) {
    $albumId = $_GET['albumid'];

    // Pastikan user yang ingin menghapus album memiliki hak akses
    if (!isset($_SESSION['user_id'])) {
        echo "Gagal menghapus album: User tidak terautentikasi.";
        exit();
    }

    // Menghapus album dari database hanya jika album milik user
    $sql = "DELETE FROM album WHERE albumid = ? AND userid = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ii", $albumId, $_SESSION['user_id']); // Mengikat parameter
        if ($stmt->execute()) {
            header("Location: album.php?message=Album berhasil dihapus.");
            exit();
        } else {
            echo "Gagal menghapus album: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Gagal menyiapkan statement: " . $conn->error;
    }
}

$conn->close();
?>
