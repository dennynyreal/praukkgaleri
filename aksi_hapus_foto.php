<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_GET['fotoid'])) {
    $fotoid = $_GET['fotoid'];

    // Ambil nama file gambar untuk dihapus
    $sql = "SELECT lokasifile FROM foto WHERE fotoid = ? AND userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $fotoid, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lokasifile = $row['lokasifile'];

        // Hapus file dari server
        if (file_exists($lokasifile)) {
            unlink($lokasifile); // Menghapus file
        }

        // Hapus data dari database
        $sql = "DELETE FROM foto WHERE fotoid = ? AND userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $fotoid, $_SESSION['user_id']);

        if ($stmt->execute()) {
            header("Location: foto.php");
            exit();
        } else {
            echo "Gagal menghapus foto: " . $conn->error;
        }
    } else {
        echo "Foto tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.";
    }

    $stmt->close();
}

$conn->close();
?>
