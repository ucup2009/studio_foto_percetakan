<?php
// Pastikan session_start() ada di file utama (index, booking, dll) atau tambahkan di sini
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$is_logged_in = isset($_SESSION['login']) && $_SESSION['login'] === true;
?>

<nav class="fixed top-0 w-full z-50 bg-[#131313]/80 backdrop-blur-xl border-b border-white/5">
    <div class="flex justify-between items-center px-8 md:px-12 py-6">
        <a href="index.php" class="text-2xl font-bold tracking-tighter text-primary font-headline uppercase">
            OPPASTUDIO
        </a>
        
        <div class="hidden md:flex gap-10 items-center">
            <?php
            $menus = [
                'index.php' => 'Home',
                'gallery.php' => 'Gallery',
                'services.php' => 'Services',
                'booking.php' => 'Booking'
            ];

            foreach ($menus as $file => $label):
                $activeClass = ($current_page == $file) ? 'text-primary border-b border-primary/30 pb-1' : 'text-on-surface-variant hover:text-primary';
            ?>
                <a href="<?= $file ?>" class="font-headline text-sm tracking-tight transition-colors duration-300 <?= $activeClass ?>">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>

            <?php if ($is_logged_in): ?>
                <div class="flex items-center gap-4 ml-4">
                    <span class="text-on-surface-variant font-label text-[10px] tracking-widest uppercase">
                        Hi, <?= explode(' ', $_SESSION['nama'])[0] ?>
                    </span>
                    <a href="auth/logout.php" class="border border-primary/30 text-primary px-4 py-2 rounded-sm font-label text-[10px] tracking-[0.2em] uppercase hover:bg-primary hover:text-on-primary transition-all">
                        Logout
                    </a>
                </div>
            <?php else: ?>
                <a href="auth/login.php" class="bg-primary text-on-primary px-6 py-2 rounded-sm font-label text-[10px] tracking-[0.2em] uppercase hover:brightness-110 transition-all ml-4">
                    Login
                </a>
            <?php endif; ?>
        </div>

        <button id="menu-btn" class="md:hidden text-primary focus:outline-none">
            <span class="material-symbols-outlined text-3xl">menu</span>
        </button>
    </div>

    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-[#131313]/95 backdrop-blur-2xl border-b border-white/5 py-8 px-8 transition-all duration-300 ease-in-out">
        <div class="flex flex-col gap-6 text-center">
            <?php foreach ($menus as $file => $label): 
                $activeClassMobile = ($current_page == $file) ? 'text-primary font-bold italic' : 'text-on-surface-variant';
            ?>
                <a href="<?= $file ?>" class="font-headline text-xl <?= $activeClassMobile ?>"><?= $label ?></a>
            <?php endforeach; ?>
            
            <hr class="border-white/10 my-2">

            <?php if ($is_logged_in): ?>
                <p class="text-primary font-label text-xs tracking-widest uppercase italic">Logged in as <?= $_SESSION['nama'] ?></p>
                <a href="logout.php" class="text-on-surface-variant font-label text-[12px] tracking-[0.2em] uppercase">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-primary text-on-primary px-8 py-4 rounded-sm font-label text-[12px] tracking-[0.2em] uppercase inline-block">
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = menuBtn.querySelector('.material-symbols-outlined');

    if(menuBtn) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            menuIcon.textContent = mobileMenu.classList.contains('hidden') ? 'menu' : 'close';
        });
    }

    window.addEventListener('click', (e) => {
        if (mobileMenu && !document.querySelector('nav').contains(e.target)) {
            mobileMenu.classList.add('hidden');
            menuIcon.textContent = 'menu';
        }
    });
</script>