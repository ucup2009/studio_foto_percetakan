<?php
include 'koneksi.php';
session_start();

if (isset($_POST['submit'])) {
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $judul     = "Untitled Asset"; // Default judul karena di DB ada kolom 'judul'
    $deskripsi = "";               // Default deskripsi

    // Ambil data file
    $nama_file   = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];
    $ukuran_file = $_FILES['gambar']['size'];

    // Cek Ekstensi
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi_file  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    if (!in_array($ekstensi_file, $ekstensi_valid)) {
        echo "<script>alert('Format file salah!'); window.location='manage_gallery.php';</script>";
        exit;
    }

    // Nama file unik
    $nama_file_baru = uniqid() . '.' . $ekstensi_file;
    
    // TENTUKAN PATH TUJUAN
    $tujuan = 'img/gallery/' . $nama_file_baru;

    // PROSES PINDAH FILE
    if (move_uploaded_file($tmp_name, $tujuan)) {
        
        // SESUAIKAN DENGAN NAMA KOLOM DATABASE KAMU: id_galeri, judul, foto, kategori, deskripsi
        $query = "INSERT INTO galeri (judul, foto, kategori, deskripsi) 
                  VALUES ('$judul', '$nama_file_baru', '$kategori', '$deskripsi')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Gambar berhasil diunggah!'); window.location='manage_gallery.php';</script>";
        } else {
            // Jika DB gagal, hapus file yang sudah terlanjur terupload
            unlink($tujuan);
            echo "Error Database: " . mysqli_error($conn);
        }
    } else {
        // ERROR INI YANG KAMU ALAMI
        echo "Gagal memindahkan file. Pastikan folder 'img/gallery' sudah ada dan memiliki izin tulis.";
    }
}
?>