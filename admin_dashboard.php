<?php
include 'koneksi.php';
session_start();

// Proteksi: Hanya admin yang bisa masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// --- MENGAMBIL STATISTIK ASLI DARI DATABASE ---

// 1. Total Booking
$total_booking_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM booking");
$total_booking_data = mysqli_fetch_assoc($total_booking_query);
$total_booking = $total_booking_data['total'];

// 2. Total Pelanggan (User dengan role pelanggan)
$total_customer_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'pelanggan'");
$total_customer_data = mysqli_fetch_assoc($total_customer_query);
$total_customer = $total_customer_data['total'];

// 3. Menghitung Kapasitas Penyimpanan (Simulasi logika berdasarkan jumlah booking)
// Misal: Kapasitas maksimal studio adalah 100 booking per bulan, kita hitung persentasenya.
$max_capacity = 100; 
$storage_percent = ($total_booking / $max_capacity) * 100;
if ($storage_percent > 100) $storage_percent = 100; // Cap di 100%
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>OPPASTUDIO | Management Suite</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Inter:wght@300;400;600&family=Manrope:wght@500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "tertiary": "#b0c6f9",
                        "surface-bright": "#393939",
                        "on-background": "#e5e2e1",
                        "surface-dim": "#131313",
                        "primary-fixed": "#ffdea5",
                        "on-surface": "#e5e2e1",
                        "error": "#ffb4ab",
                        "surface-tint": "#e9c176",
                        "primary": "#e9c176",
                        "on-primary": "#412d00",
                        "background": "#131313",
                        "secondary": "#c8c6c5",
                        "outline": "#9a8f80",
                        "surface-container-lowest": "#0e0e0e",
                        "surface-container-low": "#1c1b1b",
                        "surface-container": "#201f1f",
                        "surface-container-high": "#2a2a2a",
                        "surface-container-highest": "#353534",
                        "on-surface-variant": "#d1c5b4",
                        "outline-variant": "#4e4639",
                        "error-container": "#93000a",
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #131313;
            color: #e5e2e1;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #353534; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e9c176; }
        @media print {
            .sidebar-admin, button { display: none !important; }
            main { width: 100% !important; padding: 0 !important; }
        }
    </style>
</head>

