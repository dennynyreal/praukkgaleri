<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_POST['fotoid']) && isset($_POST['isikomentar'])) {
    $fotoid = $_POST['fotoid'];
    $userid = $_SESSION['user_id']; // Ambil user ID dari session
    $tanggalkomentar = date('Y-m-d');

    // Query untuk menambah komentar
    $sql = "INSERT INTO komentarfoto (fotoid, userid, isikomentar, tanggalkomentar) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $fotoid, $userid, $_POST['isikomentar'], $tanggalkomentar); // Menyimpan isi komentar dari POST

    if ($stmt->execute()) {
        header("Location: index.php"); // Kembali ke halaman galeri setelah menambah komentar
        exit();
    } else {
        echo "Gagal menambahkan komentar: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
