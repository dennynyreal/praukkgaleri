<?php
session_start();
include 'config.php'; // Sertakan config.php untuk koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Pictufree</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
        }
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('landing_page/view-woman-taking-picture-through-his-phone-sea.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 15px;
        }
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
        }
        .hero a {
            font-size: 1.1rem;
            padding: 12px 30px;
            border-radius: 25px;
        }
        .feature-box {
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            background-color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #3f51b5;
            margin-bottom: 15px;
        }
        .footer-custom {
            background-color: #3f51b5;
            color: #ffffff;
            padding: 15px 0;
            text-align: center;
            font-size: 0.9rem;
            margin-top: 40px;
        }
        .footer-custom a {
            color: #ffca28;
            text-decoration: none;
        }
        .footer-custom a:hover {
            color: #ffd54f;
        }
        .navbar-custom {
            background-color: #3f51b5;
            transition: background-color 0.3s ease;
        }
        .navbar-custom .nav-link {
            color: #fff;
            transition: color 0.3s ease;
        }
        .navbar-custom .nav-link:hover {
            color: #ffca28;
        }
        .modal-content {
            border-radius: 15px;
        }
        .modal-header, .modal-footer {
            background-color: #3f51b5;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .modal-body {
            padding: 30px;
            background-color: #f7f8fa;
        }
        .modal-header .btn-close {
            background-color: white;
            border-radius: 50%;
        }
        .section-title {
            font-size: 2rem;
            margin-bottom: 40px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-camera-retro"></i> Pictufree
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-warning text-white ms-lg-2 mt-2 mt-lg-0" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1>Selamat Datang di Pictufree</h1>
        <p>Galeri foto online untuk berbagi dan menemukan foto-foto indah.</p>
        <a href="landing.php" class="btn btn-light btn-lg">Mulai Sekarang</a>
    </div>
</div>

<!-- About Section -->
<div class="container my-5 text-center">
    <h2 class="section-title">Tentang Pictufree</h2>
    <p>Dengan Pictufree, Anda dapat mengunggah, membagikan, dan menemukan foto-foto menarik dari berbagai pengguna. Bergabunglah dengan komunitas kami dan nikmati berbagai konten visual yang inspiratif.</p>
</div>

<!-- Features Section -->
<div class="container my-5">
    <h2 class="text-center section-title">Fitur Kami</h2>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="feature-box">
                <i class="fas fa-camera-retro feature-icon"></i>
                <h4>Unggah Foto</h4>
                <p>Unggah foto-foto Anda dan bagikan dengan komunitas kami.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-box">
                <i class="fas fa-thumbs-up feature-icon"></i>
                <h4>Suka Foto</h4>
                <p>Berikan dukungan kepada foto favorit Anda dengan memberi like.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-box">
                <i class="fas fa-comments feature-icon"></i>
                <h4>Komentar</h4>
                <p>Beri komentar dan terlibat dalam diskusi dengan pengguna lain.</p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer-custom">
    <span>&copy; 2024 Pictufree. All rights reserved.</span> | 
    <a href="#">Privacy Policy</a> | 
    <a href="#">Terms of Service</a> | 
    <a href="#">Contact Us</a>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="registerUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="registerUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="namalengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="namalengkap" name="namalengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="registerEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="registerPassword" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
