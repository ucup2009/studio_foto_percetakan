<?php
include '../config/koneksi.php'; 
session_start();

// Proteksi Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['submit'])) {
    // Ambil data dari form dan amankan dari SQL Injection
    $nama_layanan = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $harga        = mysqli_real_escape_string($conn, $_POST['harga']);
    $deskripsi    = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Query INSERT sesuai dengan kolom di phpMyAdmin kamu
    $query = "INSERT INTO layanan (nama_layanan, harga, deskripsi) 
              VALUES ('$nama_layanan', '$harga', '$deskripsi')";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, kembali ke halaman manajemen layanan tambahan
        echo "<script>alert('Layanan baru berhasil ditambahkan!'); window.location='../admin/manage_addons.php';</script>";
        exit;
    } else {
        // Jika gagal, tampilkan pesan error database
        echo "Error Database: " . mysqli_error($conn);
    }
} else {
    // Jika mencoba akses langsung tanpa submit form
    header("Location: ../admin/tambah_addon.php");
    exit;
}
?>