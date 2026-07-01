<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}   
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="w-72 bg-[#0e0e0e] border-r border-white/5 flex flex-col h-screen sticky top-0 shrink-0">
    <div class="px-8 py-10 flex flex-col items-center">
        <div class="w-16 h-16 mb-4 rounded-full overflow-hidden border border-[#e9c176]/30 p-1">
            <img src="../asset/logo.jpeg" alt="Logo" class="w-full h-full object-cover rounded-full" onerror="this.src='logo.jpeg'">
        </div>
        <h1 class="font-headline text-xl font-bold tracking-widest text-[#e9c176]">OPPASTUDIO</h1>
        <p class="text-[9px] tracking-[0.3em] text-gray-500 uppercase mt-1">Photographer Access</p>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <a href="../fotografer/dashboard.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'dashboard.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-label text-sm uppercase tracking-widest">Overview</span>
        </a>

        <a href="../fotografer/jadwal_pemotretan.php" 
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all <?= ($current_page == 'jadwal_pemotretan.php') ? 'bg-[#e9c176]/10 text-[#e9c176]' : 'text-gray-400 hover:bg-[#1c1b1b] hover:text-white' ?>">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="font-label text-sm uppercase tracking-widest">Jadwal Saya</span>
        </a>
    </nav>

    <div class="p-8 border-t border-white/5 bg-[#0a0a0a]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#1c1b1b] rounded-full overflow-hidden border border-white/10 flex items-center justify-center">
                <span class="text-[#e9c176] font-bold">
                    <?= isset($_SESSION['nama']) ? strtoupper(substr($_SESSION['nama'], 0, 1)) : 'P' ?>
                </span>
            </div>
            <div>
                <p class="font-label text-xs font-bold text-gray-200"><?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Fotografer' ?></p>
                <a href="../auth/logout.php" onclick="return confirm('Apakah Anda ingin keluar?')" class="font-label text-[10px] text-red-400 hover:underline uppercase tracking-widest block mt-0.5">Sign Out</a>
            </div>
        </div>
    </div>
</aside>