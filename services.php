<?php
include 'koneksi.php';

// Ambil data paket fotografi
$query_paket = mysqli_query($conn, "SELECT * FROM paket ORDER BY id_paket ASC");




// mengambil data layanan cetak
$query_cetak = mysqli_query($conn, "SELECT * FROM layanan_cetak ORDER BY id_layanan ASC");

?>
<!DOCTYPE html>
<html class="dark" lang="en">





<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Our Services | OPPASTUDIO</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500&family=Manrope:wght@500;700&display=swap" rel="stylesheet" />
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
                        "background": "#131313",
                        "surface": "#131313",
                        "on-surface": "#e5e2e1",
                        "surface-container": "#201f1f",
                        "surface-container-low": "#1c1b1b",
                        "surface-container-highest": "#353534",
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #131313;
            color: #d1c5b4;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #e9c176; border-radius: 10px; }
    </style>
</head>

<body class="bg-surface text-on-surface-variant selection:bg-primary/30">

    <?php include 'navbar.php'; ?>

    

    <main class="pt-40 pb-24">
        <section class="px-8 md:px-12 mb-24 max-w-7xl mx-auto">
            <div class="max-w-4xl space-y-8">
                <span class="font-label text-primary tracking-[0.5em] uppercase text-[10px] block">Service Menu</span>
                <h1 class="text-6xl md:text-8xl font-headline tracking-tighter text-on-surface leading-none">
                    Membangun <br/><span class="text-primary italic font-normal">Warisan Anda.</span>
                </h1>
                <p class="text-lg md:text-xl font-body max-w-2xl leading-relaxed text-on-surface-variant opacity-80">
                    Penceritaan visual yang luar biasa melalui fotografi presisi dan pencetakan artisanal. Pilih pengalaman yang dikurasi yang sesuai dengan narasi Anda.
                </p>
            </div>
        </section>

        <section class="px-8 md:px-12 mb-32 max-w-7xl mx-auto">
            <div class="flex items-center gap-6 mb-16">
                <span class="font-label text-xs tracking-[0.3em] uppercase text-primary">01</span>
                <h2 class="text-3xl md:text-4xl font-headline text-on-surface">Paket Fotografi</h2>
                <div class="h-[1px] flex-grow bg-white/5"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php while($row = mysqli_fetch_assoc($query_paket)): ?>
                    <?php 
                        // Logika untuk menentukan gaya kartu (Premium vs Biasa)
                        $is_premium = (stripos($row['nama_paket'], 'Premium') !== false || $row['id_paket'] == 2);
                        $card_class = $is_premium 
                            ? "bg-surface-container border-l-4 border-primary relative overflow-hidden group shadow-2xl" 
                            : "bg-surface-container-low border border-white/5 hover:border-primary/50 transition-all duration-700 group";
                    ?>
                    
                    <div class="<?= $card_class ?> p-10 flex flex-col justify-between h-full">
                        <?php if($is_premium): ?>
                            <div class="absolute top-0 right-0 bg-primary px-4 py-1 text-[9px] font-label font-bold text-on-primary uppercase tracking-widest">Most Selected</div>
                        <?php endif; ?>

                        <div class="space-y-12">
                            <div class="flex justify-between items-start">
                                <h3 class="text-2xl font-headline text-on-surface"><?= $row['nama_paket'] ?></h3>
                                <span class="font-label text-[10px] text-primary/60 uppercase tracking-widest">Photography</span>
                            </div>
                            <div class="space-y-2">
                                <span class="text-4xl font-headline text-on-surface">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                                <p class="text-[10px] font-label text-on-surface-variant/40 uppercase tracking-widest"><?= $row['durasi'] ?> Minutes Session</p>
                            </div>
                            
                            <div class="text-xs font-body leading-relaxed opacity-70 group-hover:text-on-surface transition-colors">
                                <?= $row['deskripsi'] ?>
                            </div>
                        </div>

                        <a href="booking.php?id_paket=<?= $row['id_paket'] ?>" class="block w-full text-center mt-12 bg-surface-container-highest text-on-surface font-label text-[10px] tracking-[0.3em] uppercase py-4 rounded-sm group-hover:bg-primary group-hover:text-on-primary transition-all">
                            Book Now
                        </a>
        </div>
    <?php endwhile; ?>
