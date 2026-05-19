<?php
include '../config/koneksi.php';
session_start();

// Proteksi Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Logika Tambah Data
if (isset($_POST['submit'])) {
    $nama_paket = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $harga      = mysqli_real_escape_string($conn, $_POST['harga']);
    $durasi     = mysqli_real_escape_string($conn, $_POST['durasi']);
    $deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $query = "INSERT INTO paket (nama_paket, harga, durasi, deskripsi) 
              VALUES ('$nama_paket', '$harga', '$durasi', '$deskripsi')";

    if (mysqli_query($conn, $query)) {
        header("Location: manage_services.php?pesan=success");
        exit;
    } else {
        $error = "Gagal menambah paket: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Package | OPPASTUDIO</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Inter:wght@300;400;600&family=Manrope:wght@500;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#e9c176",
                        "surface": "#131313",
                        "on-surface": "#e5e2e1",
                        "surface-container-low": "#1c1b1b",
                        "outline-variant": "#4e4639",
                    },
                    fontFamily: { "headline": ["Noto Serif"], "body": ["Inter"], "label": ["Manrope"] }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex bg-surface text-on-surface overflow-hidden">

    <?php include '../includes/sidebar_admin.php'; ?>
    <main class="flex-1 h-screen overflow-y-auto">
        <div class="max-w-3xl mx-auto p-12">
            
            <header class="mb-12">
                <a href="manage_services.php" class="text-primary text-[10px] uppercase tracking-widest flex items-center gap-2 mb-4 hover:opacity-70 transition-all">
                    ← Back to Services
                </a>
                <h2 class="font-headline text-4xl font-bold">Create New Package</h2>
                <p class="text-gray-400 text-sm mt-2">Tambahkan paket layanan pemotretan baru ke dalam katalog studio.</p>
            </header>

            <?php if(isset($error)): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 mb-6 text-xs uppercase tracking-widest font-bold">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="../actions/upload_proses.php" method="POST" enctype="multipart/form-data">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Package Name</label>
                        <input type="text" name="nama_paket" placeholder="e.g. Wedding Cinematic" required
                               class="w-full bg-surface border-none focus:ring-1 focus:ring-primary p-4 text-sm text-white placeholder:text-gray-700 outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Price (IDR)</label>
                        <input type="number" name="harga" placeholder="e.g. 1500000" required
                               class="w-full bg-surface border-none focus:ring-1 focus:ring-primary p-4 text-sm text-white placeholder:text-gray-700 outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Duration (Minutes)</label>
                    <input type="number" name="durasi" placeholder="e.g. 60" required
                           class="w-full bg-surface border-none focus:ring-1 focus:ring-primary p-4 text-sm text-white placeholder:text-gray-700 outline-none">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Description / Features</label>
                    <textarea name="deskripsi" rows="5" placeholder="e.g. 2 Locations, 20 Edited Photos, All RAW Files included..." required
                              class="w-full bg-surface border-none focus:ring-1 focus:ring-primary p-4 text-sm text-white placeholder:text-gray-700 outline-none resize-none"></textarea>
                    <p class="text-[10px] text-gray-600 italic">Gunakan koma (,) untuk memisahkan fitur agar mudah dibaca.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" name="submit" 
                            class="w-full bg-primary text-black font-label text-[10px] font-bold tracking-widest uppercase py-5 rounded-sm hover:brightness-110 transition-all">
                        Publish Service Package
                    </button>
                </div>
            </form>

        </div>
    </main>
</body>
</html>     