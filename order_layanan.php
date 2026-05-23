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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan | OPPASTUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Mengonfigurasi warna kustom 'primary' agar dikenali oleh engine Tailwind CSS Anda
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e9c176', // Sesuaikan kode hex ini dengan tema emas/kuning OPPASTUDIO Anda
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#0e0e0e] text-gray-300 min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 lg:p-12">

    <div class="w-full max-w-md bg-[#131313] border border-white/5 p-5 sm:p-6 md:p-8 rounded-sm shadow-xl transition-all duration-300">
        <h2 class="text-xl sm:text-2xl font-serif text-white font-bold mb-1 sm:mb-2">Form Order</h2>
        <p class="text-[10px] sm:text-xs text-primary mb-5 sm:mb-6 uppercase tracking-widest leading-relaxed">
            Layanan: <?= htmlspecialchars($layanan['nama_layanan']) ?>
        </p>

        <form action="actions/proses_order_client.php" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
            <input type="hidden" name="id_layanan" value="<?= $layanan['id_layanan'] ?>">
            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>"> 
            
            <div>
                <label class="block text-[9px] sm:text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-1.5 sm:mb-2">Jumlah</label>
                <input type="number" name="jumlah" min="1" value="1" required 
                       class="w-full bg-[#0e0e0e] text-white p-2.5 sm:p-3 text-xs sm:text-sm outline-none border border-white/5 focus:border-primary/50 rounded-none transition-all">
            </div>

            <div>
                <label class="block text-[9px] sm:text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-1.5 sm:mb-2">Unggah File Desain / Foto</label>
                <input type="file" name="file_desain" required
                       class="w-full bg-[#0e0e0e] text-[11px] sm:text-xs border border-white/5 file:mr-3 file:bg-primary file:border-none file:px-3 file:py-2 file:text-black file:font-bold file:text-[9px] file:sm:text-[10px] file:uppercase file:cursor-pointer rounded-none cursor-pointer text-gray-400 file:hover:brightness-110 file:transition-all">
            </div>

            <div>
                <label class="block text-[9px] sm:text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-1.5 sm:mb-2">Catatan Khusus</label>
                <textarea name="catatan" rows="3" placeholder="Instruksi tambahan untuk cetakan..." 
                          class="w-full bg-[#0e0e0e] text-white p-2.5 sm:p-3 text-xs sm:text-sm outline-none border border-white/5 focus:border-primary/50 resize-none rounded-none transition-all"></textarea>
            </div>

            <button type="submit" name="place_order" 
                    class="w-full bg-primary text-black text-[10px] sm:text-xs font-bold uppercase tracking-widest py-3.5 sm:py-4 hover:brightness-110 active:scale-[0.99] transition-all cursor-pointer">
                Konfirmasi Pesanan
            </button>
        </form>
    </div>

</body>
</html>