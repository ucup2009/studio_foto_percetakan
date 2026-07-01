<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}   
?>

<aside class="w-72 bg-[#0e0e0e] border-r border-white/5 flex flex-col h-screen sticky top-0 shrink-0">
    <div class="px-8 py-10">
        <h1 class="font-headline text-2xl font-bold tracking-widest text-[#e9c176]">OPPASTUDIO</h1>
        <p class="font-label text-[10px] tracking-[0.2em] text-gray-500 mt-1 uppercase">Management Suite</p>
    </div>

    <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
        <a href="dashboard.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'dashboard.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
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
            <span class="material-symbols-outlined">calendar_today</span>
            <span class="font-label text-sm uppercase tracking-widest">Booking</span>
        </a>

        <a href="manage_services.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'manage_services.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">imagesmode</span>
            <span class="font-label text-sm uppercase tracking-widest">Services</span>
        </a>

        <a href="manage_addons.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'manage_addons.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">extension</span>
            <span class="font-label text-sm uppercase tracking-widest">Addons</span>
        </a>

        <a href="manage_orders.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'manage_orders.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">receipt_long</span>
            <span class="font-label text-sm uppercase tracking-widest">Orders</span>
        </a>
    </nav>

    <div class="p-8 border-t border-white/5 bg-[#0a0a0a]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#1c1b1b] rounded-full overflow-hidden border border-white/10 flex items-center justify-center">
                <span class="text-[#e9c176] font-bold">
                    <?= isset($_SESSION['nama']) ? strtoupper(substr($_SESSION['nama'], 0, 1)) : 'A' ?>
                </span>
            </div>
            <div>
                <p class="font-label text-xs font-bold text-gray-200"><?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Admin' ?></p>
                <a href="../auth/logout.php" onclick="return confirm('Apakah Anda ingin keluar?')" class="font-label text-[10px] text-red-400 hover:underline uppercase tracking-widest block mt-0.5">Sign Out</a>
            </div>
        </div>
    </div>
</aside>