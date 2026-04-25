<?php
include 'koneksi.php';
session_start();

// Proteksi Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 1. Logika Hapus (Jika ada request hapus)
if (isset($_GET['hapus'])) {
    $id_paket = $_GET['hapus'];
    // Gunakan UPDATE jika ingin soft delete, atau DELETE jika ingin hapus permanen
    mysqli_query($conn, "DELETE FROM paket WHERE id_paket = '$id_paket'");
    header("Location: manage_services.php?pesan=deleted");
    exit;
}


$sql_tampil = "SELECT * FROM paket ORDER BY harga ASC"; 


$result = mysqli_query($conn, $sql_tampil); 
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Services | OPPASTUDIO</title>
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
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex bg-surface text-on-surface overflow-hidden">

    <?php include 'sidebar_admin.php'; ?>

    <main class="flex-1 h-screen overflow-y-auto">
        <div class="max-w-7xl mx-auto p-12">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16">
                <div>
                    <h2 class="font-headline text-4xl font-bold mb-2">Service Packages</h2>
                    <p class="text-gray-400 text-sm">Kelola daftar harga dan durasi sesi foto.</p>
                </div>
                <a href="tambah_paket.php" class="bg-primary text-black font-label text-[10px] font-bold tracking-widest uppercase px-8 py-4 rounded-sm hover:brightness-110 transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm">add</span> Add New Package
                </a>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="bg-surface-container-low border border-outline-variant/10 p-8 rounded-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between mb-6">
                            <span class="material-symbols-outlined text-primary text-3xl">photo_camera</span>
                            <div class="flex gap-2 text-gray-500">
                                <a href="edit_paket.php?id=<?= $row['id_paket'] ?>" class="hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-xl">edit_note</span>
                                </a>
                                <a href="?hapus=<?= $row['id_paket'] ?>" onclick="return confirm('Hapus paket?')" class="hover:text-red-400 transition-colors">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </a>
                            </div>
                        </div>
                        
                        <h3 class="font-headline text-2xl font-bold mb-1"><?= $row['nama_paket'] ?></h3>
                        
                        <div class="flex items-center gap-2 mb-4 text-gray-500">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            <p class="text-[10px] uppercase tracking-widest font-bold"><?= $row['durasi'] ?> Minutes Session</p>
                        </div>

                        <p class="text-primary font-label text-xl font-bold mb-6">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                        
                        <p class="text-xs text-gray-400 leading-relaxed italic mb-8 border-l-2 border-primary/20 pl-4">
                            "<?= $row['deskripsi'] ?>"
                        </p>
                    </div>

                    <div class="pt-6 border-t border-white/5 text-[10px] text-gray-600 uppercase tracking-widest">
                        ID: #<?= $row['id_paket'] ?> • Added: <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
</body>
</html>