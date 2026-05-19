<?php
// Sesuaikan path koneksi (naik satu folder ke root)
include '../config/koneksi.php'; 
session_start();

if (isset($_POST['submit'])) {
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $judul     = "Untitled Asset"; 
    $deskripsi = "";               

    // Ambil data file
    $nama_file   = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];
    $ukuran_file = $_FILES['gambar']['size'];

    // Cek Ekstensi
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi_file  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    if (!in_array($ekstensi_file, $ekstensi_valid)) {
        echo "<script>alert('Format file salah!'); window.location='../admin/manage_gallery.php';</script>";
        exit;
    }

    // Nama file unik
    $nama_file_baru = uniqid() . '.' . $ekstensi_file;
    
    // PERBAIKAN PATH TUJUAN: Naik satu folder ke root, lalu masuk ke img/gallery/
    $tujuan = '../img/gallery/' . $nama_file_baru;

    // Pastikan folder ../img/gallery/ sudah kamu buat secara manual!
    if (move_uploaded_file($tmp_name, $tujuan)) {
        
        // Sesuaikan kolom DB: foto (sesuai kode kamu sebelumnya)
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
        echo "Pastikan folder 'img/gallery' sudah ada di luar folder 'actions'.";
    }
}
?>