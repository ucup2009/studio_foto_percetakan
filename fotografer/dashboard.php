<?php
include '../config/koneksi.php';
session_start();

// 1. PROTEKSI HALAMAN (Sesuai role di database)
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

// 3. HITUNG STATISTIK OPTIMAL
$count_today = 0;
$count_done = 0;
$total_project = mysqli_num_rows($result);

if ($total_project > 0) {
    while ($row_stat = mysqli_fetch_assoc($result)) {
        if ($row_stat['tanggal'] == date('Y-m-d')) {
            $count_today++;
        }
        if (strtolower($row_stat['status']) == 'selesai') {
            $count_done++;
        }
    }
    // Kembalikan pointer internal database ke baris pertama agar data tabel bisa di-render
    mysqli_data_seek($result, 0);
}
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

    <?php include '../includes/sidebar_fotografer.php'; ?>

    <main class="flex-1 p-12 overflow-y-auto">
        <header class="mb-12 flex justify-between items-end">
            <div>
                <h2 class="font-headline text-4xl font-bold mb-2 italic">Creative Studio</h2>
                <p class="text-primary text-[10px] uppercase tracking-[0.3em]">Visual Artist: <?= htmlspecialchars($_SESSION['nama']) ?></p>
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
                <h3 class="text-3xl font-headline"><?= str_pad($total_project, 2, '0', STR_PAD_LEFT) ?></h3>
            </div>
            <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm">
                <p class="text-[9px] uppercase tracking-widest text-gray-500 font-bold mb-2">Completed</p>
                <h3 class="text-3xl font-headline"><?= str_pad($count_done, 2, '0', STR_PAD_LEFT) ?></h3>
            </div>
            <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm">
                <p class="text-[9px] uppercase tracking-widest text-gray-500 font-bold mb-2">Status</p>
                <h3 class="text-primary tracking-wide uppercase text-sm font-bold mt-1">● Active</h3>
            </div>
        </div>

        <div class="bg-[#1c1b1b] border border-white/5 rounded-sm overflow-hidden">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h4 class="font-headline text-lg">Upcoming Schedule</h4>
                <span class="text-[10px] text-gray-500 uppercase tracking-wider">Sorted by Date</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 text-[10px] uppercase tracking-widest text-gray-400 bg-white/[0.01]">
                            <th class="p-6">Waktu & Tanggal</th>
                            <th class="p-6">Pelanggan</th>
                            <th class="p-6">Paket & Lokasi</th>
                            <th class="p-6 text-center">Status</th>
                            <th class="p-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-white divide-y divide-white/5">
                        <?php if ($total_project > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-white/[0.01] transition-all group">
                                <td class="p-6">
                                    <div class="font-bold text-white"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-tighter mt-1"><?= date('H:i', strtotime($row['jam'])) ?> WIB</div>
                                </td>
                                <td class="p-6">
                                    <span class="font-medium text-gray-200"><?= htmlspecialchars($row['nama_pelanggan']) ?></span>
                                </td>
                                <td class="p-6">
                                    <div class="text-primary font-semibold text-sm"><?= htmlspecialchars($row['nama_paket']) ?></div>
                                    <div class="text-[10px] text-gray-500 italic mt-0.5"><?= htmlspecialchars($row['lokasi']) ?></div>
                                </td>
                                <td class="p-6 text-center">
                                    <?php if(strtolower($row['status']) == 'selesai'): ?>
                                        <span class="inline-block px-3 py-1 bg-green-500/10 text-green-400 border border-green-500/20 text-[9px] font-bold uppercase tracking-widest rounded-sm">Selesai</span>
                                    <?php elseif(strtolower($row['status']) == 'batal'): ?>
                                        <span class="inline-block px-3 py-1 bg-red-500/10 text-red-400 border border-red-500/20 text-[9px] font-bold uppercase tracking-widest rounded-sm">Batal</span>
                                    <?php else: ?>
                                        <span class="inline-block px-3 py-1 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[9px] font-bold uppercase tracking-widest rounded-sm">Terjadwal</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php if(strtolower($row['status']) == 'terjadwal'): ?>
                                            <a href="proses_update.php?id_jadwal=<?= $row['id_jadwal'] ?>&status=selesai" 
                                               onclick="return confirm('Tandai sesi foto ini sudah selesai?')"
                                               class="inline-block bg-primary text-black px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-white transition-all rounded-sm"
                                               title="Selesaikan Sesi">
                                                Done Shoot
                                            </a>
                                            <a href="proses_update.php?id_jadwal=<?= $row['id_jadwal'] ?>&status=batal" 
                                               onclick="return confirm('Apakah Anda yakin ingin membatalkan jadwal ini?')"
                                               class="inline-block bg-red-500/10 text-red-400 border border-red-500/20 px-3 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all rounded-sm"
                                               title="Batalkan Jadwal">
                                                Cancel
                                            </a>
                                        <?php else: ?>
                                            <span class="material-symbols-outlined text-gray-600 select-none text-md">done_all</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="p-16 text-center text-gray-600 italic tracking-widest text-xs uppercase">
                                    No production schedule found for your artist account.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // Logika pencegahan error saat manipulasi atau pembacaan event
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Dashboard Fotografer siap. Sistem proteksi string aktif.");
        });
    </script>
</body>
</html>