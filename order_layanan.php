<?php
include 'config/koneksi.php';
session_start();

// Pastikan user sudah login untuk mendapatkan id_user
if (!isset($_SESSION['login'])) {
    echo "<script>alert('Silakan login terlebih dahulu untuk memesan!'); window.location='login.php';</script>";
    exit;
}

// Ambil data layanan berdasarkan parameter URL
if (isset($_GET['id_layanan'])) {
    $id_layanan = mysqli_real_escape_string($conn, $_GET['id_layanan']);
    $query_layanan = mysqli_query($conn, "SELECT * FROM layanan_cetak WHERE id_layanan = '$id_layanan'");
    $layanan = mysqli_fetch_assoc($query_layanan);
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pemesanan | OPPASTUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0e0e0e] text-gray-300 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-[#131313] border border-white/5 p-8 rounded-sm shadow-xl">
        <h2 class="text-2xl font-serif text-white font-bold mb-2">Form Order</h2>
        <p class="text-xs text-primary mb-6 uppercase tracking-widest">Layanan: <?= $layanan['nama_layanan'] ?></p>

        <form action="actions/proses_order_client.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id_layanan" value="<?= $layanan['id_layanan'] ?>">
            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>"> <div>
                <label class="block text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-2">Jumlah</label>
                <input type="number" name="jumlah" min="1" value="1" required class="w-full bg-[#0e0e0e] text-white p-3 text-sm outline-none border border-white/5 focus:border-primary/50">
            </div>

            <div>
                <label class="block text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-2">Unggah File Desain / Foto</label>
                <input type="file" name="file_desain" class="w-full bg-[#0e0e0e] text-xs p-3 border border-white/5 file:bg-primary file:border-none file:px-3 file:py-1 file:text-black file:font-bold file:text-[10px] file:uppercase">
            </div>

            <div>
                <label class="block text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-2">Catatan Khusus</label>
                <textarea name="catatan" rows="3" placeholder="Instruksi tambahan untuk cetakan..." class="w-full bg-[#0e0e0e] text-white p-3 text-sm outline-none border border-white/5 focus:border-primary/50 resize-none"></textarea>
            </div>

            <button type="submit" name="place_order" class="w-full bg-primary text-black text-xs font-bold uppercase tracking-widest py-4 hover:brightness-110 transition-all">
                Konfirmasi Pesanan
            </button>
        </form>
    </div>

</body>
</html>