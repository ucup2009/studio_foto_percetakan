<?php
include '../config/koneksi.php';
session_start();

// Proteksi: Hanya admin yang bisa masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Logika Hapus User (Jika diperlukan)
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id_hapus' AND role != 'admin'");
    header("Location: manage_user.php");
    exit;
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manage Users | OPPASTUDIO</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Inter:wght@300;400;600&family=Manrope:wght@500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#e9c176",
                        "on-primary": "#412d00",
                        "surface": "#131313",
                        "on-surface": "#e5e2e1",
                        "surface-container-low": "#1c1b1b",
                        "surface-container-lowest": "#0e0e0e",
                        "on-surface-variant": "#d1c5b4",
                        "outline-variant": "#4e4639",
                    },
                    fontFamily: {
                        "headline": ["Noto Serif"],
                        "body": ["Inter"],
                        "label": ["Manrope"]
                    }
                },
            },
        }
    </script>
    
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #131313; color: #e5e2e1; font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-screen flex overflow-hidden">

   <?php include '../includes/sidebar_admin.php'; ?>


    <main class="flex-1 bg-surface h-screen overflow-y-auto">
        <div class="max-w-7xl mx-auto p-12">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16">
                <div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-on-surface mb-2">User Management</h2>
                    <p class="text-on-surface-variant text-sm max-w-md">Kelola data pelanggan dan hak akses akun OPPASTUDIO.</p>
                </div>
            </header>

            <section class="bg-surface-container-lowest p-10 rounded-sm border border-outline-variant/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant/10">
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">User Info</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Email Address</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Role</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <?php
                            // Ambil data user
                            $result = mysqli_query($conn, "SELECT * FROM users ORDER BY role ASC, nama ASC");

                            if(mysqli_num_rows($result) > 0):
                                while($row = mysqli_fetch_assoc($result)):
                                    // PERBAIKAN: Cek apakah key 'email' ada, jika tidak gunakan placeholder
                                    // Ganti 'email' dengan nama kolom yang benar di database Anda jika berbeda
                                    $email_display = isset($row['email']) ? $row['email'] : '<span class="text-red-400/50 italic">Column Error</span>';
                            ?>
                            <tr class="group hover:bg-surface-container/30 transition-colors">
                                <td class="py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-xs font-bold text-primary border border-primary/20">
                                            <?= strtoupper(substr($row['nama'], 0, 2)) ?>
                                        </div>
                                        <div>
                                            <p class="text-on-surface font-semibold text-sm"><?= $row['nama'] ?></p>
                                            <p class="text-[10px] text-on-surface-variant/50 uppercase tracking-widest">ID: #<?= $row['id_user'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-on-surface-variant text-sm">
                                    <?= $email_display ?>
                                </td>
                                <td class="py-6">
                                    <span class="px-2 py-1 <?= $row['role'] == 'admin' ? 'bg-primary/20 text-primary' : 'bg-white/5 text-on-surface-variant' ?> text-[10px] font-bold tracking-widest uppercase rounded-sm">
                                        <?= $row['role'] ?>
                                    </span>
                                </td>
                                <td class="py-6 text-right">
                                    <?php if($row['role'] !== 'admin'): ?>
                                        <a href="?hapus=<?= $row['id_user'] ?>" onclick="return confirm('Hapus user ini?')" class="text-on-surface-variant/40 hover:text-red-400 transition-colors">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="4" class="py-10 text-center text-on-surface-variant/30 italic text-sm">No users found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>
</body>
</html>