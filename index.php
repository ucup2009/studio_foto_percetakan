<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>OPPASTUDIO | Capturing Your Timeless Moments</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500&family=Manrope:wght@500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link rel="icon" href="asset/logo.jpeg">
    
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #131313;
            color: #d1c5b4;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        /* Reveal animation on scroll placeholder */
        .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #353534; border-radius: 10px; }
    </style>
</head>

<body class="bg-background selection:bg-primary/30 selection:text-primary overflow-x-hidden">

   <?php include 'includes/navbar.php'; ?>

    <main>
        <section class="relative h-screen flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="asset/b.jpg" 
                     alt="Cinematic wedding photography" 
                     class="w-full h-full object-cover grayscale-[0.3] brightness-[0.4]" />
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-background/20 to-background"></div>
            </div>
            
            <div class="relative z-10 text-center max-w-5xl px-6">
                <span class="font-label text-primary tracking-[0.5em] uppercase text-[10px] mb-6 block">Reo.Manggarai Premiere Studio</span>
                <h1 class="font-headline text-5xl md:text-8xl text-on-surface font-bold tracking-tighter leading-[0.95] mb-12">
                    Oppastudio: Mengabadikan <br/> <span class="italic font-normal text-primary/90">Momen-Momen Abadi Anda</span>
                </h1>
                <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                    <button class="bg-primary text-on-primary px-10 py-4 rounded-sm font-label text-xs tracking-[0.2em] uppercase transition-all duration-500 hover:scale-105 shadow-xl">
                        Book a Session
                    </button>
                    <button class="border border-outline-variant/30 text-on-surface px-10 py-4 rounded-sm font-label text-xs tracking-[0.2em] uppercase hover:bg-white/5 transition-all duration-500">
                        View Portfolio
                    </button>
                </div>
            </div>
        </section>

        <section class="py-32 px-8 md:px-12 bg-surface">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-16 items-center">
                <div class="md:col-span-5 space-y-8">
                    <div>
                        <span class="font-label text-primary tracking-[0.3em] uppercase text-[10px] mb-4 block">Our Story</span>
                        <h2 class="font-headline text-4xl md:text-5xl text-on-surface leading-tight">Visi : <br/>Keunggulan Lokal di Manggarai</h2>
                    </div>
                    <p class="text-on-surface-variant font-body text-lg leading-relaxed">
                        Berakar di jantung Reo, Oppastudio didirikan oleh Yusuf dengan misi tunggal: untuk mengangkat penceritaan regional melalui citra sinematik. Kami tidak hanya mengambil foto; kami mendokumentasikan jiwa komunitas kami.
                    </p>
                    <a href="#" class="inline-flex items-center gap-4 group cursor-pointer">
                        <div class="h-[1px] w-12 bg-primary transition-all group-hover:w-20"></div>
                        <span class="font-label text-primary text-[10px] uppercase tracking-[0.2em]">Learn More about Yusuf</span>
                    </a>
                </div>
                
                <div class="md:col-span-7 relative h-[500px] md:h-[650px]">
                    <img src="asset/logo.jpeg" 
                         alt="Photography studio interior" 
                         class="w-full h-full object-cover grayscale opacity-80 border border-white/5" />
                    <div class="absolute -bottom-8 -left-8 bg-surface-container-lowest p-10 max-w-xs hidden lg:block border-l-4 border-primary shadow-2xl">
                        <p class="font-headline italic text-on-surface text-xl leading-relaxed">"Setiap bingkai adalah warisan yang menunggu untuk diceritakan."</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-32 px-8 md:px-12 bg-[#0d0d0d] overflow-hidden min-h-screen flex flex-col justify-center relative">
    
            <div class="max-w-7xl mx-auto mb-12 text-center relative z-20">
                <span class="font-label text-[#e9c176] tracking-[0.3em] uppercase text-[10px] mb-4 block">Karya yang Dikurasi</span>
                <h2 class="font-headline text-4xl md:text-5xl text-white font-light">Galeri Unggulan</h2>
                <p class="text-[11px] text-zinc-500 uppercase tracking-widest mt-2 font-mono">Klik Tombol Kontrol untuk Memutar Koleksi</p>
            </div>

            <div class="relative w-full max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-center gap-12 md:gap-20">
                
                <div class="w-full md:w-1/3 text-center md:text-left z-20 flex flex-col items-center md:items-start justify-center">
                    <div class="w-24 h-[1px] bg-zinc-700 mb-6"></div>
                    <h3 id="class=text-xs text-[#e9c176] tracking-[0.2em] uppercase mt-3 font-mono transition-all duration-500">
                        Weddings
                    </h3>
                    <p id=" active-title" class="font-headline text-5xl md:text-6xl text-white tracking-wide font-light uppercase transition-all duration-500">
                        Eternal Unions
                       
                    </p>
                    <div class="w-24 h-[1px] bg-zinc-700 mt-6"></div>

                    <div class="flex gap-3 mt-10">
                        <button onclick="rotateGallery(-45)" class="w-10 h-10 rounded-full border border-white/10 hover:border-[#e9c176] flex items-center justify-center text-white text-sm hover:text-[#e9c176] transition-all bg-black/40">
                            ←
                        </button>
                        <button onclick="rotateGallery(45)" class="w-10 h-10 rounded-full border border-white/10 hover:border-[#e9c176] flex items-center justify-center text-white text-sm hover:text-[#e9c176] transition-all bg-black/40">
                            →
                        </button>
                    </div>
                </div>

                <div class="relative w-[340px] h-[340px] sm:w-[500px] sm:h-[500px] md:w-[650px] md:h-[650px] flex-shrink-0">
                    
                    <div class="absolute -left-[20%] top-1/2 -translate-y-1/2 w-[40%] h-[40%] bg-[#0d0d0d] rounded-full z-10 hidden md:block"></div>
                    
                    <div id="gallery-wheel" class="absolute inset-0 w-full h-full rounded-full border-4 border-zinc-950 bg-zinc-950 overflow-hidden transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)]" style="transform: rotate(0deg);">
                        
                        <div onclick="setActiveInfo('Weddings', 'Eternal Unions', 0)" class="absolute inset-0 origin-center cursor-pointer group" style="clip-path: polygon(50% 50%, 100% 0, 100% 50%, 100% 100%);">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGzhgZDQJwTR84EEGMmuJTzVMctmwIZ6Emaq24wp_yfbrU4QH2QjQiqoyeDiQV3kYZN0RzTFU69w-pHzuu-Augak2SDokq9qxopd9T9rmcTBp26VhTjmcQM5r9ibEonMUYKMK8RZmlVaeNJrCvcu1-x22l62qb-wHEv54H29Lq7wzQXra_q7yRnpqvz2bB5fzSQUGp4jGg9vlM0gJLGWWpQPaNCTVwcBT_BO0VBPvtZiBD0DNSfL3RCqKF6PyPKxZE7z6-V1yWOyFs" alt="Wedding" class="absolute w-[150%] h-[150%] max-w-none object-cover grayscale group-hover:grayscale-0 transition-all duration-700" style="left: -25%; top: -25%; transform: rotate(0deg);" />
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-transparent transition-colors"></div>
                        </div>

                        <div onclick="setActiveInfo('Portraits', 'Character Studies', -45)" class="absolute inset-0 origin-center cursor-pointer group" style="clip-path: polygon(50% 50%, 100% 100%, 50% 100%, 0 100%);">
                            <img src="asset/1.jpg" alt="Portrait" class="absolute w-[150%] h-[150%] max-w-none object-cover grayscale group-hover:grayscale-0 transition-all duration-700" style="left: -25%; top: -25%; transform: rotate(-45deg);" />
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition-colors"></div>
                        </div>

                        <div onclick="setActiveInfo('Events', 'Vibrant Life', -135)" class="absolute inset-0 origin-center cursor-pointer group" style="clip-path: polygon(50% 50%, 0 100%, 0 50%, 0 0);">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD2nDkjjNj7bId-3U9fgE3E00vFGy4oATJqgbvvNPgyZuRjyUA03WrCa8YoXIfLxffew1FZkP5vtvuRZESArYwB-70-9_MAX20p5PDch6jZNRJlc8KXUb8cO9T7Vw48ZnaXlyzu5TdaNTnO2jG9qibQv61EJOEkv2xwNZQLr47cm0UvSqK1V2k9JiKb94a97gnit7tmiuUwRfKOPgOWFBFTCbyVJwrGNboiv8J6xEZVJLnjzCxPdwpqZAHK39MPQz7k_V9Xu4sx4uen" alt="Events" class="absolute w-[150%] h-[150%] max-w-none object-cover grayscale group-hover:grayscale-0 transition-all duration-700" style="left: -25%; top: -25%; transform: rotate(-135deg);" />
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition-colors"></div>
                        </div>

                        <div onclick="setActiveInfo('Details', 'The Nuances', -225)" class="absolute inset-0 origin-center cursor-pointer group" style="clip-path: polygon(50% 50%, 0 0, 50% 0, 100% 0);">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDd213UD_olFpwoqDTdMbTaxZ8KBOe0_0ZH5zUXF8kuv-PJCQo44d6DxKpUwR-HT2UIl07ZRoHSnMDz5aobkQepeDgt1HjUu1z_DKdtsWqKzfEfSGLxRor9u-ByWiUEppvu-f4LjdnHNQGF-EznUEANajEpnNqKk6H6Sq0cRS59EK3rQtfLcptY0mpkOKCXIALq8SgWKCuCIh9-Y_srCeE7dej7kaQDQE9sX2BX0zDACSlv52nyjPEXTupr3z-3eVEnXB7nhoc1KEoV" alt="Details" class="absolute w-[150%] h-[150%] max-w-none object-cover grayscale group-hover:grayscale-0 transition-all duration-700" style="left: -25%; top: -25%; transform: rotate(-225deg);" />
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition-colors"></div>
                        </div>

                    </div>
                    
                    <div class="absolute inset-0 rounded-full border border-white/10 pointer-events-none z-10 m-4"></div>
                    <div class="absolute inset-0 rounded-full border border-white/5 pointer-events-none z-10 m-16"></div>
                </div>
                
            </div>
        </section>
        <section class="py-32 px-8 bg-surface">
            <div class="max-w-4xl mx-auto bg-surface-container-lowest border border-white/5 p-12 md:p-24 text-center shadow-2xl">
                <h2 class="font-headline text-4xl md:text-5xl text-on-surface mb-8 leading-tight">Siap untuk menentukan <br>warisan visual Anda? </h2>
                <p class="text-on-surface-variant font-body text-lg mb-12 max-w-2xl mx-auto">
                    Jelajahi paket fotografi khusus kami yang dirancang untuk setiap momen penting. Dari potret intim hingga perayaan besar.
                </p>
                <a href="#" class="inline-block bg-primary text-on-primary px-12 py-5 rounded-sm font-label text-[10px] tracking-[0.3em] uppercase transition-all duration-500 hover:scale-105 hover:bg-primary-container">
                    Explore Packages
                </a>
            </div>
        </section>
    </main>

    <footer class="bg-black py-20 px-8 md:px-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 items-start">
            <div class="space-y-6">
                <div class="text-xl font-headline text-primary font-bold">OPPASTUDIO</div>
                <p class="text-on-surface-variant/50 font-label text-[10px] tracking-widest uppercase leading-loose">
                    © 2024 OPPASTUDIO. <br>All Rights Reserved.
                </p>
            </div>
            
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-4">
                    <a href="#" class="block text-on-surface-variant/60 hover:text-primary transition-colors font-label text-[10px] tracking-widest uppercase">Instagram</a>
                    <a href="#" class="block text-on-surface-variant/60 hover:text-primary transition-colors font-label text-[10px] tracking-widest uppercase">LinkedIn</a>
                </div>
                <div class="space-y-4">
                    <a href="#" class="block text-on-surface-variant/60 hover:text-primary transition-colors font-label text-[10px] tracking-widest uppercase">Contact</a>
                    <a href="#" class="block text-on-surface-variant/60 hover:text-primary transition-colors font-label text-[10px] tracking-widest uppercase">Studio Address</a>
                </div>
            </div>

            <div class="md:text-right">
                <span class="font-label text-primary text-[10px] uppercase tracking-[0.3em] block mb-6">Newsletter</span>
                <div class="flex border-b border-white/20 pb-2">
                    <input type="email" placeholder="YOUR EMAIL" class="bg-transparent border-none focus:ring-0 text-[10px] font-label tracking-widest w-full text-on-surface uppercase" />
                    <button class="text-primary text-[10px] font-label uppercase tracking-widest hover:brightness-125 transition-all">Join</button>
                </div>
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
<script 
    type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    //  URL script copy dari dashboard Tawk.to kamu sendiri
    s1.src='https://embed.tawk.to/65xxxxxx/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<script>
    let currentRotation = 0;

    function rotateGallery(degrees) {
        currentRotation += degrees;
        const wheel = document.getElementById('gallery-wheel');
        wheel.style.transform = `rotate(${currentRotation}deg)`;
        
        // Efek transisi halus teks judul saat roda digeser manual
        document.getElementById('active-title').style.opacity = '0.3';
        setTimeout(() => {
            document.getElementById('active-title').style.opacity = '1';
        }, 300);
    }

    function setActiveInfo(title, subtitle, targetDegrees) {
        // Berfungsi mengubah teks ketika elemen gambar diklik langsung
        document.getElementById('active-title').innerText = title;
        document.getElementById('active-subtitle').innerText = subtitle;
        
        currentRotation = targetDegrees;
        document.getElementById('gallery-wheel').style.transform = `rotate(${targetDegrees}deg)`;
    }
</script>
</body>
</html>