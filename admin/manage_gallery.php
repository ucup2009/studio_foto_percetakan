<?php
include '../config/koneksi.php';
session_start();

// Proteksi Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Logika Hapus Foto
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Ambil nama file dari kolom 'foto'
    $get_file = mysqli_query($conn, "SELECT foto FROM galeri WHERE id_galeri = $id");
    $data_file = mysqli_fetch_assoc($get_file);
    $nama_file = $data_file['foto'];
    
    // Hapus file fisik jika ada di dalam folder
    if (!empty($nama_file) && file_exists("img/gallery/" . $nama_file)) {
        unlink("img/gallery/" . $nama_file);
    }

    mysqli_query($conn, "DELETE FROM galeri WHERE id_galeri = $id");
    header("Location: manage_gallery.php?pesan=deleted");
    exit;
}

// Ambil data dari database
$query_galeri = "SELECT * FROM galeri ORDER BY id_galeri DESC";
$tampil_galeri = mysqli_query($conn, $query_galeri);
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manage Gallery | OPPASTUDIO</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Inter:wght@300;400;600&family=Manrope:wght@500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#e9c176",
                        "on-primary": "#412d00",
                        "background": "#131313",
                        "surface-container-low": "#1c1b1b",
                        "on-surface": "#e5e2e1",
                        "on-surface-variant": "#d1c5b4",
                        "outline-variant": "#4e4639",
                    },
                    fontFamily: { "headline": ["Noto Serif"], "body": ["Inter"], "label": ["Manrope"] }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex bg-background text-on-surface overflow-hidden">

    <?php include '../includes/sidebar_admin.php'; ?>

    <main class="flex-1 h-screen overflow-y-auto">
        <div class="max-w-7xl mx-auto p-12">
            <header class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="font-headline text-4xl font-bold text-on-surface mb-2">Gallery Assets</h2>
                    <p class="text-on-surface-variant text-sm">Organize your studio portfolio.</p>
                </div>
                <button onclick="document.getElementById('uploadModal').classList.toggle('hidden')" class="bg-primary text-on-primary font-label text-xs font-bold tracking-widest uppercase px-8 py-4 rounded-sm hover:brightness-110 transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm">add_a_photo</span> Add New Media
                </button>
            </header>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php while($row = mysqli_fetch_assoc($tampil_galeri)): ?>
                <div class="group relative bg-surface-container-low border border-outline-variant/10 rounded-sm overflow-hidden aspect-square">
                    
                    <?php 
                        // 1. Coba jalur langsung jika file ini berada di root folder yang sama dengan 'img'
                        $path_gambar = "img/gallery/" . $row['foto'];

                        // 2. Jika tidak ditemukan, coba jalur mundur satu tingkat (jika file ini di dalam folder seperti 'admin/')
                        if (!file_exists($path_gambar)) {
                            $path_gambar = "../img/gallery/" . $row['foto'];
                        }

                        // 3. Jika nama file kosong atau benar-benar tidak ada di kedua folder tersebut, berikan gambar placeholder
                        if (empty($row['foto']) || (!file_exists($path_gambar) && !file_exists("img/gallery/" . $row['foto']))) {
                            $path_gambar = "https://placehold.co/600x600/1c1b1b/e9c176?text=Cek+Database";
                        }
                    ?>

                        <img src="<?= $path_gambar ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" alt="Gallery Image" />
                    
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-4">
                        <div class="flex justify-end">
                            <a href="?hapus=<?= $row['id_galeri'] ?>" onclick="return confirm('Hapus foto ini?')" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </a>
                        </div>
                        <div>
                            <p class="text-[10px] text-primary uppercase font-bold tracking-widest"><?= htmlspecialchars($row['kategori']) ?></p>
                            <p class="text-xs font-semibold truncate text-white/80"><?= htmlspecialchars($row['foto']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <div id="uploadModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-6">
        <div class="bg-surface-container-low max-w-lg w-full p-10 border border-outline-variant/20 relative">
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="absolute top-4 right-4 text-on-surface-variant hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
            <h3 class="font-headline text-2xl mb-6 text-on-surface">Upload Asset</h3>
            <form action="../actions/upload_proses.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Category</label>
                    <select name="kategori" class="w-full bg-background border-none focus:ring-1 focus:ring-primary p-4 text-sm mt-2 text-white outline-none">
                        <option value="fashion">Fashion</option>
                        <option value="portrait">Portrait</option>
                        <option value="wedding">Wedding</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">File</label>
                    <input type="file" name="gambar" id="inputGambar" accept="image/*" class="w-full bg-background border-none p-4 text-sm mt-2 text-white" required>
                </div>
                <div id="previewContainer" class="hidden">
                    <img id="imgPreview" src="#" class="w-full h-48 object-cover border border-outline-variant/20 rounded-sm grayscale">
                </div>
                <button type="submit" name="submit" class="w-full bg-primary text-on-primary py-4 font-bold text-xs tracking-widest uppercase hover:brightness-110 transition-all">Process Upload</button>
            </form>
        </div>
    </div>

    <script>
        const inputGambar = document.getElementById('inputGambar');
        const previewContainer = document.getElementById('previewContainer');
        const imgPreview = document.getElementById('imgPreview');
        inputGambar.onchange = evt => {
            const [file] = inputGambar.files;
            if (file) {
                imgPreview.src = URL.createObjectURL(file);
                previewContainer.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>