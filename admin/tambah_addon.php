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
?>

<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Service | OPPASTUDIO</title>
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
                        "surface": "#131313",
                        "on-surface": "#e5e2e1",
                        "surface-container-low": "#1c1b1b",
                        "outline-variant": "#4e4639",
                    },
                    fontFamily: { "headline": ["Noto Serif"], "body": ["Inter"], "label": ["Manrope"] }
                },
            },
        }
    </script>
</head>
<body class="min-h-screen flex bg-[#0e0e0e] text-gray-300 overflow-hidden">

    <?php include '../includes/sidebar_admin.php'; ?>

    <main class="flex-1 bg-surface h-screen overflow-y-auto">
        <div class="max-w-4xl mx-auto p-12">
            
            <header class="mb-12">
                <a href="manage_addons.php" class="text-primary text-[10px] uppercase tracking-[0.2em] flex items-center gap-2 mb-4 hover:opacity-70 transition-all font-bold">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    Back to Add-ons
                </a>
                <h2 class="font-headline text-4xl font-bold tracking-tight text-on-surface">Add New Service / Add-on</h2>
                <p class="text-gray-500 text-sm mt-2">Tambahkan layanan cetak, makeup artist, atau keperluan dokumentasi lainnya.</p>
            </header>

            <section class="bg-surface-container-low p-10 border border-white/5 rounded-sm shadow-2xl">
                <form action="../actions/proses_tambah_addon.php" method="POST" class="space-y-8">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="font-label text-[10px] tracking-widest text-gray-500 uppercase font-bold">Nama Layanan</label>
                            <input type="text" name="nama_layanan" placeholder="Contoh: Cetak Foto 16R + Bingkai" required
                                   class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface placeholder:text-gray-700 text-sm outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="font-label text-[10px] tracking-widest text-gray-500 uppercase font-bold">Harga (Rp)</label>
                            <input type="number" name="harga" placeholder="Contoh: 150000" required
                                   class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface placeholder:text-gray-700 text-sm outline-none">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="font-label text-[10px] tracking-widest text-gray-500 uppercase font-bold">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3" placeholder="Contoh: Cetak kualitas tinggi bingkai kayu eksklusif" required
                                  class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface placeholder:text-gray-700 text-sm outline-none resize-none"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" name="submit" class="w-full bg-primary text-black font-label text-xs font-bold tracking-[0.3em] uppercase py-5 rounded-sm hover:brightness-110 transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined text-sm">add_circle</span>
                            Save & Publish Service
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </main>

</body>
</html>