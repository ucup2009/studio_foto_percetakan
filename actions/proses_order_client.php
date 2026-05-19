<?php
include '../config/koneksi.php'; 
include '../includes/whatsapp_helper.php'; // 1. Include helper WA yang baru dibuat
session_start();

if (isset($_POST['place_order'])) {
    $id_user    = mysqli_real_escape_string($conn, $_POST['id_user']);
    $id_layanan = mysqli_real_escape_string($conn, $_POST['id_layanan']);
    $jumlah     = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $catatan    = mysqli_real_escape_string($conn, $_POST['catatan']);
    $tanggal    = date('Y-m-d H:i:s');
    $status     = 'menunggu'; // Sesuai ENUM database kamu

    // (Proses upload file tetap sama seperti sebelumnya...)
    $nama_file = $_FILES['file_desain']['name'];
    $tmp_name  = $_FILES['file_desain']['tmp_name'];
    $file_baru = "";
    if (!empty($nama_file)) {
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $file_baru = uniqid() . '.' . $ekstensi;
        move_uploaded_file($tmp_name, '../img/designs/' . $file_baru);
    }

    // Ambil data user (Nama & Nomor Telepon) dan Detail Jasa untuk isi pesan WA
    $user_query = mysqli_query($conn, "SELECT nama, no_hp FROM users WHERE id_user = '$id_user'");
    $user_data  = mysqli_fetch_assoc($user_query);
    
    $layanan_query = mysqli_query($conn, "SELECT nama_layanan, harga FROM layanan_cetak WHERE id_layanan = '$id_layanan'");
    $layanan_data  = mysqli_fetch_assoc($layanan_query);

    // Query Simpan ke Database
    $query = "INSERT INTO pesanan_cetak (id_user, id_layanan, jumlah, file_desain, catatan, tanggal_pesan, status) 
              VALUES ('$id_user', '$id_layanan', '$jumlah', '$file_baru', '$catatan', '$tanggal', '$status')";
              
    if (mysqli_query($conn, $query)) {
        
        // 2. LOGIKA NOTIFIKASI WHATSAPP
        $nomor_tujuan = $user_data['no_hp']; 
        $total_bayar  = $layanan_data['harga'] * $jumlah;
        
        // Menyusun teks pesan dengan format yang elegan
        $pesan_wa = "*OPPASTUDIO - ORDER CONFIRMATION* \n\n";
        $pesan_wa .= "Halo *" . $user_data['nama'] . "*, Terima kasih telah memesan layanan kami.\n\n";
        $pesan_wa .= "Detail Pesanan:\n";
        $pesan_wa .= "• Layanan: " . $layanan_data['nama_layanan'] . "\n";
        $pesan_wa .= "• Jumlah: " . $jumlah . "x\n";
        $pesan_wa .= "• Total: Rp " . number_format($total_bayar, 0, ',', '.') . "\n";
        $pesan_wa .= "• Status: *Menunggu Pembayaran*\n\n";
        $pesan_wa .= "Silakan lakukan pembayaran agar pesanan Anda dapat segera kami proses. Terima kasih.";

        // Eksekusi kirim WA
        sendWhatsApp($nomor_tujuan, $pesan_wa);

        echo "<script>alert('Pesanan berhasil dibuat dan notifikasi telah dikirim ke WhatsApp Anda!'); window.location='../index.php';</script>";
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>