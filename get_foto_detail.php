<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_GET['fotoid'])) {
    $fotoid = $_GET['fotoid'];

    // Ambil detail foto
    $sqlFoto = "SELECT fotoid, judulfoto, deskripsifoto, lokasifile FROM foto WHERE fotoid = ?";
    $stmtFoto = $conn->prepare($sqlFoto);
    $stmtFoto->bind_param("i", $fotoid);
    $stmtFoto->execute();
    $resultFoto = $stmtFoto->get_result();
    $fotoDetail = $resultFoto->fetch_assoc();

    // Ambil komentar dengan username
    $sqlKomentar = "
        SELECT k.userid, u.username AS user, k.isikomentar, k.tanggalkomentar 
        FROM komentarfoto k 
        JOIN user u ON k.userid = u.userid 
        WHERE k.fotoid = ?
    ";
    $stmtKomentar = $conn->prepare($sqlKomentar);
    $stmtKomentar->bind_param("i", $fotoid);
    $stmtKomentar->execute();
    $resultKomentar = $stmtKomentar->get_result();

    $komentarList = [];
    while ($komentar = $resultKomentar->fetch_assoc()) {
        $komentarList[] = $komentar; // Menyimpan komentar ke dalam array
    }

    // Kembalikan data dalam format JSON
    echo json_encode([
        'lokasifile' => $fotoDetail['lokasifile'],
        'judulfoto' => $fotoDetail['judulfoto'],
        'deskripsifoto' => $fotoDetail['deskripsifoto'],
        'komentar' => $komentarList
    ]);
} else {
    echo json_encode(['error' => 'Foto ID tidak valid.']);
}

$stmtFoto->close(); // Tutup statement foto
$stmtKomentar->close(); // Tutup statement komentar
$conn->close(); // Tutup koneksi database
?>
