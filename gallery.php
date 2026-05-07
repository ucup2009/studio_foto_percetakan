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
        body { background-color: #131313; color: #e5e2e1; font-family: 'Inter', sans-serif; }
        .gallery-item.hidden { display: none; }
    </style>
</head>
<body class="min-h-screen">

    <?php include 'includes/navbar.php'; ?>

    <header class="max-w-7xl mx-auto px-8 py-20 text-center">
        <p class="text-[#e9c176] text-[10px] uppercase tracking-[0.3em] mb-4">Our Visual Legacy</p>
        <h2 class="font-headline text-5xl md:text-7xl font-bold mb-8">Selected Works</h2>
        
        <div class="flex flex-wrap justify-center gap-4 mt-12">
            <button onclick="filterSelection('all')" class="filter-btn px-6 py-2 border border-[#e9c176] text-[#e9c176] text-[10px] uppercase tracking-widest hover:bg-[#e9c176] hover:text-black transition-all">All Work</button>
            <button onclick="filterSelection('fashion')" class="filter-btn px-6 py-2 border border-white/10 text-gray-400 text-[10px] uppercase tracking-widest hover:border-[#e9c176] hover:text-[#e9c176] transition-all">Fashion</button>
            <button onclick="filterSelection('portrait')" class="filter-btn px-6 py-2 border border-white/10 text-gray-400 text-[10px] uppercase tracking-widest hover:border-[#e9c176] hover:text-[#e9c176] transition-all">Portrait</button>
            <button onclick="filterSelection('wedding')" class="filter-btn px-6 py-2 border border-white/10 text-gray-400 text-[10px] uppercase tracking-widest hover:border-[#e9c176] hover:text-[#e9c176] transition-all">Wedding</button>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-8 pb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gallery-grid">
            
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="gallery-item <?= strtolower($row['kategori']) ?> relative group overflow-hidden bg-[#1c1b1b] aspect-[3/4]">
                <img src="img/gallery/<?= $row['foto'] ?>" alt="<?= $row['kategori'] ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-8 flex flex-col justify-end">
                    <p class="text-[#e9c176] text-[10px] uppercase tracking-widest mb-2"><?= $row['kategori'] ?></p>
                    <h3 class="font-headline text-xl uppercase tracking-tighter">View Project</h3>
                </div>
            </div>
            <?php endwhile; ?>

        </div>
    </section>

    <script>
        function filterSelection(c) {
            var x, i;
            x = document.getElementsByClassName("gallery-item");
            if (c == "all") c = "";
            for (i = 0; i < x.length; i++) {
                x[i].classList.add("hidden");
                if (x[i].className.indexOf(c) > -1) {
                    x[i].classList.remove("hidden");
                }
            }
            
            // UI Update untuk tombol filter
            let btns = document.getElementsByClassName("filter-btn");
            for (let btn of btns) {
                btn.classList.remove("border-[#e9c176]", "text-[#e9c176]");
                btn.classList.add("border-white/10", "text-gray-400");
            }
            event.currentTarget.classList.add("border-[#e9c176]", "text-[#e9c176]");
        }
    </script>
</body>
</html>