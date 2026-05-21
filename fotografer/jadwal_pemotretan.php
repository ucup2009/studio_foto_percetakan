<?php
// Pastikan session sudah dimulai dan koneksi disertakan
include '../config/koneksi.php';
session_start();
$query = "SELECT * FROM jadwal ORDER BY tanggal ASC"; 
$result_jadwal = mysqli_query($conn, $query);

// Cek jika query gagal agar tidak muncul Fatal Error
if (!$result_jadwal) {
    die("Query gagal: " . mysqli_error($conn));
}
// Ambil ID fotografer dari session login (misal: Rohan dengan id_user 5)
$id_fotografer = $_SESSION['id_user'];

// Query untuk menampilkan jadwal khusus fotografer yang sedang login
$query = "SELECT j.id_jadwal, j.tanggal, j.jam, j.status, 
                 b.lokasi, b.catatan, 
                 p.nama_paket, 
                 u.nama AS nama_pelanggan
          FROM jadwal j
          JOIN booking b ON j.id_booking = b.id_booking
          JOIN paket p ON b.id_paket = p.id_paket
          JOIN users u ON b.id_user = u.id_user
          WHERE j.id_fotografer = '$id_fotografer'
          ORDER BY j.tanggal ASC, j.jam ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <title>Jadwal Pemotretan | OPPASTUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body class="bg-[#131313] text-[#e5e2e1] flex min-h-screen">

    
    <?php include '../includes/sidebar_fotografer.php';?>

    <main class="flex-1 p-12">
        <header class="mb-10">
            <h2 class="text-3xl font-bold text-[#e9c176] mb-2 uppercase tracking-tighter">Jadwal Pemotretan</h2>
            <p class="text-gray-500 text-sm italic">Kelola jadwal dan perbarui status sesi foto Anda.</p>
        </header>

        <div class="grid grid-cols-1 gap-6">
            <?php while($row = mysqli_fetch_assoc($result_jadwal)): ?>
            <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6">
                    <div class="bg-[#e9c176]/10 p-4 rounded-sm text-[#e9c176] text-center min-w-[80px]">
                        <p class="text-[10px] uppercase font-bold">Tgl</p>
                        <p class="text-xl font-bold"><?= date('d M', strtotime($row['tgl_pemotretan'])) ?></p>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg"><?= $row['nama_klien'] ?></h4>
                        <p class="text-sm text-gray-400"><?= $row['paket_foto'] ?> • <?= $row['jam_pemotretan'] ?></p>
                        <p class="text-[10px] mt-2 px-2 py-0.5 bg-white/5 inline-block rounded-full text-[#e9c176]">Status: <?= $row['status_pemotretan'] ?></p>
                    </div>
                </div>

                <form method="POST" class="flex gap-2">
                    <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                    <select name="status" class="bg-[#0e0e0e] text-xs p-3 border-none outline-none focus:ring-1 focus:ring-[#e9c176]">
                        <option value="Menunggu" <?= $row['status_pemotretan'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="Selesai Foto" <?= $row['status_pemotretan'] == 'Selesai Foto' ? 'selected' : '' ?>>Selesai Foto</option>
                        <option value="Editing" <?= $row['status_pemotretan'] == 'Editing' ? 'selected' : '' ?>>Proses Editing</option>
                    </select>
                    <button type="submit" name="update_status" class="bg-[#e9c176] text-[#412d00] px-4 py-2 text-[10px] font-bold uppercase hover:brightness-110">
                        Update
                    </button>
                </form>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>                                   