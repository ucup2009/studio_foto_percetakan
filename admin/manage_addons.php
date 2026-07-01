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

// Nama tabel di database kamu
$nama_tabel = 'layanan_cetak'; 

// PROSES HAPUS LAYANAN
if (isset($_GET['hapus'])) {
    $id_layanan = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    // Query hapus menggunakan id_layanan sesuai database kamu
    $query_hapus = "DELETE FROM $nama_tabel WHERE id_layanan = '$id_layanan'";
    
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('Layanan berhasil dihapus!'); window.location='manage_addons.php';</script>";
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
}

// AMBIL DATA LAYANAN
$query = "SELECT * FROM $nama_tabel ORDER BY id_layanan ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Layanan Tambahan | OPPASTUDIO</title>
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
        <div class="max-w-5xl mx-auto p-12">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
                <div>
                    <h2 class="font-headline text-4xl font-bold text-white mb-2">Layanan Cetak & Add-ons</h2>
                    <p class="text-gray-500 text-sm">Kelola daftar harga jasa cetak, makeup artist, kostum, dan retouching.</p>
                </div>
                <a href="tambah_addon.php" class="bg-primary text-black font-label text-[10px] font-bold tracking-widest uppercase px-8 py-4 rounded-sm hover:brightness-110 transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm">add</span> Add New Service
                </a>
            </header>

            <div class="bg-surface-container border border-white/5 divide-y divide-white/5 rounded-sm shadow-2xl">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="p-8 flex items-center justify-between hover:bg-white/[0.01] transition-all group">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-surface flex items-center justify-center border border-white/5 group-hover:border-primary/30 transition-all text-gray-400 group-hover:text-primary">
                                <span class="material-symbols-outlined text-2xl">
                                    <?php 
                                        $nama = strtolower($row['nama_layanan']);
                                        if (strpos($nama, 'cetak') !== false || strpos($nama, 'foto') !== false) echo 'image';
                                        elseif (strpos($nama, 'makeup') !== false || strpos($nama, 'mua') !== false) echo 'face';
                                        elseif (strpos($nama, 'kostum') !== false || strpos($nama, 'baju') !== false) echo 'styler';
                                        elseif (strpos($nama, 'background') !== false) echo 'wallpaper';
                                        else echo 'auto_awesome';
                                    ?>
                                </span>
                            </div>
                            <div>
                                <h3 class="font-headline text-xl font-bold text-white mb-1"><?= $row['nama_layanan'] ?></h3>
                                <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">"<?= $row['deskripsi'] ?>"</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-12">
                            <div class="text-right">
                                <p class="text-primary font-label text-lg font-bold">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                                <span class="text-[9px] text-primary/50 tracking-widest uppercase font-bold">Kualitas Premium</span>
                            </div>
                            
                            <div class="flex gap-3 border-l border-white/5 pl-6">
                                <a href="edit_addon.php?id=<?= $row['id_layanan'] ?>" class="text-gray-500 hover:text-primary transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit_note</span>
                                </a>
                                <a href="?hapus=<?= $row['id_layanan'] ?>" onclick="return confirm('Hapus layanan <?= $row['nama_layanan'] ?>?')" class="text-gray-500 hover:text-red-400 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="p-12 text-center text-sm text-gray-600 italic uppercase tracking-widest">
                        Belum ada data di dalam tabel `layanan`.
                    </div>
                <?php endif; ?>
            </div>

            <p class="text-left text-[9px] text-gray-600 uppercase tracking-widest mt-4">Total Record: <?= mysqli_num_rows($result) ?> Item Layanan</p>
        </div>
    </main>
</body>
</html>