</div>
        </section>

        <section class="mb-32 px-8 md:px-12 max-w-7xl mx-auto">
            <div class="relative h-[500px] w-full border border-white/5 overflow-hidden">
                <img src="asset/lig.jpg" 
                     alt="Professional studio lighting setup" 
                     class="w-full h-full object-cover grayscale opacity-40 hover:scale-105 transition-transform duration-1000" />
                <div class="absolute bottom-8 right-8 bg-background p-8 md:p-12 max-w-md border-l-4 border-primary shadow-2xl">
                    <p class="font-headline text-xl md:text-2xl text-on-surface italic leading-relaxed">
                        "Kamera adalah sebuah instrumen yang mengajarkan orang cara melihat tanpa kamera."
                    </p>
                </div>
            </div>
        </section>

        <section class="px-8 md:px-12 max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row gap-16">
                <div class="md:w-1/3 space-y-6">
                    <div class="flex items-center gap-4">
                        <span class="font-label text-xs tracking-[0.3em] uppercase text-primary">02</span>
                        <h2 class="text-3xl font-headline text-on-surface">Layanan Cetak</h2>
                    </div>
                    <p class="text-on-surface-variant font-body leading-relaxed opacity-70">
                        Kami menggunakan kertas arsip berkualitas museum dan sistem tinta yang terkalibrasi untuk memastikan kenangan Anda bertahan selama beberapa generasi.
                    </p>
                </div>
                
                <div class="md:w-2/3">
            <div class="grid grid-cols-1 divide-y divide-white/5 border border-white/5">
                <?php while($cetak = mysqli_fetch_assoc($query_cetak)): ?>
                    <div class="bg-surface-container-low p-8 flex justify-between items-center group hover:bg-surface-container transition-all">
                        <div class="flex items-center gap-6">
                            <span class="material-symbols-outlined text-primary/40 text-4xl group-hover:text-primary transition-colors">
                                <?php 
                                    // Logika sederhana penentuan icon berdasarkan nama
                                    if (stripos($cetak['nama_layanan'], 'Foto') !== false) echo 'account_box';
                                    elseif (stripos($cetak['nama_layanan'], 'Undangan') !== false) echo 'mail';
                                    else echo 'card_membership';
                                ?>
                            </span>
                            <div>
                                <h4 class="text-lg font-headline text-on-surface"><?= $cetak['nama_layanan'] ?></h4>
                                <p class="text-[10px] font-label uppercase tracking-widest text-on-surface-variant/40">
                                    <?= $cetak['deskripsi'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-body text-on-surface">Rp <?= number_format($cetak['harga'], 0, ',', '.') ?></p>
                            <p class="text-[9px] font-label text-primary uppercase tracking-tighter">
                                <?= isset($cetak['info_tambahan']) ? $cetak['info_tambahan'] : 'Kualitas Premium' ?>
                            </p>
                        </div>
                    </div>
        <?php endwhile; ?>
    </div>
</div>
            </div>
        </section>
    </main>

    <footer class="bg-black py-20 px-8 md:px-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16">
            <div class="space-y-6">
                <span class="text-xl font-headline text-primary font-bold">OPPASTUDIO</span>
                <p class="font-label text-[10px] tracking-widest uppercase text-on-surface-variant/40 leading-loose">
                    Meningkatkan estetika melalui lensa sinematik karya dan keahlian premium.
                </p>
            </div>
            
            <div class="flex gap-16">
                <div class="space-y-4">
                    <h5 class="font-label text-[10px] tracking-widest uppercase text-primary">Connect</h5>
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">Instagram</a>
                    <a href="#" class="block font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60 hover:text-primary transition-colors">LinkedIn</a>
                </div>
                <div class="space-y-4">
                    <h5 class="font-label text-[10px] tracking-widest uppercase text-primary">Office</h5>
                    <p class="font-label text-[10px] tracking-widest uppercase text-on-surface-variant/60">124 Art District,<br>Ende Regency</p>
                </div>
            </div>

            <div class="md:text-right space-y-4">
                <p class="font-label text-[9px] tracking-[0.3em] uppercase text-on-surface-variant/30">© 2024 OPPASTUDIO. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = menuBtn.querySelector('.material-symbols-outlined');

        menuBtn.addEventListener('click', () => {
            // Toggle visibility menu
            mobileMenu.classList.toggle('hidden');
            
            // Ganti icon dari menu ke close (X)
            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.textContent = 'menu';
            } else {
                menuIcon.textContent = 'close';
            }
        });

        // Menutup menu jika klik di luar navbar (opsional)
        window.addEventListener('click', (e) => {
            if (!document.querySelector('nav').contains(e.target)) {
                mobileMenu.classList.add('hidden');
                menuIcon.textContent = 'menu';
            }
        });
    </script>

</body>
</html>