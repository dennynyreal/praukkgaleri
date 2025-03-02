<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (!isset($_SESSION['user_id'])) {
    header("Location: landing.php");
    exit();
}

if (isset($_GET['albumid'])) {
    $albumId = $_GET['albumid'];

    // Ambil detail album dari database
    $sql = "SELECT namaalbum, deskripsi, tanggalbuat, foto FROM album WHERE albumid = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $albumId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Detail Album</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                <style>
                    body {
                        background-color: #f7f9fc;
                        color: #333;
                    }
                    .album-title {
                        font-size: 2rem;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }
                    .album-description {
                        font-size: 1.2rem;
                        margin-bottom: 20px;
                    }
                    .album-image {
                        width: 100%;
                        height: auto;
                        border-radius: 10px;
                    }
                    .btn-custom {
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
            <div class="container mt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="album-title"><?php echo htmlspecialchars($row['namaalbum']); ?></h2>
                        <p class="album-description"><strong>Deskripsi:</strong> <?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        <p><strong>Tanggal Buat:</strong> <?php echo htmlspecialchars($row['tanggalbuat']); ?></p>
                        <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Album Image" class="album-image">
                        <br>
                        <a href="album.php" class="btn btn-primary btn-custom">Kembali ke Album</a>
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
            </body>
            </html>
            <?php
        } else {
            echo "Album tidak ditemukan.";
        }
        
        $stmt->close();
    } else {
        echo "Gagal menyiapkan statement: " . $conn->error;
    }
} else {
    echo "ID album tidak ditentukan.";
}

$conn->close();
?>
