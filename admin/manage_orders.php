<?php
include '../config/koneksi.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Nama tabel transaksi/pesanan kamu (sesuaikan jika namanya berbeda di phpMyAdmin)
$tabel_pesanan = 'pesanan_cetak'; 

// LOGIKA UPDATE STATUS (Jika admin mengubah status pesanan)
if (isset($_POST['update_status'])) {
    $id_pesanan = mysqli_real_escape_string($conn, $_POST['id_pesanan']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query_update = "UPDATE $tabel_pesanan SET status = '$status_baru' WHERE id_pesanan = '$id_pesanan'";
    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Status pesanan berhasil diperbarui!'); window.location='manage_orders.php';</script>";
        exit;
    }
}

// QUERY AMBIL DATA (Menggabungkan dengan tabel user dan layanan agar infonya lengkap)
$query = "SELECT p.*, u.nama, l.nama_layanan, l.harga 
          FROM $tabel_pesanan p
          JOIN users u ON p.id_user = u.id_user
          JOIN layanan_cetak l ON p.id_layanan = l.id_layanan
          ORDER BY p.tanggal_pesan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Orders | OPPASTUDIO</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Inter:wght@300;400;600&family=Manrope:wght@500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#e9c176",
                        "surface": "#0e0e0e",
                        "surface-container": "#131313",
                    },
                    fontFamily: { "headline": ["Noto Serif"], "body": ["Inter"], "label": ["Manrope"] }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex bg-surface text-gray-300 overflow-hidden">

    <?php include '../includes/sidebar_admin.php'; ?>

    <main class="flex-1 h-screen overflow-y-auto">
        <div class="max-w-6xl mx-auto p-12">
            
            <header class="mb-12">
                <h2 class="font-headline text-4xl font-bold text-white mb-2">Layanan Cetak Orders</h2>
                <p class="text-gray-500 text-sm">Pantau berkas foto masuk, cetakan, dan instruksi khusus dari klien.</p>
            </header>

            <div class="bg-surface-container border border-white/5 rounded-sm shadow-2xl overflow-hidden">
                <table class="w-full text-left border-collapse text-xs uppercase tracking-widest">
                    <thead class="bg-[#1c1b1b] text-primary border-b border-white/5 font-label font-bold text-[10px]">
                        <tr>
                            <th class="px-6 py-4">Klien</th>
                            <th class="px-6 py-4">Detail Layanan</th>
                            <th class="px-6 py-4 text-center">Jumlah</th>
                            <th class="px-6 py-4">Berkas Desain</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 font-body">
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-white/[0.01] transition-all">
                                <td class="px-6 py-5">
                                    <p class="text-white font-bold tracking-normal text-sm"><?= $row['nama'] ?></p>
                                    <p class="text-[9px] text-gray-600 mt-1"><?= date('d M Y', strtotime($row['tanggal_pesan'])) ?></p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-gray-300 font-medium normal-case text-sm tracking-normal"><?= $row['nama_layanan'] ?></p>
                                    <?php if(!empty($row['catatan'])): ?>
                                        <p class="text-[10px] text-primary/70 italic normal-case mt-1 tracking-normal">Note: "<?= $row['catatan'] ?>"</p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center text-white font-bold font-label">
                                    <?= $row['jumlah'] ?>x
                                </td>
                                <td class="px-6 py-5">
                                    <?php if(!empty($row['file_desain'])): ?>
                                        <a href="../img/designs/<?= $row['file_desain'] ?>" target="_blank" class="text-primary hover:underline flex items-center gap-1 text-[10px] font-bold">
                                            <span class="material-symbols-outlined text-sm">download</span> VIEW FILE
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-700 italic text-[10px]">No File</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php 
                                        $status = strtolower($row['status']);
                                        if($status == 'selesai') {
                                            echo '<span class="bg-green-500/10 text-green-400 border border-green-500/20 px-3 py-1 rounded-sm text-[9px] font-bold">Selesai</span>';
                                        } elseif($status == 'diproses') {
                                            echo '<span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded-sm text-[9px] font-bold">Diproses</span>';
                                        } else {
                                            echo '<span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-3 py-1 rounded-sm text-[9px] font-bold">Menunggu</span>';
                                        }
                                    ?>
                                </td>

                                <td class="px-6 py-5 text-right">
                                    <form action="" method="POST" class="flex items-center justify-end gap-2">
                                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                                        <select name="status" class="bg-[#0e0e0e] border border-white/5 text-gray-400 text-[10px] p-2 rounded-sm outline-none focus:border-primary/50 transition-colors uppercase font-bold tracking-wider">
                                            <option value="menunggu" <?= ($row['status'] == 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                                            <option value="diproses" <?= ($row['status'] == 'diproses') ? 'selected' : '' ?>>Diproses</option>
                                            <option value="selesai" <?= ($row['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                        <button type="submit" name="update_status" class="bg-primary/10 text-primary border border-primary/20 hover:bg-primary hover:text-black p-2 rounded-sm transition-all" title="Update Status">
                                            <span class="material-symbols-outlined text-xs block">check</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-12 text-center text-sm text-gray-600 italic uppercase tracking-widest">
                                    Belum ada pesanan layanan tambahan masuk.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</body>
</html>