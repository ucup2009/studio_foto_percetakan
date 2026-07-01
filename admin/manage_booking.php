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
    header("Location: manage_booking.php?pesan=updated");
    exit;
}

// Logika Hapus Booking
if (isset($_GET['hapus'])) {
    $id_booking = mysqli_real_escape_string($conn, $_GET['hapus']);

    // Jika Anda sudah menjalankan ON DELETE CASCADE di database, query ini akan otomatis menghapus jadwal terkait
    $query_hapus = "DELETE FROM booking WHERE id_booking = '$id_booking'";
    
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>
                alert('Data booking dan seluruh jadwal fotografer terkait berhasil dibersihkan!');
                window.location.href='manage_booking.php';
              </script>";
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
}

// Tangkap Variabel Pencarian dan Filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_status = isset($_GET['status_filter']) ? mysqli_real_escape_string($conn, $_GET['status_filter']) : '';
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
        select { background-image: none !important; }
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

            <div class="mb-8 bg-surface-container-low p-6 rounded-sm border border-outline-variant/10">
                <form method="GET" action="" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="w-full md:w-1/2 relative">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-on-surface-variant/40 text-lg">search</span>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama customer atau paket..." 
                               class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary text-sm p-3 pl-12 text-on-surface placeholder:text-on-surface-variant/30 transition-all rounded-sm">
                    </div>
                    
                    <div class="w-full md:w-auto flex flex-col md:flex-row gap-4 items-stretch md:items-center">
                        <div class="relative min-w-[160px]">
                            <select name="status_filter" class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary text-xs font-bold tracking-widest uppercase p-3.5 text-on-surface-variant rounded-sm appearance-none cursor-pointer">
                                <option value="">— ALL STATUS —</option>
                                <option value="Menunggu" <?= $filter_status == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option value="Dikonfirmasi" <?= $filter_status == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                                <option value="Selesai" <?= $filter_status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="Batal" <?= $filter_status == 'Batal' ? 'selected' : '' ?>>Batal</option>
                            </select>
                        </div>

                        <button type="submit" class="bg-primary text-on-primary font-label text-[10px] font-bold tracking-widest uppercase px-6 py-3.5 rounded-sm hover:brightness-110 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">filter_alt</span> Filter
                        </button>

                        <?php if(!empty($search) || !empty($filter_status)): ?>
                            <a href="manage_booking.php" class="border border-outline-variant/30 text-on-surface-variant font-label text-[10px] font-bold tracking-widest uppercase px-6 py-3.5 rounded-sm hover:bg-surface-container-lowest transition-all flex items-center justify-center">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <section class="bg-surface-container-lowest p-10 rounded-sm border border-outline-variant/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant/10">
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Customer & Paket</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Photographer</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Schedule & Location</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Status</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <?php
                            // MODIFIKASI KONDISI QUERY SQL BERDASARKAN FILTER/PENCARIAN
                            $where_clauses = [];
                            
                            if (!empty($search)) {
                                $where_clauses[] = "(klien.nama LIKE '%$search%' OR paket.nama_paket LIKE '%$search%')";
                            }
                            
                            if (!empty($filter_status)) {
                                $where_clauses[] = "booking.status = '$filter_status'";
                            }
                            
                            $where_sql = "";
                            if (count($where_clauses) > 0) {
                                $where_sql = "WHERE " . implode(' AND ', $where_clauses);
                            }

                            $query = "SELECT booking.*, 
                                             klien.nama AS nama_klien, 
                                             paket.nama_paket,
                                             u_fg.nama AS nama_fotografer
                                      FROM booking 
                                      JOIN users AS klien ON booking.id_user = klien.id_user 
                                      JOIN paket ON booking.id_paket = paket.id_paket
                                      LEFT JOIN fotografer AS fg ON booking.id_fotografer = fg.id_fotografer
                                      LEFT JOIN users AS u_fg ON fg.id_user = u_fg.id_user
                                      $where_sql
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
                                        <span class="text-on-surface font-semibold text-sm"><?= $row['nama_klien'] ?></span>
                                        <span class="text-[10px] text-primary uppercase tracking-widest mt-1"><?= $row['nama_paket'] ?></span>
                                    </div>
                                </td>
                                
                                <td class="py-6">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="material-symbols-outlined text-sm text-[#e9c176]">camera_roll</span>
                                        <span class="font-medium <?= !empty($row['nama_fotografer']) ? 'text-gray-200' : 'text-gray-500 italic' ?>">
                                            <?= !empty($row['nama_fotografer']) ? htmlspecialchars($row['nama_fotografer']) : 'Belum Ditentukan' ?>
                                        </span>
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
                                <td colspan="5" class="py-10 text-center text-on-surface-variant/30 italic text-sm">No bookings found.</td>
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