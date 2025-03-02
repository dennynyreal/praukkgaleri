<?php
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_GET['albumid'])) {
    $albumId = $_GET['albumid'];

    $sql = "SELECT namaalbum FROM album WHERE albumid = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $albumId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $album = $result->fetch_assoc();
            echo htmlspecialchars($album['namaalbum']);
        } else {
            echo "Album tidak ditemukan.";
        }

        $stmt->close();
    } else {
        echo "Gagal menyiapkan statement: " . $conn->error;
    }
} else {
    echo "Album ID tidak ditentukan.";
}

$conn->close();
?>
