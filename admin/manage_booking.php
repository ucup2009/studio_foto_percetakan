<?php
include '../config/koneksi.php';
session_start();

// Proteksi: Hanya admin yang bisa masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Logika Update Status
if (isset($_POST['update_status'])) {
    $id_booking = $_POST['id_booking'];
    $status_baru = $_POST['status'];
    
    $update_query = "UPDATE booking SET status = '$status_baru' WHERE id_booking = '$id_booking'";
    mysqli_query($conn, $update_query);
    header("Location: manage/manage_booking.php?pesan=updated");
    exit;
}

// Logika Hapus Booking
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM booking WHERE id_booking = '$id_hapus'");
    header("Location: manage_booking.php?pesan=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manage Bookings | OPPASTUDIO</title>
    
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
        select { background-image: none !important; } /* Menghapus icon default select */
    </style>
</head>

<body class="min-h-screen flex overflow-hidden">

    <?php include '../includes/sidebar_admin.php'; ?>


    <main class="flex-1 bg-surface h-screen overflow-y-auto">
        <div class="max-w-7xl mx-auto p-12">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16">
                <div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-on-surface mb-2">Booking Management</h2>
                    <p class="text-on-surface-variant text-sm max-w-md">Tinjau, konfirmasi, dan kelola jadwal pemotretan klien.</p>
                </div>
            </header>

            <?php if(isset($_GET['pesan'])): ?>
                <div class="mb-8 p-4 bg-primary/10 border border-primary/20 text-primary text-xs font-label tracking-widest uppercase">
                    Data Berhasil <?= $_GET['pesan'] == 'updated' ? 'Diperbarui' : 'Dihapus' ?>
                </div>
            <?php endif; ?>

            <section class="bg-surface-container-lowest p-10 rounded-sm border border-outline-variant/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant/10">
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Customer & Paket</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Schedule & Location</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Status</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <?php
                            $query = "SELECT booking.*, users.nama, paket.nama_paket 
                                      FROM booking 
                                      JOIN users ON booking.id_user = users.id_user 
                                      JOIN paket ON booking.id_paket = paket.id_paket
                                      ORDER BY booking.id_booking DESC";
                            $result = mysqli_query($conn, $query);

                            if(mysqli_num_rows($result) > 0):
                                while($row = mysqli_fetch_assoc($result)):
                                    // Warna status
                                    $statusColor = "text-on-surface-variant";
                                    if($row['status'] == 'Menunggu') $statusColor = "text-primary";
                                    if($row['status'] == 'Dikonfirmasi') $statusColor = "text-blue-400";
                                    if($row['status'] == 'Selesai') $statusColor = "text-green-400";
                                    if($row['status'] == 'Batal') $statusColor = "text-red-400";
                            ?>
                            <tr class="group hover:bg-surface-container/30 transition-colors">
                                <td class="py-6">
                                    <div class="flex flex-col">
                                        <span class="text-on-surface font-semibold text-sm"><?= $row['nama'] ?></span>
                                        <span class="text-[10px] text-primary uppercase tracking-widest mt-1"><?= $row['nama_paket'] ?></span>
                                    </div>
                                </td>
                                <td class="py-6">
                                    <div class="flex flex-col text-sm text-on-surface-variant">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-xs">calendar_today</span>
                                            <?= date('d M Y', strtotime($row['tanggal'])) ?> • <?= $row['jam'] ?>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 opacity-60">
                                            <span class="material-symbols-outlined text-xs">location_on</span>
                                            <?= $row['lokasi'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6">
                                    <form method="POST" action="" class="flex items-center gap-2">
                                        <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="bg-surface-container-high border-none text-[10px] font-bold tracking-widest uppercase p-2 rounded-sm <?= $statusColor ?> focus:ring-1 focus:ring-primary">
                                            <option value="Menunggu" <?= $row['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                            <option value="Dikonfirmasi" <?= $row['status'] == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                                            <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                            <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : '' ?>>Batal</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="flex justify-end gap-4">
                                        <?php if(!empty($row['catatan'])): ?>
                                        <button title="<?= $row['catatan'] ?>" class="text-on-surface-variant/40 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-xl">comment</span>
                                        </button>
                                        <?php endif; ?>

                                        <a href="?hapus=<?= $row['id_booking'] ?>" onclick="return confirm('Hapus riwayat booking ini?')" class="text-on-surface-variant/40 hover:text-red-400 transition-colors">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="4" class="py-10 text-center text-on-surface-variant/30 italic text-sm">No bookings found.</td>
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