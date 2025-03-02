<?php
session_start(); // Memulai sesi
include 'config.php'; // Sertakan konfigurasi database

// Memeriksa apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $namalengkap = $_POST['namalengkap'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Meng-hash password
    $alamat = $_POST['alamat'];
    $role = 'user'; // Default role sebagai 'user'

    // Validasi: Periksa apakah username atau email sudah ada
    $check_sql = "SELECT username, email FROM user WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "Username atau Email sudah terdaftar!";
    } else {
        // Menyiapkan dan mengikat parameter
        $stmt = $conn->prepare("INSERT INTO user (username, namalengkap, email, password, alamat, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $namalengkap, $email, $password, $alamat, $role);

        // Mengeksekusi pernyataan dan memeriksa keberhasilan
        if ($stmt->execute()) {
            $_SESSION['message'] = "Pendaftaran Berhasil, Silahkan Login"; // Pesan sukses
            header("Location: landing.php"); // Alihkan ke landing.php
            exit();
        } else {
            $_SESSION['message'] = "Pendaftaran Gagal: " . htmlspecialchars($stmt->error); // Pesan kesalahan
        }

        $stmt->close(); // Menutup pernyataan
    }

    $check_stmt->close(); // Menutup pernyataan validasi
}

$conn->close(); // Menutup koneksi
