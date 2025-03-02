<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_POST['fotoid'])) {
    $fotoid = $_POST['fotoid'];
    $userid = $_SESSION['user_id']; // Ambil user ID dari session
    $tanggallike = date('Y-m-d');

    // Cek apakah pengguna sudah memberi like pada foto ini
    $sqlCheck = "SELECT * FROM likefoto WHERE fotoid = ? AND userid = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $fotoid, $userid);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows === 0) {
        // Jika belum pernah like, tambahkan like baru
        $sqlLike = "INSERT INTO likefoto (fotoid, userid, tanggallike) VALUES (?, ?, ?)";
        $stmtLike = $conn->prepare($sqlLike);
        $stmtLike->bind_param("iis", $fotoid, $userid, $tanggallike);

        if ($stmtLike->execute()) {
            echo "Like ditambahkan.";
        } else {
            echo "Gagal menambah like: " . $conn->error;
        }

        $stmtLike->close();
    } else {
        echo "Anda sudah memberi like pada foto ini.";
    }

    $stmtCheck->close();
}

$conn->close();
?>
