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
    $id_paket = mysqli_real_escape_string($conn, $_POST['id_paket']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
    $status = "Menunggu"; 

    // Ambil data nama paket untuk isi pesan WA
    $query_nama_paket = mysqli_query($conn, "SELECT nama_paket FROM paket WHERE id_paket = '$id_paket'");
    $data_paket = mysqli_fetch_assoc($query_nama_paket);
    $nama_paket = ucwords($data_paket['nama_paket'] ?? 'Paket Pilihan');

    $sql = "INSERT INTO booking (id_user, id_paket, lokasi, tanggal, jam, catatan, status) 
            VALUES ('$id_user', '$id_paket', '$lokasi', '$tanggal', '$jam', '$catatan', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        $notif = "success";

        // --- SISTEM OTOMATISASI WHATSAPP ---
        
        // 1. Ambil nomor HP & Nama pelanggan dari sesi login atau database user
        // Pastikan format nomor di database aman (contoh: 08123xxx atau 628123xxx)
        $nama_pelanggan = $_SESSION['nama'] ?? 'Pelanggan';
        $no_hp_pelanggan = $_SESSION['no_hp'] ?? ''; // Sesuaikan key session No HP di sistem Anda

        // 2. Format teks template pesan profesional ala OPPASTUDIO
        $format_tanggal = date('d M Y', strtotime($tanggal));
        $pesan_wa = "*OPPASTUDIO | Booking Confirmation*\n\n";
        $pesan_wa .= "Halo, *{$nama_pelanggan}*.\n\n";
        $pesan_wa .= "Terima kasih telah mempercayakan momen berharga Anda kepada kami. Reservasi sesi foto Anda telah berhasil direkam ke dalam sistem dengan rincian berikut:\n\n";
        $pesan_wa .= "▪️ *Paket:* {$nama_paket}\n";
        $pesan_wa .= "▪️ *Tanggal:* {$format_tanggal}\n";
        $pesan_wa .= "▪️ *Waktu:* {$jam} WIB\n";
        $pesan_wa .= "▪️ *Lokasi:* {$lokasi}\n";
        $pesan_wa .= "▪️ *Status:* Menunggu Konfirmasi\n\n";
        $pesan_wa .= "Kurator dan tim administrasi kami akan segera meninjau jadwal Anda serta menghubungi Anda kembali untuk detail persiapan teknis pemotretan.\n\n";
        $pesan_wa .= "_Pesan ini dikirimkan secara otomatis oleh sistem OPPASTUDIO Management Suite._";

        // 3. Eksekusi pengiriman via API gateway jika nomor handphone tersedia
        if (!empty($no_hp_pelanggan)) {
            kirimWhatsApp($no_hp_pelanggan, $pesan_wa);
        }
    } else {
        $notif = "error";
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
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        <p class="font-headline text-2xl text-primary font-bold">OCT 2024</p>
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
                            <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Lokasi Pemotretan</label>
                            <input type="text" name="lokasi" placeholder="STUDIO A / JAKARTA" required
                                   class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface placeholder:text-outline-variant/30 text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="font-label text-[10px] tracking-widest text-on-surface-variant uppercase font-bold">Tanggal</label>
                            <input type="date" name="tanggal" required class="w-full bg-surface-container-lowest border-none focus:ring-1 focus:ring-primary/50 transition-all p-4 text-on-surface text-sm" />
                        </div>
                        <div class="space-y-2">
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
                <p class="font-label text-[10px] tracking-widest uppercase text-on-surface-variant/50">© 2024 OPPASTUDIO. <br>All Rights Reserved.</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">Instagram</a>
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">LinkedIn</a>
                </div>
                <div class="space-y-4">
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">Contact</a>
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">Studio</a>
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
</body>
</html>