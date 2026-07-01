<?php
include 'config/koneksi.php';
session_start();

// Ambil data galeri dari database
$query = "SELECT * FROM galeri ORDER BY id_galeri DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gallery | OPPASTUDIO</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0d0d0d; color: #e5e2e1; font-family: 'Inter', sans-serif; }
        .gallery-item.hidden-item { display: none !important; }
        
        /* Efek khusus garis tipis lintas-gambar (Editorial Cross-Lines) */
        .editorial-line-v { position: absolute; top: 0; bottom: 0; width: 1px; background-color: rgba(255, 255, 255, 0.4); z-index: 10; pointer-events: none; }
        .editorial-line-h { position: absolute; left: 0; right: 0; height: 1px; background-color: rgba(255, 255, 255, 0.4); z-index: 10; pointer-events: none; }
    </style>
</head>
<body class="min-h-screen">

    <?php include 'includes/navbar.php'; ?>

    <header class="max-w-7xl mx-auto px-8 pt-40 pb-12 text-left relative">
        <div class="border-l border-[#e9c176]/30 pl-6">
            <p class="text-[#e9c176] text-[10px] uppercase tracking-[0.4em] mb-3">Customer Profile & Visual Identity</p>
            <h2 class="font-headline text-5xl md:text-6xl font-light tracking-tight mb-2">Selected Works</h2>
            <p class="text-xs text-gray-500 font-mono">OPPASTUDIO Fine Art Division</p>
        </div>
        
        <div class="flex flex-wrap gap-3 mt-12 border-b border-white/5 pb-8">
            <button onclick="filterSelection('all')" class="filter-btn px-5 py-1.5 border border-[#e9c176] text-[#e9c176] text-[10px] uppercase tracking-widest hover:bg-[#e9c176] hover:text-black transition-all">All Work</button>
            <button onclick="filterSelection('fashion')" class="filter-btn px-5 py-1.5 border border-white/5 text-gray-500 text-[10px] uppercase tracking-widest hover:border-[#e9c176] hover:text-[#e9c176] transition-all">Fashion</button>
            <button onclick="filterSelection('portrait')" class="filter-btn px-5 py-1.5 border border-white/5 text-gray-500 text-[10px] uppercase tracking-widest hover:border-[#e9c176] hover:text-[#e9c176] transition-all">Portrait</button>
            <button onclick="filterSelection('wedding')" class="filter-btn px-5 py-1.5 border border-white/5 text-gray-500 text-[10px] uppercase tracking-widest hover:border-[#e9c176] hover:text-[#e9c176] transition-all">Wedding</button>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-8 pb-32">
        <div class="relative p-4 bg-black/40 min-h-[600px]">
            
            <div class="editorial-line-v hidden md:block" style="left: 35%;"></div>
            <div class="editorial-line-v hidden md:block" style="left: 68%;"></div>
            <div class="editorial-line-h hidden md:block" style="top: 28%;"></div>
            <div class="editorial-line-h hidden md:block" style="top: 65%;"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-8 md:gap-4 auto-rows-[160px]" id="gallery-grid">
                
                <?php 
                $index = 0;
                while($row = mysqli_fetch_assoc($result)): 
                    // Pengaturan pola distribusi asimetris berbasis pembagian sisa indeks (pattern moodboard)
                    $grid_class = "";
                    $text_align = "left";
                    
                    if ($index % 6 == 0) {
                        $grid_class = "md:col-span-4 md:row-span-2"; // Potret Kiri Atas
                    } elseif ($index % 6 == 1) {
                        $grid_class = "md:col-span-5 md:row-span-3 md:mt-12"; // Gambar Tengah Besar Menjorok
                        $text_align = "right";
                    } elseif ($index % 6 == 2) {
                        $grid_class = "md:col-span-3 md:row-span-2"; // Sisi Kanan Atas Kotak Sekunder
                    } elseif ($index % 6 == 3) {
                        $grid_class = "md:col-span-3 md:row-span-2"; // Blok Horizontal Bawah Kiri
                    } elseif ($index % 6 == 4) {
                        $grid_class = "md:col-span-4 md:row-span-3"; // Tiang Tengah Bawah
                    } else {
                        $grid_class = "md:col-span-5 md:row-span-2"; // Penutup Seimbang Kanan Bawah
                    }
                ?>
                
                <div class="gallery-item <?= strtolower($row['kategori']) ?> <?= $grid_class ?> relative group overflow-hidden bg-zinc-900 flex flex-col justify-between p-2 transition-all duration-500">
                    
                    <div class="mb-2 px-1 flex justify-between items-center text-[10px] uppercase tracking-widest text-zinc-500 font-mono group-hover:text-[#e9c176] transition-colors">
                        <span>[ 0<?= $index + 1 ?> ]</span>
                        <span><?= $row['kategori'] ?></span>
                    </div>

                    <div class="w-full h-full overflow-hidden relative bg-black/50 flex-1">
                        <img src="img/gallery/<?= $row['foto'] ?>" alt="<?= $row['kategori'] ?>" class="w-full h-full object-cover grayscale opacity-70 group-hover:opacity-100 group-hover:grayscale-0 group-hover:scale-102 transition-all duration-1000">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-20 transition-opacity"></div>
                    </div>

                    <div class="mt-2 px-1 text-<?= $text_align ?>">
                        <h3 class="font-headline text-xs uppercase tracking-widest text-zinc-400 group-hover:text-white transition-colors">View Exhibition</h3>
                    </div>
                </div>
                
                <?php 
                    $index++;
                endwhile; 
                ?>

            </div>
        </div>
    </section>

    <script>
        function filterSelection(c) {
            var x, i;
            x = document.getElementsByClassName("gallery-item");
            if (c == "all") c = "";
            for (i = 0; i < x.length; i++) {
                x[i].classList.add("hidden-item");
                if (x[i].className.indexOf(c) > -1) {
                    x[i].classList.remove("hidden-item");
                }
            }
            
            // UI Update untuk tombol filter aktif
            let btns = document.getElementsByClassName("filter-btn");
            for (let btn of btns) {
                btn.classList.remove("border-[#e9c176]", "text-[#e9c176]");
                btn.classList.add("border-white/5", "text-gray-500");
            }
            event.currentTarget.classList.add("border-[#e9c176]", "text-[#e9c176]");
        }
    </script>
</body>
</html>