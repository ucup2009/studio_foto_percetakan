<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    exit;
}

$id = $_GET['id'];

// Keamanan: Cek agar admin tidak menghapus dirinya sendiri melalui URL
$query_cek = mysqli_query($conn, "SELECT email FROM users WHERE id_user = '$id'");
$data = mysqli_fetch_assoc($query_cek);

if ($data['email'] == $_SESSION['email']) {
    echo "<script>alert('Anda tidak bisa menghapus akun Anda sendiri!'); window.location='manage_user.php';</script>";
} else {
    $hapus = mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id'");
    if ($hapus) {
        header("Location: manage_user.php");
    } else {
        echo "Gagal menghapus user.";
    }
}
?>