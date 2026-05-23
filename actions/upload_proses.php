<?php
// Sesuaikan path koneksi (naik satu folder ke root)
include '../config/koneksi.php'; 
session_start();

if (isset($_POST['submit'])) {
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori'] ?? '');
    $judul     = "Untitled Asset"; 
    $deskripsi = "";               

    // 1. Pastikan file benar-benar dipilih dan tidak ada error upload dari browser
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
        echo "<script>alert('Silakan pilih file gambar terlebih dahulu!'); window.location='../admin/manage_gallery.php';</script>";
        exit;
    }

    // Ambil data file secara aman
    $nama_file   = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];
    $ukuran_file = $_FILES['gambar']['size'];

    // 2. Cek Ukuran File (Contoh: Maksimal 5 MB = 5 * 1024 * 1024 bytes)
    if ($ukuran_file > 5242880) {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB.'); window.location='../admin/manage_gallery.php';</script>";
        exit;
    }

    // Cek Ekstensi
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi_file  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    if (!in_array($ekstensi_file, $ekstensi_valid)) {
        echo "<script>alert('Format file salah! Hanya mendukung JPG, JPEG, PNG, dan WEBP.'); window.location='../admin/manage_gallery.php';</script>";
        exit;
    }

    // Nama file unik
    $nama_file_baru = uniqid() . '.' . $ekstensi_file;
    
    // Path tujuan
    $tujuan = '../img/gallery/' . $nama_file_baru;

    if (move_uploaded_file($tmp_name, $tujuan)) {
        
        // Sesuaikan kolom DB
        $query = "INSERT INTO galeri (judul, foto, kategori, deskripsi) 
                  VALUES ('$judul', '$nama_file_baru', '$kategori', '$deskripsi')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Gambar berhasil diunggah!'); window.location='../admin/manage_gallery.php';</script>";
        } else {
            // Jika DB gagal, hapus file fisik
            unlink($tujuan);
            echo "Error Database: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal memindahkan file ke: " . $tujuan . "<br>";
        echo "Pastikan folder 'img/gallery' sudah ada dan memiliki izin tulis (write permission).";
    }
}
?>