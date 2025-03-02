<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database

if (!isset($_SESSION['user_id'])) {
  header("Location: landing.php");
  exit();
}

// Tentukan halaman saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Ambil kata kunci pencarian jika ada
$search_keyword = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Ambil data album dari database untuk user yang sedang login
$sql = "SELECT albumid, namaalbum, deskripsi, tanggalbuat 
        FROM album 
        WHERE userid = ? 
        AND (namaalbum LIKE ? OR deskripsi LIKE ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $_SESSION['user_id'], $search_keyword, $search_keyword);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) { // Periksa apakah query gagal
  echo "Error: " . $conn->error; // Tampilkan pesan kesalahan
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Album - Pictufree</title>
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

    .card {
      border: none;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .card-body {
      padding: 1rem;
    }

    .btn-custom {
      color: #f4f6f9;
      transition: color 0.3s ease;
    }

    .btn-custom:hover {
      color: #ffca28;
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

    /* Tambahkan margin antar elemen pada tampilan desktop */
    .row.align-items-center {
      margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {

      /* Pada layar kecil, headline tetap di kiri */
      .row.align-items-center .col-md-6:first-child {
        text-align: left;
      }

      /* Atur elemen untuk tampilan vertikal */
      .row.align-items-center {
        flex-direction: column;
        align-items: stretch;
      }

      /* .row.align-items-center .col-md-6 {
        margin-bottom: 1rem;
        
      } */

      /* Form pencarian memenuhi lebar */
      .input-group {
        width: 100%;
      }
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

  <!-- Content -->
  <div class="container my-5">
    <!-- Search Form -->
    <div class="row mb-4">
      <div class="col-12">
        <form class="d-flex flex-wrap justify-content-between align-items-center" method="GET" action="album.php">
          <h2 class="text-primary mb-3 mb-md-6">Data Album</h2>
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari album..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary btn-custom">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Button Tambah Album -->
    <div class="d-flex justify-content-end mb-4">
      <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addAlbumModal">
        <i class="fas fa-plus-circle"></i> Tambah Album
      </button>
    </div>

    <!-- Album Cards -->
    <div class="row">
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<div class="col-md-4 mb-4">';
          echo '  <div class="card">';
          echo '    <div class="card-body">';
          echo '      <h5 class="card-title">' . htmlspecialchars($row['namaalbum']) . '</h5>';
          echo '      <p class="card-text">' . htmlspecialchars($row['deskripsi']) . '</p>';
          echo '      <p class="card-text"><small class="text-muted">Tanggal: ' . htmlspecialchars($row['tanggalbuat']) . '</small></p>';
          echo '      <div class="d-flex justify-content-end">';
          echo '        <div class="dropdown">';
          echo '          <button class="btn btn-warning" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
          echo '            <strong>:</strong>';
          echo '          </button>';
          echo '          <ul class="dropdown-menu">';
          echo '            <li><a class="dropdown-item edit-album" data-id="' . htmlspecialchars($row['albumid']) . '" data-name="' . htmlspecialchars($row['namaalbum']) . '" data-description="' . htmlspecialchars($row['deskripsi']) . '" href="#" data-bs-toggle="modal" data-bs-target="#editAlbumModal">Edit</a></li>';
          echo '            <li><a class="dropdown-item text-danger" href="aksi_hapus_album.php?albumid=' . htmlspecialchars($row['albumid']) . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus album ini?\');">Hapus</a></li>';
          echo '          </ul>';
          echo '        </div>';
          echo '      </div>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
        }
      } else {
        echo '<p class="text-muted">Tidak ada album yang ditemukan untuk pencarian: <strong>' . htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') . '</strong>.</p>';
      }
      ?>
    </div>
  </div>

  <!-- Modal for Adding Album -->
  <div class="modal fade" id="addAlbumModal" tabindex="-1" aria-labelledby="addAlbumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addAlbumModalLabel"><i class="fas fa-plus-circle me-2"></i>Tambah Album Baru</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="aksi_album.php" method="POST">
            <div class="mb-3">
              <label class="form-label">Nama Album</label>
              <input type="text" name="namaalbum" class="form-control animated-input" placeholder="Masukkan nama album" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control animated-input" name="deskripsi" placeholder="Tambahkan deskripsi singkat" required></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-custom" name="tambah"><i class="fas fa-save me-1"></i> Simpan Album</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Editing Album -->
  <div class="modal fade" id="editAlbumModal" tabindex="-1" aria-labelledby="editAlbumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="editAlbumModalLabel"><i class="fas fa-edit me-2"></i>Edit Album</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editAlbumForm" action="aksi_edit_album.php" method="POST">
            <input type="hidden" name="albumid" id="editAlbumId">
            <div class="mb-3">
              <label class="form-label">Nama Album</label>
              <input type="text" name="namaalbum" class="form-control" id="editAlbumName" placeholder="Masukkan nama album" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" id="editAlbumDescription" placeholder="Edit deskripsi singkat" required></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer-custom">
    <span>&copy; 2024 Pictufree. All rights reserved.</span> | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a> | <a href="#">Contact Us</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Script untuk mengisi form edit dengan data album
    document.addEventListener('DOMContentLoaded', function() {
      const editLinks = document.querySelectorAll('.edit-album');
      editLinks.forEach(link => {
        link.addEventListener('click', function() {
          const albumId = this.getAttribute('data-id');
          const albumName = this.getAttribute('data-name');
          const albumDescription = this.getAttribute('data-description');

          // Isi data ke dalam form edit
          document.getElementById('editAlbumId').value = albumId;
          document.getElementById('editAlbumName').value = albumName;
          document.getElementById('editAlbumDescription').value = albumDescription;
        });
      });
    });
  </script>

</body>

</html>

<?php
$conn->close(); // Tutup koneksi database
?>