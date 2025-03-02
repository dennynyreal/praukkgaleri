<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_POST['fotoid'])) {
    $fotoid = $_POST['fotoid'];
    $judulfoto = $_POST['judulfoto'];
    $deskripsifoto = $_POST['deskripsifoto'];
    $albumid = $_POST['albumid'];
    $lokasifile = $_FILES['lokasifile']['name'] ? 'uploads/' . basename($_FILES['lokasifile']['name']) : null;

    // Update foto
    if ($lokasifile) {
        // Update foto jika ada file baru
        move_uploaded_file($_FILES['lokasifile']['tmp_name'], $lokasifile);
        $sql = "UPDATE foto SET judulfoto = ?, deskripsifoto = ?, lokasifile = ?, albumid = ? WHERE fotoid = ? AND userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiisi", $judulfoto, $deskripsifoto, $lokasifile, $albumid, $fotoid, $_SESSION['user_id']);
    } else {
        // Jika tidak ada file baru, hanya update judul, deskripsi, dan album
        $sql = "UPDATE foto SET judulfoto = ?, deskripsifoto = ?, albumid = ? WHERE fotoid = ? AND userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $judulfoto, $deskripsifoto, $albumid, $fotoid, $_SESSION['user_id']);
    }

    if ($stmt->execute()) {
        header("Location: foto.php");
        exit();
    } else {
        echo "Gagal memperbarui foto: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
