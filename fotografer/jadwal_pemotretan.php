<?php
include '../config/koneksi.php';
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

// Ambil ID fotografer dari session login
$id_fotografer = $_SESSION['id_user'];
$notif = "";

// 2. Logika Pembaruan Status (Jika Tombol Update Diklik)
if (isset($_POST['update_status'])) {
    $id_jadwal = mysqli_real_escape_string($conn, $_POST['id_jadwal']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Perbarui status langsung di tabel jadwal
    $query_update = "UPDATE jadwal SET status = '$status_baru' WHERE id_jadwal = '$id_jadwal' AND id_fotografer = '$id_fotografer'";
    if (mysqli_query($conn, $query_update)) {
        $notif = "success";
    } else {
        $notif = "error";
    }
}

// 3. Query Mengambil Jadwal Sesuai Fotografer yang Login
// Ambil ID user dari session login fotografer (misal Yusuf dengan id_user = 5)
$id_user_login = $_SESSION['id_user'];

// Query menampilkan jadwal khusus fotografer yang sedang login dengan mencocokkan id_user-nya
$query = "SELECT j.id_jadwal, j.tanggal, j.jam, j.status, 
                 b.lokasi, b.catatan, 
                 p.nama_paket, 
                 u_pelanggan.nama AS nama_pelanggan
          FROM jadwal j
          JOIN booking b ON j.id_booking = b.id_booking
          JOIN paket p ON b.id_paket = p.id_paket
          JOIN users u_pelanggan ON b.id_user = u_pelanggan.id_user
          JOIN fotografer f ON j.id_fotografer = f.id_fotografer
          WHERE f.id_user = '$id_user_login'
          ORDER BY j.tanggal ASC, j.jam ASC";

$result_jadwal = mysqli_query($conn, $query);

if (!$result_jadwal) {
    die("Query gagal: " . mysqli_error($conn));
}   
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
    
    <?php include '../includes/sidebar_fotografer.php'; ?>

    <main class="flex-1 p-12">
        <header class="mb-10">
            <h2 class="text-3xl font-bold text-[#e9c176] mb-2 uppercase tracking-tighter">Jadwal Pemotretan</h2>
            <p class="text-gray-500 text-sm italic">Kelola jadwal dan perbarui status sesi foto Anda.</p>
        </header>

        <?php if($notif == "success"): ?>
            <div class="bg-primary/20 border border-[#e9c176] text-[#e9c176] p-4 mb-6 text-sm">
                Status jadwal pemotretan berhasil diperbarui!
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6">
            <?php if (mysqli_num_rows($result_jadwal) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result_jadwal)): ?>
                <div class="bg-[#1c1b1b] border border-white/5 p-6 rounded-sm flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-6">
                        <div class="bg-[#e9c176]/10 p-4 rounded-sm text-[#e9c176] text-center min-w-[80px]">
                            <p class="text-[10px] uppercase font-bold">Tgl</p>
                            <p class="text-xl font-bold"><?= date('d M', strtotime($row['tanggal'])) ?></p>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg"><?= ucwords($row['nama_pelanggan']) ?></h4>
                            <p class="text-sm text-gray-400"><?= $row['nama_paket'] ?> • <?= $row['jam'] ?> WIB</p>
                            <p class="text-sm text-gray-500 mt-1"><strong class="text-gray-400">Lokasi:</strong> <?= $row['lokasi'] ?></p>
                            <?php if(!empty($row['catatan'])): ?>
                                <p class="text-xs text-gray-500 italic mt-1">"<?= $row['catatan'] ?>"</p>
                            <?php endif; ?>
                            <p class="text-[10px] mt-2 px-2 py-0.5 bg-white/5 inline-block rounded-full text-[#e9c176]">Status: <?= $row['status'] ?></p>
                        </div>
                    </div>

                    <form method="POST" class="flex gap-2">
                        <input type="hidden" name="id_jadwal" value="<?= $row['id_jadwal'] ?>">
                        <select name="status" class="bg-[#0e0e0e] text-xs p-3 border-none text-[#e5e2e1] outline-none focus:ring-1 focus:ring-[#e9c176]">
                            <option value="Menunggu" <?= $row['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                            <option value="Selesai Foto" <?= $row['status'] == 'Selesai Foto' ? 'selected' : '' ?>>Selesai Foto</option>
                            <option value="Editing" <?= $row['status'] == 'Editing' ? 'selected' : '' ?>>Proses Editing</option>
                        </select>
                        <button type="submit" name="update_status" class="bg-[#e9c176] text-[#412d00] px-4 py-2 text-[10px] font-bold uppercase hover:brightness-110">
                            Update
                        </button>
                    </form>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-[#1c1b1b] border border-white/5 p-12 text-center rounded-sm">
                    <span class="material-symbols-outlined text-gray-600 text-5xl mb-2">calendar_today</span>
                    <p class="text-gray-400 text-sm">Belum ada jadwal pemotretan yang ditugaskan kepada Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>