<?php
include 'koneksi.php';
session_start();

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // 1. Cek apakah password cocok
    if ($password !== $confirm) {
        $error = "Konfirmasi password tidak sesuai.";
    } else {
        // 2. Cek apakah email sudah terdaftar
        $check_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
        
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email sudah digunakan, silakan gunakan email lain.";
        } else {
            // 3. Masukkan ke database (Role default: pelanggan)
            // Catatan: Disarankan menggunakan password_hash() untuk keamanan produksi
            $query = "INSERT INTO users (nama, email, password, no_hp, role) 
                      VALUES ('$nama', '$email', '$password', '$no_hp', 'pelanggan')";
            
            if (mysqli_query($conn, $query)) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan sistem. Silakan coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register | OPPASTUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #131313; font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Noto Serif', serif; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #e9c176; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10">
    <div class="w-full max-w-md bg-[#1c1b1b] p-10 border border-white/5 shadow-2xl">
        <h2 class="font-headline text-3xl text-[#e9c176] text-center mb-2">OPPASTUDIO</h2>
        <p class="text-[10px] tracking-[0.3em] text-gray-500 uppercase text-center mb-8">Create New Account</p>
        
        <?php if($error): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-200 text-[11px] p-4 mb-6 italic text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-500/10 border border-green-500 text-green-200 text-[11px] p-4 mb-6 italic text-center">
                <?= $success ?>
                <br>
                <a href="login.php" class="underline font-bold">Klik di sini untuk Login</a>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="text-[10px] tracking-widest text-gray-400 uppercase font-bold">Full Name</label>
                <input type="text" name="nama" required placeholder="Your name" 
                       class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-[#e9c176] p-4 text-white text-sm mt-2">
            </div>
            <div>
                <label class="text-[10px] tracking-widest text-gray-400 uppercase font-bold">Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com"
                       class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-[#e9c176] p-4 text-white text-sm mt-2">
            </div>
            <div>
                <label class="text-[10px] tracking-widest text-gray-400 uppercase font-bold">Phone Number</label>
                <input type="number" name="no_hp" required placeholder="08..."
                       class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-[#e9c176] p-4 text-white text-sm mt-2">
            </div>
            <div>
                <label class="text-[10px] tracking-widest text-gray-400 uppercase font-bold">Password</label>
                <input type="password" name="password" required 
                       class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-[#e9c176] p-4 text-white text-sm mt-2">
            </div>
            <div>
                <label class="text-[10px] tracking-widest text-gray-400 uppercase font-bold">Confirm Password</label>
                <input type="password" name="confirm_password" required 
                       class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-[#e9c176] p-4 text-white text-sm mt-2">
            </div>

            <button type="submit" name="register" class="w-full bg-[#e9c176] text-[#412d00] py-4 font-bold text-xs tracking-widest uppercase hover:brightness-110 transition-all mt-4">
                Create Account
            </button>

            <p class="text-center text-[11px] text-gray-500 mt-6">
                Already have an account? 
                <a href="login.php" class="text-[#e9c176] hover:underline">Sign In</a>
            </p>
        </form>
    </div>
</body>
</html>