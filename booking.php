<?php
include 'config/koneksi.php';
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$notif = ""; 

// Fungsi Helper untuk Kirim API WhatsApp
function kirimWhatsApp($target, $pesan) {
    $token = "CxQ7iSS8Bep55J3yFfL7"; 

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $pesan,
            'countryCode' => '62', // otomatis handle kode negara Indonesia
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// 2. Logika Simpan Data
if (isset($_POST['submit_booking'])) {
    $id_user = $_SESSION['id_user']; 
    
    // Gunakan null coalescing operator (?? '') agar jika null, otomatis diubah jadi string kosong
    $id_paket      = mysqli_real_escape_string($conn, $_POST['id_paket'] ?? '');
    $id_fotografer = mysqli_real_escape_string($conn, $_POST['id_fotografer'] ?? ''); 
    $tanggal       = mysqli_real_escape_string($conn, $_POST['tanggal'] ?? '');
    $jam           = mysqli_real_escape_string($conn, $_POST['jam'] ?? '');
    $lokasi        = mysqli_real_escape_string($conn, $_POST['lokasi'] ?? '');
    $catatan       = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');
    
    // Status ENUM yang sudah diselaraskan dengan database Anda
    $status_booking = "menunggu";  // Sesuai enum tabel booking
    $status_jadwal  = "terjadwal"; // Sesuai enum('terjadwal','selesai') di tabel jadwal

    // Validasi Sisi Server Tambahan (Double Check untuk Batasan 2 Jadwal Per Hari)
    $cek_kuota = mysqli_query($conn, "SELECT COUNT(*) as total FROM booking WHERE tanggal = '$tanggal' AND status != 'batal'");
    $data_kuota = mysqli_fetch_assoc($cek_kuota);

    if ($data_kuota['total'] >= 2) {
        $notif = "penuh";
    } else {
        // Ambil data nama paket untuk isi pesan WA
        $query_nama_paket = mysqli_query($conn, "SELECT nama_paket FROM paket WHERE id_paket = '$id_paket'");
        $data_paket = mysqli_fetch_assoc($query_nama_paket);
        $nama_paket = ucwords($data_paket['nama_paket'] ?? 'Paket Pilihan');

        // Ambil data nama fotografer untuk isi pesan WA (Mencari lewat relasi tabel fotografer)
        $query_nama_foto = mysqli_query($conn, "SELECT u.nama FROM fotografer f JOIN users u ON f.id_user = u.id_user WHERE f.id_fotografer = '$id_fotografer'");
        $data_foto = mysqli_fetch_assoc($query_nama_foto);
        $nama_fotografer = ucwords($data_foto['nama'] ?? 'Fotografer');

        // 1. Masukkan data ke tabel booking
        $sql = "INSERT INTO booking (id_user, id_paket, id_fotografer, lokasi, tanggal, jam, catatan, status) 
                VALUES ('$id_user', '$id_paket', '$id_fotografer', '$lokasi', '$tanggal', '$jam', '$catatan', '$status_booking')";
        
        if (mysqli_query($conn, $sql)) {
            // 2. Ambil id_booking yang baru saja digenerate oleh sistem
            $id_booking_baru = mysqli_insert_id($conn);

            // 3. Masukkan ke tabel jadwal
            $sql_jadwal = "INSERT INTO jadwal (id_booking, id_fotografer, tanggal, jam, status) 
                           VALUES ('$id_booking_baru', '$id_fotografer', '$tanggal', '$jam', '$status_jadwal')";
            
            mysqli_query($conn, $sql_jadwal);

            $notif = "success";

            // --- SISTEM OTOMATISASI WHATSAPP ---
            $nama_pelanggan = $_SESSION['nama'] ?? 'Pelanggan';
            $no_hp_pelanggan = $_SESSION['no_hp'] ?? ''; 

            $format_tanggal = date('d M Y', strtotime($tanggal));
            $pesan_wa = "*OPPASTUDIO | Booking Confirmation*\n\n";
            $pesan_wa .= "Halo Pelanggan atas Nama, *{$nama_pelanggan}*.\n\n";
            $pesan_wa .= "Terima kasih telah mempercayakan momen berharga Anda kepada kami. Reservasi sesi foto Anda telah berhasil direkam dengan rincian berikut:\n\n";
            $pesan_wa .= "▪️ *Paket:* {$nama_paket}\n";
            $pesan_wa .= "▪️ *Visual Artist:* {$nama_fotografer}\n";
            $pesan_wa .= "▪️ *Tanggal:* {$format_tanggal}\n";
            $pesan_wa .= "▪️ *Waktu:* {$jam} WITA\n";
            $pesan_wa .= "▪️ *Lokasi:* {$lokasi}\n";
            $pesan_wa .= "▪️ *Status:* Terjadwal\n\n";
            $pesan_wa .= "_Pesan ini dikirimkan secara otomatis oleh sistem OPPASTUDIO Suite._";

            if (!empty($no_hp_pelanggan)) {
                kirimWhatsApp($no_hp_pelanggan, $pesan_wa);
            }
        } else {
            $notif = "error";
        }
    }
} 
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Book Your Session | OPPASTUDIO</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Inter:wght@400;500&family=Manrope:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#e9c176",
                        "on-primary": "#412d00",
                        "primary-container": "#c5a059",
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
                }
            }
        }
    </script>
    
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #131313; color: #d1c5b4; font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        .editorial-shadow { box-shadow: 0 48px 100px -12px rgba(233, 193, 118, 0.05); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #353534; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e9c176; }
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(0.8); cursor: pointer; }
        .pac-container { 
            background-color: #1c1b1b; 
            border: 1px solid #4e4639; 
            font-family: 'Inter', sans-serif; 
            z-index: 9999;
        }
        .pac-item { 
            border-top: 1px solid #353534; 
            padding: 10px; 
            color: #e5e2e1; 
        }
        .pac-item:hover { 
            background-color: #0e0e0e; 
        }
        .pac-item-query { 
            color: #e9c176; 
        }

        /* Desain Kustomisasi Tema Gelap FullCalendar agar Menyatu Sempurna dengan Website */
        .fc { background: #0e0e0e; border: 1px solid #4e4639; border-radius: 4px; padding: 12px; font-family: 'Inter', sans-serif; }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #353534 !important; }
        .fc-col-header-cell { background: #1c1b1b; padding: 8px 0 !important; color: #e9c176 !important; font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; }
        .fc-daygrid-day-number { color: #e5e2e1; padding: 6px !important; font-size: 13px; }
        .fc-toolbar-title { font-family: 'Noto Serif', serif; color: #e5e2e1 !important; font-size: 1.25rem !important; text-transform: uppercase; letter-spacing: 0.05em; }
        .fc-button-primary { background-color: #1c1b1b !important; border: 1px solid #4e4639 !important; color: #e9c176 !important; }
        .fc-button-primary:hover { background-color: #e9c176 !important; color: #412d00 !important; }
        .fc-button-primary:disabled { background-color: #0e0e0e !important; color: #353534 !important; opacity: 0.5; }
        .fc-day-today { background: rgba(233, 193, 118, 0.05) !important; }
        .fc-daygrid-bg-harness { cursor: not-allowed; }
        .fc-highlight { background: rgba(233, 193, 118, 0.25) !important; }
    </style>
</head>

<body class="bg-surface selection:bg-primary-container selection:text-on-primary-container overflow-x-hidden">

   <?php include 'includes/navbar.php'; ?>

    <main class="pt-40 pb-24 px-6 md:px-12 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            
            <div class="lg:col-span-5 space-y-12">
                <header class="space-y-6">
                    <span class="font-label text-xs tracking-[0.4em] text-primary uppercase inline-block">Reserve Your Date</span>
                    <h1 class="text-6xl md:text-7xl font-headline text-on-surface leading-tight tracking-tighter">
                        Tangkap yang <br/><span class="italic text-primary">Tak terlihat.</span>
                    </h1>
                    <p class="text-on-surface-variant text-lg max-w-md leading-relaxed">
                       Fotografi seni rupa bukan hanya tentang gambar—tetapi juga tentang warisan. Kami menyusun sesi abadi yang disesuaikan dengan narasi unik Anda.
                    </p>
                </header>

                <div class="relative group">
                    <div class="aspect-[4/5] bg-surface-container-low overflow-hidden editorial-shadow border border-white/5">
                        <img src="asset/kamera2.jpg" 
                             alt="Vintage medium format camera in studio" 
                             class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:scale-105 transition-all duration-1000" />
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-surface-container-lowest p-8 border-l-4 border-primary shadow-2xl">
                        <p class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase mb-1">Next Availability</p>
                        <p class="font-headline text-2xl text-primary font-bold">OCT 2026</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 bg-surface-container-low p-8 md:p-16 relative border border-white/5">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] select-none text-primary">
                    <span class="material-symbols-outlined text-[12rem]" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
                </div>
                
                <?php if($notif == "success"): ?>
                    <div class="bg-primary/20 border border-primary text-primary p-4 mb-6 text-sm relative z-20">
                        Booking berhasil dikirim! Rincian pesanan telah dikirimkan ke nomor WhatsApp Anda. Kurator kami akan segera menghubungi Anda.
                    </div>
                <?php elseif($notif == "penuh"): ?>
                    <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 mb-6 text-sm relative z-20">
                        Maaf, tanggal yang Anda pilih baru saja penuh. Silakan tentukan tanggal pendaftaran lainnya pada kalender di bawah.
                    </div>
                <?php elseif($notif == "error"): ?>
                    <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 mb-6 text-sm relative z-20">
                        Terjadi kesalahan saat menyimpan data. Silakan coba lagi.
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="space-y-8 relative z-10">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Pilih Paket</label>
                            <select name="id_paket" required class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface text-sm appearance-none">
                                <option value="" disabled selected>— SELECT A PACKAGE —</option>
                                <?php
                                $query_paket = mysqli_query($conn, "SELECT id_paket, nama_paket FROM paket");
                                while($p = mysqli_fetch_assoc($query_paket)) {
                                    $selected = (isset($_GET['id_paket']) && $_GET['id_paket'] == $p['id_paket']) ? 'selected' : '';
                                    echo "<option value='".$p['id_paket']."' $selected>".ucwords($p['nama_paket'])."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Pilih Fotografer / Visual Artist</label>
                            <select name="id_fotografer" required class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface text-sm appearance-none">
                                <option value="" disabled selected>— SELECT ARTIST —</option>
                                <?php
                                // Hanya mengambil fotografer dari database dengan role 'fotografer'
                                $query_select_foto = mysqli_query($conn, "SELECT f.id_fotografer, u.nama 
                                                                        FROM fotografer f 
                                                                        JOIN users u ON f.id_user = u.id_user 
                                                                        WHERE u.role = 'fotografer'");
                                
                                if (mysqli_num_rows($query_select_foto) > 0) {
                                    while ($foto = mysqli_fetch_assoc($query_select_foto)) {
                                        echo "<option value='".$foto['id_fotografer']."'>".ucwords($foto['nama'])."</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>Belum ada fotografer terdaftar di database</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Lokasi Pemotretan</label>
                        <input type="text" id="cari-lokasi" name="lokasi" placeholder="KETIK ALAMAT ATAU PILIH TITIK DI PETA..." required
                            class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface placeholder:text-outline-variant/30 text-sm" />
                        <div id="map" class="w-full h-44 bg-surface-container-lowest mt-2 border border-white/5 rounded-sm overflow-hidden"></div>
                    </div>

                    <div class="space-y-2">
                        <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Pilih Tanggal Sesi Foto</label>
                        <div id="kalender-booking" class="w-full"></div>
                        
                        <input type="hidden" id="tanggal" name="tanggal" required />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2 md:col-span-2">
                            <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Waktu/Jam</label>
                            <input type="time" name="jam" required class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface text-sm" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Catatan Khusus</label>
                        <textarea name="catatan" placeholder="TELL US ABOUT YOUR VISION..." rows="4" 
                                class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface placeholder:text-outline-variant/30 text-sm resize-none"></textarea>
                    </div>

                    <div class="pt-4 space-y-6">
                        <div class="flex items-start gap-4 p-4 bg-primary/5 border-l-2 border-primary/30">
                            <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">info</span>
                            <p class="font-label text-[10px] leading-relaxed tracking-wider text-on-surface-variant uppercase">
                                Status booking Anda akan muncul sebagai "Menunggu" sampai dikonfirmasi oleh admin. Notifikasi instan rincian pemesanan dikirim langsung ke WhatsApp Anda.
                            </p>
                        </div>
                        
                        <button type="submit" name="submit_booking" class="w-full group bg-primary text-on-primary py-5 rounded-sm font-label text-xs tracking-[0.4em] uppercase hover:brightness-110 transition-all flex items-center justify-center gap-3">
                            Confirm Booking
                            <span class="material-symbols-outlined text-sm group-hover:translate-x-2 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <section class="py-24 bg-surface-container-lowest border-y border-white/5">
        <div class="max-w-7xl mx-auto px-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div class="space-y-4 group">
                    <span class="font-headline text-5xl text-primary/10 group-hover:text-primary/30 transition-colors">01</span>
                    <h3 class="font-headline text-xl text-on-surface">Konsultasi</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Dialog langsung untuk memahami preferensi estetika Anda dan suasana sesi pemotretan.</p>
                </div>
                <div class="space-y-4 group">
                    <span class="font-headline text-5xl text-primary/10 group-hover:text-primary/30 transition-colors">02</span>
                    <h3 class="font-headline text-xl text-on-surface">Sesi</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Sesi fotografi santai dan terpandu yang berfokus pada momen otentik dan komposisi artistik.</p>
                </div>
                <div class="space-y-4 group">
                    <span class="font-headline text-5xl text-primary/10 group-hover:text-primary/30 transition-colors">03</span>
                    <h3 class="font-headline text-xl text-on-surface">Kurasi</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Gambar-gambar pilihan yang diedit dengan cermat dikirim melalui galeri digital pribadi.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-black py-20 px-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 items-start">
            <div class="space-y-6">
                <span class="text-2xl font-headline text-primary font-bold">OPPASTUDIO</span>
                <p class="font-label text-[10px] tracking-widest uppercase text-on-surface-variant/50">© 2026 OPPASTUDIO. <br>All Rights Reserved.</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">Instagram</a>
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">LinkedIn</a>
                </div>
                <div class="space-y-4">
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">Contact</a>
                    <a href="https://maps.app.goo.gl/Gy1QxXtdCbkTo27R7" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">Studio</a>
                </div>
            </div>

            <div class="md:text-right">
                <span class="font-headline text-primary block mb-4 italic">Join the inner circle</span>
                <div class="flex border-b border-primary/30 pb-2">
                    <input type="email" placeholder="EMAIL ADDRESS" class="bg-transparent border-none focus:ring-0 text-[10px] font-label tracking-widest p-0 w-full uppercase text-on-surface" />
                    <button class="material-symbols-outlined text-primary text-sm">arrow_forward</button>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('kalender-booking');
            const hiddenInputTanggal = document.getElementById('tanggal');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                selectable: true,
                unselectAuto: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                
                // Mengambil data ketersediaan counter dari file get_jadwal.php
                events: 'get_jadwal.php',

                // Batas bawah pemilihan tanggal (hari ini) agar tidak memesan masa lalu
                validRange: {
                    start: new Date().toISOString().split('T')[0]
                },

                select: function(info) {
                    const tanggalTerpilih = info.startStr;
                    const semuaEvent = calendar.getEvents();
                    
                    // Cek jika tanggal yang diklik ditandai penuh oleh server backend
                    const apakahPenuh = semuaEvent.some(event => {
                        return event.startStr === tanggalTerpilih && event.extendedProps.isFull === true;
                    });

                    if (apakahPenuh) {
                        alert("Maaf, kuota pemesanan tanggal " + tanggalTerpilih + " sudah penuh (Maksimal 2 sesi per hari). Silakan tentukan tanggal lain.");
                        calendar.unselect();
                        hiddenInputTanggal.value = "";
                    } else {
                        // Pasang value tanggal ke hidden input untuk dibaca oleh $_POST['tanggal'] PHP
                        hiddenInputTanggal.value = tanggalTerpilih;
                    }
                }
            });

            calendar.render();
        });
    </script>

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn && mobileMenu) {
            const menuIcon = menuBtn.querySelector('.material-symbols-outlined');
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                menuIcon.textContent = mobileMenu.classList.contains('hidden') ? 'menu' : 'close';
            });
        }
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Koordinat Awal (Default: Ende, NTT)
        const defaultLat = -8.8407;
        const defaultLng = 121.6528;

        // 2. Buat Pilihan Gaya Peta (Layers)
        const modeGelap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        const modeTerang = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
        const modeSatelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });

        // 3. Inisialisasi Peta (Default menggunakan Mode Gelap agar cocok dengan web Anda)
        const map = L.map('map', {
            center: [defaultLat, defaultLng],
            zoom: 14,
            layers: [modeGelap] // Peta awal yang muncul
        });

        // 4. Tambahkan Tombol Pengubah Gaya Peta di Pojok Kanan Atas
        const baseMaps = {
            "Tema Gelap (Rekomendasi)": modeGelap,
            "Tema Terang Standar": modeTerang,
            "Peta Satelit / Foto Udara": modeSatelit
        };
        L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

        // 5. Tambahkan Penanda (Marker) Berwarna Biru Premium yang Bisa Digeser
        const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        marker.bindPopup("<b>Lokasi Pemotretan</b><br>Geser saya ke lokasi yang tepat!").openPopup();

        // Hubungkan dengan input text HTML Anda
        const inputLokasi = document.getElementById("cari-lokasi");

        // Fungsi memperbarui teks input dan isi pop-up peta
        function perbaruiLokasi(lat, lng, namaTempat = "") {
            if (namaTempat) {
                inputLokasi.value = `${namaTempat} (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
                marker.setPopupContent(`<b>Lokasi Terpilih:</b><br>${namaTempat}`).openPopup();
            } else {
                inputLokasi.value = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                marker.setPopupContent(`<b>Koordinat Terpilih:</b><br>${lat.toFixed(6)}, ${lng.toFixed(6)}`).openPopup();
            }
        }

        // A. INTERAKSI: Saat Penanda (Marker) Selesai Digeser oleh Pengguna
        marker.on('dragend', function() {
            const posisi = marker.getLatLng();
            perbaruiLokasi(posisi.lat, posisi.lng);
        });

        // B. INTERAKSI: Saat Peta Diklik di Mana Saja (Pindahkan Marker Otomatis)
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            marker.setLatLng([lat, lng]);
            perbaruiLokasi(lat, lng);
        });

        // C. Tambahkan fitur Geocoder Kontrol Pencarian
        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false
        })
        .on('markgeocode', function(e) {
            const center = e.geocode.center;
            const name = e.geocode.name;
            map.setView(center, 16);
            marker.setLatLng(center);
            perbaruiLokasi(center.lat, center.lng, name);
        })
        .addTo(map);
    });
    </script>
</body>
</html>