<?php
// Mendapatkan nama file untuk fitur active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-72 bg-[#0e0e0e] border-r border-white/5 flex flex-col h-screen sticky top-0 shrink-0">
    <div class="px-8 py-10">
        <h1 class="font-headline text-2xl font-bold tracking-widest text-[#e9c176]">OPPASTUDIO</h1>
        <p class="font-label text-[10px] tracking-[0.2em] text-gray-500 mt-1 uppercase">Management Suite</p>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <a href="admin_dashboard.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'admin_dashboard.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-label text-sm uppercase tracking-widest">Dashboard</span>
        </a>

        <a href="manage_gallery.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'manage_gallery.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">photo_library</span>
            <span class="font-label text-sm uppercase tracking-widest">Gallery</span>
        </a>

        <a href="manage_user.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'manage_user.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">group</span>
            <span class="font-label text-sm uppercase tracking-widest">Users</span>
        </a>
        <a href="manage_booking.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'manage_booking.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">box</span>
            <span class="font-label text-sm uppercase tracking-widest">booking</span>
        </a>

        
    </nav>
    <div class="p-8 border-t border-outline-variant/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-surface-container-high rounded-full overflow-hidden border border-outline-variant/20 flex items-center justify-center">
                    <span class="text-primary font-bold"><?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?></span>
                </div>
                <div>
                    <p class="font-label text-xs font-bold text-on-surface"><?= $_SESSION['nama'] ?></p>
                    <a href="logout.php" class="font-label text-[10px] text-error hover:underline uppercase tracking-widest">Sign Out</a>
                </div>
            </div>
        </div>
</aside>