<body class="min-h-screen flex overflow-hidden">

    <?php include 'sidebar_admin.php'; ?>

    <main class="flex-1 bg-surface h-screen overflow-y-auto">
        <div class="max-w-7xl mx-auto p-12">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16">
                <div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-on-surface mb-2">Dashboard Overview</h2>
                    <p class="text-on-surface-variant text-sm max-w-md">Welcome back, <?= explode(' ', $_SESSION['nama'])[0] ?>. Here is the studio performance.</p>
                </div>
                <button onclick="window.print()" class="bg-primary text-on-primary font-label text-xs font-bold tracking-widest uppercase px-8 py-4 rounded-sm hover:brightness-110 transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                    Print Report
                </button>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-surface-container-low p-8 border-l-2 border-transparent hover:border-primary transition-all group">
                    <p class="font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase mb-4">Total Bookings</p>
                    <div class="flex items-end gap-3">
                        <h3 class="font-headline text-5xl font-bold text-primary"><?= number_format($total_booking) ?></h3>
                        <span class="text-tertiary text-xs font-label mb-2">Live</span>
                    </div>
                </div>
                
                <div class="bg-surface-container-low p-8 border-l-2 border-transparent hover:border-primary transition-all group">
                    <p class="font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase mb-4">Studio Occupancy</p>
                    <div class="flex items-end gap-3">
                        <h3 class="font-headline text-5xl font-bold text-on-surface"><?= round($storage_percent) ?><span class="text-2xl">%</span></h3>
                        <span class="text-on-surface-variant/40 text-xs font-label mb-2">Capacity</span>
                    </div>
                </div>

                <div class="bg-surface-container-low p-8 border-l-2 border-transparent hover:border-primary transition-all group">
                    <p class="font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase mb-4">Total Customers</p>
                    <div class="flex items-end gap-3">
                        <h3 class="font-headline text-5xl font-bold text-on-surface"><?= number_format($total_customer) ?></h3>
                        <span class="text-tertiary text-xs font-label mb-2">Users</span>
                    </div>
                </div>
            </section>

            <section class="bg-surface-container-lowest p-10 rounded-sm border border-outline-variant/5">
                <div class="flex justify-between items-center mb-10">
                    <h4 class="font-headline text-xl font-bold text-on-surface">Recent Bookings</h4>
                    <div class="flex gap-2">
                        <button class="p-2 text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">filter_list</span></button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant/10">
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Customer</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Location</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Date</th>
                                <th class="pb-6 font-label text-[10px] tracking-[0.2em] text-on-surface-variant/50 uppercase">Status</th>
                                <th class="pb-6"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <?php
                            // Query mengambil 5 booking terbaru
                            $query = "SELECT booking.*, users.nama 
                                      FROM booking 
                                      JOIN users ON booking.id_user = users.id_user 
                                      ORDER BY id_booking DESC LIMIT 5";
                            $result = mysqli_query($conn, $query);

                            if(mysqli_num_rows($result) > 0):
                                while($row = mysqli_fetch_assoc($result)):
                            ?>
                            <tr class="group hover:bg-surface-container/30 transition-colors">
                                <td class="py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-[10px] font-bold text-primary border border-primary/20">
                                            <?= strtoupper(substr($row['nama'], 0, 2)) ?>
                                        </div>
                                        <span class="text-on-surface font-semibold text-sm"><?= $row['nama'] ?></span>
                                    </div>
                                </td>
                                <td class="py-6 text-on-surface-variant text-sm"><?= $row['lokasi'] ?></td>
                                <td class="py-6 text-on-surface-variant text-sm font-label"><?= date('M d, Y', strtotime($row['tanggal'])) ?></td>
                                <td class="py-6">
                                    <?php 
                                        $statusClass = "bg-primary/10 text-primary";
                                        if($row['status'] == 'Selesai') $statusClass = "bg-green-500/10 text-green-400";
                                        if($row['status'] == 'Batal') $statusClass = "bg-red-500/10 text-red-400";
                                    ?>
                                    <span class="px-2 py-1 <?= $statusClass ?> text-[10px] font-bold tracking-widest uppercase rounded-sm">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td class="py-6 text-right">
                                    <a href="admin_booking_detail.php?id=<?= $row['id_booking'] ?>" class="text-on-surface-variant/40 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">more_vert</span>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="py-10 text-center text-on-surface-variant/30 italic text-sm">No recent bookings found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-12 pb-12">
                <div class="bg-surface-container-low p-10 border border-outline-variant/5">
                    <h5 class="font-headline text-lg font-bold mb-6">Studio Load</h5>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant">Monthly Booking Target</span>
                            <span class="font-label"><?= round($storage_percent) ?>%</span>
                        </div>
                        <div class="w-full h-1 bg-surface-container-highest rounded-full overflow-hidden">
                            <div class="h-full bg-primary" style="width: <?= $storage_percent ?>%"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-6 mt-8">
                            <div class="border-l-2 border-primary/20 pl-4">
                                <p class="text-[10px] font-label text-on-surface-variant/50 uppercase">Active</p>
                                <p class="text-lg font-headline font-bold"><?= $total_booking ?> Sessions</p>
                            </div>
                            <div class="border-l-2 border-primary/20 pl-4">
                                <p class="text-[10px] font-label text-on-surface-variant/50 uppercase">Customers</p>
                                <p class="text-lg font-headline font-bold"><?= $total_customer ?> Registered</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-end">
                        <h5 class="font-headline text-lg font-bold">Newest Uploads</h5>
                        <button class="text-primary text-[10px] font-label font-bold tracking-widest uppercase hover:underline">View All</button>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <img src="https://images.unsplash.com/photo-1542038784456-1ea8e935640e?q=80&w=2070&auto=format&fit=crop" alt="Editorial Fashion" class="w-full h-40 object-cover grayscale hover:grayscale-0 transition-all duration-500 rounded-sm" />
                        <img src="https://images.unsplash.com/photo-1492691523567-6170c2298b4e?q=80&w=2070&auto=format&fit=crop" alt="Architectural Minimalist" class="w-full h-40 object-cover grayscale hover:grayscale-0 transition-all duration-500 rounded-sm" />
                        <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=1964&auto=format&fit=crop" alt="Cinematic Portrait" class="w-full h-40 object-cover grayscale hover:grayscale-0 transition-all duration-500 rounded-sm" />
                    </div>
                </div>
            </section>

        </div>
    </main>
</body>
</html>