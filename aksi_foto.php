<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_POST['tambah'])) {
    $judulfoto = $_POST['judulfoto'];
    $deskripsifoto = $_POST['deskripsifoto'];
    $tanggalunggah = $_POST['tanggalunggah'];
    $albumid = $_POST['albumid'];
    $userid = $_SESSION['user_id']; // Ambil user ID dari session

    // Proses upload foto
    $lokasifile = 'uploads/' . basename($_FILES['lokasifile']['name']);
    if (move_uploaded_file($_FILES['lokasifile']['tmp_name'], $lokasifile)) {
        $sql = "INSERT INTO foto (judulfoto, deskripsifoto, tanggalunggah, lokasifile, albumid, userid) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $judulfoto, $deskripsifoto, $tanggalunggah, $lokasifile, $albumid, $userid);

        if ($stmt->execute()) {
            header("Location: foto.php");
            exit();
        } else {
            echo "Gagal menambah foto: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Maaf, terjadi kesalahan saat mengupload gambar.";
    }
}

$conn->close();
?>
