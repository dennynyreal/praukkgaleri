<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (isset($_GET['fotoid'])) {
    $fotoId = $_GET['fotoid'];

    // Mengambil detail foto dari database
    $sql = "SELECT * FROM foto WHERE fotoid = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $fotoId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $foto = $result->fetch_assoc();
        } else {
            echo "Foto tidak ditemukan.";
            exit();
        }
        $stmt->close();
    } else {
        echo "Gagal menyiapkan statement: " . $conn->error;
        exit();
    }
} else {
    echo "ID foto tidak ditentukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Foto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2><?php echo htmlspecialchars($foto['judulfoto']); ?></h2>
  <img src="<?php echo htmlspecialchars($foto['lokasifile']); ?>" alt="<?php echo htmlspecialchars($foto['judulfoto']); ?>" class="img-fluid mb-3">
  <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($foto['deskripsifoto']); ?></p>
  <p><strong>Tanggal Unggah:</strong> <?php echo htmlspecialchars($foto['tanggalunggah']); ?></p>
  <!-- <p><strong>Lokasi File:</strong> <?php echo htmlspecialchars($foto['lokasifile']); ?></p> -->
  <!-- <p><strong>Album ID:</strong> <?php echo htmlspecialchars($foto['albumid']); ?></p> -->
  <a href="foto.php" class="btn btn-secondary">Kembali ke Daftar Foto</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
