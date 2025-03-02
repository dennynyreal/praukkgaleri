<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: landing.php");
    exit();
}

include 'config.php';

// Dapatkan nama file halaman saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Cek apakah ada parameter pencarian
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Mengambil data foto dengan jumlah like, serta filter berdasarkan kata kunci
$sqlFoto = "
    SELECT f.fotoid, f.judulfoto, f.deskripsifoto, f.tanggalunggah, f.lokasifile, f.userid, 
           (SELECT COUNT(*) FROM likefoto WHERE fotoid = f.fotoid) AS like_count
    FROM foto f
    WHERE f.judulfoto LIKE '%$search%' OR f.deskripsifoto LIKE '%$search%'
";
$resultFoto = $conn->query($sqlFoto);

// Cek jika query berhasil
if (!$resultFoto) {
    echo "Error: " . $conn->error;
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto - Pictufree</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .navbar-custom {
            background-color: #3f51b5;
            padding: 0.8rem 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: #fff;
            font-weight: bold;
            font-size: 1.6rem;
            margin-left: 10px;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            margin-right: 1rem;
            font-weight: 500;
        }

        .navbar-nav .nav-link.active {
            color: #ffca28 !important;
        }

        .navbar-nav .nav-link:hover {
            background-color: #ffca28;
            /* Ganti warna latar belakang saat hover */
            color: #3f51b5 !important;
            /* Ganti warna teks saat hover */
            border-radius: 5px;
            /* Tambahkan border radius */
        }

        .card {
            border: none;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card-img-top:hover {
            transform: scale(1.05);
        }

        .card-body {
            padding: 1rem;
        }

        .btn-like {
            color: #ff5252;
            transition: color 0.3s ease;
        }

        .btn-like:hover {
            color: #ff1744;
        }

        .footer-custom {
            background-color: #3f51b5;
            color: #ffffff;
            padding: 1rem 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1030;
            /* Membuatnya tetap di atas konten lain */
        }

        .footer-custom a {
            color: #ffca28;
            text-decoration: none;
        }

        .footer-custom a:hover {
            color: #ffd54f;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-camera-retro"></i> Pictufree
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Formulir Pencarian -->
    <div class="container my-4">
        <form method="GET" action="">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari foto..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
    </div>

    <div class="container my-5">
        <h2 class="text-primary mb-4">Galeri Foto</h2>
        <div class="row">
            <?php
            if ($resultFoto->num_rows > 0) {
                while ($row = $resultFoto->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '  <div class="card">';
                    echo '    <img src="' . htmlspecialchars($row['lokasifile']) . '" class="card-img-top" alt="' . htmlspecialchars($row['judulfoto']) . '" data-bs-toggle="modal" data-bs-target="#detailModal" data-fotoid="' . $row['fotoid'] . '">';
                    echo '    <div class="card-body">';
                    echo '      <h5 class="card-title">' . htmlspecialchars($row['judulfoto']) . '</h5>';
                    echo '      <p class="card-text">' . htmlspecialchars($row['deskripsifoto']) . '</p>';
                    echo '      <p class="card-text"><small class="text-muted">Tanggal Unggah: ' . htmlspecialchars($row['tanggalunggah']) . '</small></p>';
                    echo '        <button class="btn btn-danger btn-delete" data-fotoid="' . $row['fotoid'] . '">Banned</button>';
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted text-center">Tidak ada foto yang ditemukan.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Modal for Detail Foto -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="modalFoto" class="img-fluid" alt="">
                    <h5 id="modalJudul"></h5>
                    <p id="modalDeskripsi"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-custom">
        <span>&copy; 2024 Pictufree. All rights reserved.</span> | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a> | <a href="#">Contact Us</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        // Script untuk mengisi modal detail foto
        document.addEventListener('DOMContentLoaded', function() {
            const fotoImages = document.querySelectorAll('.card-img-top');
            fotoImages.forEach(image => {
                image.addEventListener('click', function() {
                    const fotoid = this.getAttribute('data-fotoid');
                    fetch('get_foto_detail.php?fotoid=' + fotoid)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('modalFoto').src = data.lokasifile;
                            document.getElementById('modalJudul').innerText = data.judulfoto;
                            document.getElementById('modalDeskripsi').innerText = data.deskripsifoto;

                            let komentarHtml = '';
                            data.komentar.forEach(komentar => {
                                komentarHtml += `<p><strong>${komentar.user}:</strong> ${komentar.isikomentar} <small>(${komentar.tanggalkomentar})</small></p>`;
                            });
                            document.getElementById('modalKomentarContainer').innerHTML = komentarHtml;
                        });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fotoid = this.getAttribute('data-fotoid');
                    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                        fetch('aksi_delete.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: new URLSearchParams({
                                    'fotoid': fotoid
                                })
                            })
                            .then(response => response.text())
                            .then(data => {
                                if (data === 'Foto berhasil dihapus.') {
                                    alert(data);
                                    location.reload();
                                } else {
                                    alert('Gagal menghapus foto: ' + data);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            });
        });
    </script>

</body>

</html>

<?php
$conn->close(); // Tutup koneksi database
?>