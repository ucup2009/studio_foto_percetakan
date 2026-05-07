<?php
include 'koneksi.php';
session_start();

// 1. PROTEKSI HALAMAN
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'fotografer') {
    header("Location: login.php");
    exit;
}

$id_foto = $_SESSION['id_user'];

// 2. QUERY AMBIL DATA JADWAL (JOIN 3 TABEL)
$query = "SELECT j.id_jadwal, j.tanggal, j.jam, j.status, 
                 u.nama AS nama_pelanggan, 
                 p.nama_paket, 
                 b.lokasi
          FROM jadwal j
          JOIN booking b ON j.id_booking = b.id_booking
          JOIN users u ON b.id_user = u.id_user
          JOIN paket p ON b.id_paket = p.id_paket
          WHERE j.id_fotografer = '$id_foto'
          ORDER BY j.tanggal ASC, j.jam ASC";

$result = mysqli_query($conn, $query);

// 3. HITUNG STATISTIK (OPSIONAL AGAR DASHBOARD HIDUP)
$count_today = mysqli_num_rows(mysqli_query($conn, "SELECT id_jadwal FROM jadwal WHERE id_fotografer = '$id_foto' AND tanggal = CURDATE()"));
$count_done = mysqli_num_rows(mysqli_query($conn, "SELECT id_jadwal FROM jadwal WHERE id_fotografer = '$id_foto' AND status = 'selesai'"));
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fotografer Dashboard | OPPASTUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #131313; color: #e5e2e1; font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Noto Serif', serif; }
        .bg-primary { background-color: #e9c176; }
        .text-primary { color: #e9c176; }
    </style>
</head>
<body class="flex min-h-screen">

    <?php include 'sidebar_fotografer.php'; ?>

    <main class="flex-1 p-12 overflow-y-auto">
        <header class="mb-12 flex justify-between items-end">
            <div>
                <h2 class="font-headline text-4xl font-bold mb-2 italic">Creative Studio</h2>
                <p class="text-primary text-[10px] uppercase tracking-[0.3em]">Visual Artist: <?= $_SESSION['nama'] ?></p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest"><?= date('l, d F Y') ?></p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm">
                <p class="text-[9px] uppercase tracking-widest text-gray-500 font-bold mb-2">Today's Shoot</p>
                <h3 class="text-3xl font-headline text-primary"><?= str_pad($count_today, 2, '0', STR_PAD_LEFT) ?></h3>
            </div>
            <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm">
                <p class="text-[9px] uppercase tracking-widest text-gray-500 font-bold mb-2">Total Project</p>
                <h3 class="text-3xl font-headline"><?= mysqli_num_rows($result) ?></h3>
            </div>
            <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm">
                <p class="text-[9px] uppercase tracking-widest text-gray-500 font-bold mb-2">Completed</p>
                <h3 class="text-3xl font-headline"><?= $count_done ?></h3>
            </div>
            <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm">
                <p class="text-[9px] uppercase tracking-widest text-gray-500 font-bold mb-2">Status</p>
                <h3 class="text-xl font-headline text-primary">Active</h3>
            </div>
        </div>

        <div class="bg-[#1c1b1b] border border-white/5 rounded-sm overflow-hidden">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h4 class="font-headline text-lg">Upcoming Schedule</h4>
                <span class="text-[10px] text-gray-500 uppercase">Sorted by Date</span>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 text-[10px] uppercase tracking-widest text-gray-400">
                        <th class="p-6">Waktu & Tanggal</th>
                        <th class="p-6">Pelanggan</th>
                        <th class="p-6">Paket & Lokasi</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-white">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-all group">
                            <td class="p-6">
                                <div class="font-bold text-white"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-tighter"><?= $row['jam'] ?> WIB</div>
                            </td>
                            <td class="p-6">
                                <span class="font-medium"><?= $row['nama_pelanggan'] ?></span>
                            </td>
                            <td class="p-6">
                                <div class="text-primary font-semibold"><?= $row['nama_paket'] ?></div>
                                <div class="text-[10px] text-gray-500 italic"><?= $row['lokasi'] ?></div>
                            </td>
                            <td class="p-6 text-center">
                                <?php if($row['status'] == 'selesai'): ?>
                                    <span class="inline-block px-3 py-1 rounded-full bg-green-500/10 text-green-500 text-[9px] font-bold uppercase tracking-widest">Selesai</span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-500 text-[9px] font-bold uppercase tracking-widest">Terjadwal</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-6 text-right">
                                <?php if($row['status'] == 'terjadwal'): ?>
                                    <a href="proses_update.php?id_jadwal=<?= $row['id_jadwal'] ?>&status=selesai" 
                                       onclick="return confirm('Tandai sesi foto ini sudah selesai?')"
                                       class="inline-block bg-primary text-black px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:brightness-110 transition-all">
                                       Done Shoot
                                    </a>
                                <?php else: ?>
                                    <span class="material-symbols-outlined text-green-500 opacity-50">task_alt</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-500 italic tracking-widest text-xs">
                                No production schedule found for your account.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>