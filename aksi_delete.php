<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Akses ditolak.";
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fotoid = isset($_POST['fotoid']) ? $conn->real_escape_string($_POST['fotoid']) : '';

    if (!empty($fotoid)) {
        $sqlDelete = "DELETE FROM foto WHERE fotoid = '$fotoid'";

        if ($conn->query($sqlDelete)) {
            echo "Foto berhasil dihapus.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "ID foto tidak valid.";
    }
} else {
    echo "Metode tidak diizinkan.";
}

$conn->close();
