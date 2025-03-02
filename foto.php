<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (!isset($_SESSION['user_id'])) {
  header("Location: landing.php");
  exit();
}

// Atur halaman aktif
$current_page = 'foto.php';

// Ambil data album untuk dropdown hanya untuk pengguna yang sedang login
$sqlAlbum = "SELECT albumid, namaalbum FROM album WHERE userid = ?";
$stmtAlbum = $conn->prepare($sqlAlbum);
$stmtAlbum->bind_param("i", $_SESSION['user_id']);
$stmtAlbum->execute();
$resultAlbum = $stmtAlbum->get_result();

// Cek apakah ada pencarian
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil data foto dari database untuk user yang sedang login dan sesuai dengan kata kunci pencarian
$sqlFoto = "SELECT fotoid, judulfoto, deskripsifoto, tanggalunggah, lokasifile, albumid 
            FROM foto 
            WHERE userid = ? AND (judulfoto LIKE ? OR deskripsifoto LIKE ?)";
$stmtFoto = $conn->prepare($sqlFoto);
$searchTerm = '%' . $searchKeyword . '%';
$stmtFoto->bind_param("iss", $_SESSION['user_id'], $searchTerm, $searchTerm);
$stmtFoto->execute();
$resultFoto = $stmtFoto->get_result();

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
  <title>Foto - Pictufree</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      color: #333;
      padding-bottom: 60px;
      /* Tambahan untuk memberi ruang pada footer */
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
      color: #3f51b5 !important;
      border-radius: 5px;
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
    }

    .footer-custom a {
      color: #ffca28;
      text-decoration: none;
    }

    .footer-custom a:hover {
      color: #ffd54f;
    }

    .foto-card {
      transition: transform 0.3s, box-shadow 0.3s;
      border-radius: 15px;
      overflow: hidden;
      border: 1px solid #e0e0e0;
      background-color: #ffffff;
    }

    .foto-card img {
      height: 200px;
      /* Set height for consistency */
      object-fit: cover;
      /* Cover ensures the aspect ratio is maintained */
      width: 100%;
      /* Full width */
    }

    .foto-card:hover {
      transform: scale(1.03);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-custom {
      border-radius: 25px;
      padding: 0.5rem 1.5rem;
      transition: background-color 0.3s ease;
    }

    .modal-header {
      background-color: #3f51b5;
      color: #fff;
    }

    .modal-title {
      color: #fff;
    }

    .animated-input {
      transition: border-color 0.3s ease;
    }

    .animated-input:focus {
      border-color: #007bff;
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
          <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'album.php') ? 'active' : ''; ?>" href="album.php">Album</a></li>
          <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'foto.php') ? 'active' : ''; ?>" href="foto.php">Foto</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Modal for Adding Foto -->
  <div class="modal fade" id="addFotoModal" tabindex="-1" aria-labelledby="addFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addFotoModalLabel">Tambah Foto</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="aksi_foto.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Judul Foto</label>
              <input type="text" name="judulfoto" class="form-control animated-input" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <textarea class="form-control animated-input" name="deskripsifoto" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Upload Foto</label>
              <input type="file" name="lokasifile" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Unggah</label>
              <input type="date" name="tanggalunggah" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Pilih Album</label>
              <select name="albumid" class="form-select" required>
                <option value="">-- Pilih Album --</option>
                <?php while ($album = $resultAlbum->fetch_assoc()) : ?>
                  <option value="<?php echo $album['albumid']; ?>"><?php echo htmlspecialchars($album['namaalbum']); ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <input type="hidden" name="userid" value="<?php echo $_SESSION['user_id']; ?>">
            <button type="submit" class="btn btn-primary btn-custom" name="tambah">Tambah Foto</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Editing Foto -->
  <div class="modal fade" id="editFotoModal" tabindex="-1" aria-labelledby="editFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="editFotoModalLabel">Edit Foto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editFotoForm" action="aksi_edit_foto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="fotoid" id="editFotoId">
            <div class="mb-3">
              <label class="form-label">Judul Foto</label>
              <input type="text" name="judulfoto" class="form-control" id="editJudulFoto" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <textarea class="form-control" name="deskripsifoto" id="editDeskripsiFoto" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Upload Foto (biarkan kosong jika tidak ingin mengganti)</label>
              <input type="file" name="lokasifile" class="form-control" id="editLokasiFile" accept="image/*">
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Unggah</label>
              <input type="date" name="tanggalunggah" class="form-control" id="editTanggal" value="<?php echo date('Y-m-d'); ?>" readonly required>
            </div>
            <div class="mb-3">
              <label class="form-label">Pilih Album</label>
              <select name="albumid" class="form-select" id="editAlbumId" required>
                <option value="">-- Pilih Album --</option>
                <?php
                $resultAlbum->data_seek(0); // Reset pointer hasil album
                while ($album = $resultAlbum->fetch_assoc()) : ?>
                  <option value="<?php echo $album['albumid']; ?>"><?php echo htmlspecialchars($album['namaalbum']); ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5">
      <!-- Pencarian -->
      <form class="mb-4" method="GET" action="foto.php">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Cari foto berdasarkan kategori" value="<?php echo htmlspecialchars($searchKeyword); ?>">
          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
        </div>
      </form>

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Data Foto</h2>
        <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addFotoModal">
          <i class="fas fa-plus-circle"></i> Tambah Foto
        </button>
      </div>

      <div class="row">
        <?php
        if ($resultFoto->num_rows > 0) {
          while ($row = $resultFoto->fetch_assoc()) {
            echo '<div class="col-md-4 mb-4">';
            echo '  <div class="card foto-card shadow-sm">';
            echo '    <img src="' . htmlspecialchars($row['lokasifile']) . '" class="card-img-top" alt="' . htmlspecialchars($row['judulfoto']) . '">';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . htmlspecialchars($row['judulfoto']) . '</h5>';
            echo '      <p class="card-text">' . htmlspecialchars($row['deskripsifoto']) . '</p>';
            echo '      <p class="card-text"><small class="text-muted">Tanggal Unggah: ' . htmlspecialchars($row['tanggalunggah']) . '</small></p>';
            echo '      <div class="d-flex justify-content-end">';
            echo '        <div class="dropdown">';
            echo '          <button class="btn btn-warning" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
            echo '          <strong>:</strong>';
            echo '          </button>';
            echo '          <ul class="dropdown-menu">';
            echo '            <li><a class="dropdown-item edit-foto" data-id="' . htmlspecialchars($row['fotoid']) . '" data-judul="' . htmlspecialchars($row['judulfoto']) . '" data-deskripsi="' . htmlspecialchars($row['deskripsifoto']) . '" data-lokasi="' . htmlspecialchars($row['lokasifile']) . '" data-tanggal="' . htmlspecialchars($row['tanggalunggah']) . '" data-albumid="' . htmlspecialchars($row['albumid']) . '" href="#" data-bs-toggle="modal" data-bs-target="#editFotoModal">Edit</a></li>';
            echo '            <li><a class="dropdown-item text-danger" href="aksi_hapus_foto.php?fotoid=' . htmlspecialchars($row['fotoid']) . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus foto ini?\');">Hapus</a></li>';
            echo '          </ul>';
            echo '        </div>';
            echo '      </div>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
          }
        } else {
          echo '<p class="text-muted">Tidak ada foto yang ditemukan.</p>';
        }
        ?>
      </div>
    </div>


    <!-- Modal for Detail Foto -->
    <div class="modal fade" id="detailFotoModal" tabindex="-1" aria-labelledby="detailFotoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detailFotoModalLabel">Detail Foto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h2 id="detailJudulFoto"></h2>
            <img id="detailLokasiFile" src="" alt="" class="img-fluid mb-3" style="max-height: 400px; object-fit: cover;">
            <p><strong>Kategori:</strong> <span id="detailDeskripsiFoto"></span></p>
            <p><strong>Tanggal Unggah:</strong> <span id="detailTanggal"></span></p>
            <p><strong>Album:</strong> <span id="detailAlbumName"></span></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer-custom">
      <span>&copy; 2024 Pictufree. All rights reserved.</span>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact Us</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
      // Script untuk mengisi form edit dengan data foto
      document.addEventListener('DOMContentLoaded', function() {
        const editLinks = document.querySelectorAll('.edit-foto');
        editLinks.forEach(link => {
          link.addEventListener('click', function() {
            const fotoId = this.getAttribute('data-id');
            const judulFoto = this.getAttribute('data-judul');
            const deskripsiFoto = this.getAttribute('data-deskripsi');
            const lokasiFile = this.getAttribute('data-lokasi');
            const tanggal = this.getAttribute('data-tanggal');
            const albumId = this.getAttribute('data-albumid');

            // Isi data ke dalam form edit
            document.getElementById('editFotoId').value = fotoId;
            document.getElementById('editJudulFoto').value = judulFoto;
            document.getElementById('editDeskripsiFoto').value = deskripsiFoto;
            document.getElementById('editLokasiFile').value = lokasiFile;
            document.getElementById('editTanggal').value = new Date().toISOString().split('T')[0];
            document.getElementById('editAlbumId').value = albumId;
          });
        });

        // Script untuk mengisi modal detail dengan data foto
        const detailLinks = document.querySelectorAll('.dropdown-item[data-bs-target="#detailFotoModal"]');
        detailLinks.forEach(link => {
          link.addEventListener('click', function() {
            const judulFoto = this.getAttribute('data-judul');
            const deskripsiFoto = this.getAttribute('data-deskripsi');
            const lokasiFile = this.getAttribute('data-lokasi');
            const tanggal = this.getAttribute('data-tanggal');
            const albumId = this.getAttribute('data-albumid');

            // Isi data ke dalam modal detail
            document.getElementById('detailJudulFoto').innerText = judulFoto;
            document.getElementById('detailDeskripsiFoto').innerText = deskripsiFoto;
            document.getElementById('detailLokasiFile').src = lokasiFile;
            document.getElementById('detailTanggal').innerText = tanggal;

            // Ambil nama album berdasarkan albumId
            if (albumId) {
              fetch('get_album_name.php?albumid=' + albumId)
                .then(response => response.text())
                .then(data => {
                  document.getElementById('detailAlbumName').innerText = data;
                })
                .catch(error => console.error('Error:', error));
            } else {
              document.getElementById('detailAlbumName').innerText = "Album tidak ditemukan.";
            }
          });
        });
      });
    </script>

</body>

</html>

<?php
$stmtFoto->close(); // Tutup statement foto
$stmtAlbum->close(); // Tutup statement album
$conn->close(); // Tutup koneksi database
?>