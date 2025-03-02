<?php
session_start();
include 'config.php'; // Sertakan konfigurasi database

// Periksa apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: index_admin.php"); // Arahkan ke admin dashboard
    } else {
        header("Location: index.php"); // Arahkan ke halaman utama untuk user
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mendapatkan user berdasarkan username
    $sql = "SELECT userid, password, role FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan informasi ke dalam sesi
            $_SESSION['user_id'] = $user['userid'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                header("Location: index_admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION['message'] = "Password salah.";
        }
    } else {
        $_SESSION['message'] = "Username tidak ditemukan.";
    }

    $stmt->close();
}

$conn->close();
