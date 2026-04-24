<?php
include 'koneksi.php';
session_start();

$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if ($password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role']; 

            // Redirection berdasarkan role
            if ($row['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($row['role'] === 'fotografer') {
                // Sesuai use case: melihat jadwal & update status
                header("Location: fotografer_dashboard.php"); 
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | OPPASTUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #131313; font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Noto Serif', serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md bg-[#1c1b1b] p-10 border border-white/5 shadow-2xl rounded-sm">
        
        <div class="flex flex-col items-center mb-10">
            <div class="w-20 h-20 mb-4 rounded-full overflow-hidden border-2 border-[#e9c176]/30 p-1">
                <img src="asset/logo.jpeg" alt="Logo OPPASTUDIO" class="w-full h-full object-cover rounded-full">
            </div>
            <h2 class="font-headline text-3xl text-[#e9c176] tracking-widest uppercase">OPPASTUDIO</h2>
            <p class="text-[10px] tracking-[0.4em] text-gray-500 uppercase mt-2">Visual Legacy</p>
        </div>
        
        <?php if(isset($error) && $error): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-200 text-xs p-4 mb-6 italic text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="text-[10px] tracking-widest text-gray-400 uppercase font-bold">Email</label>
                <input type="email" name="email" required placeholder="your@email.com" 
                       class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-[#e9c176] p-4 text-white text-sm mt-2 outline-none transition-all">
            </div>
            <div>
                <label class="text-[10px] tracking-widest text-gray-400 uppercase font-bold">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="w-full bg-[#0e0e0e] border-none focus:ring-1 focus:ring-[#e9c176] p-4 text-white text-sm mt-2 outline-none transition-all">
            </div>
            
            <div class="pt-2">
                <button type="submit" name="login" class="w-full bg-[#e9c176] text-[#412d00] py-4 font-bold text-xs tracking-widest uppercase hover:brightness-110 transition-all shadow-lg shadow-[#e9c176]/10">
                    Sign In
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-white/5 text-center">
            <p class="text-xs text-gray-500">
                Don't have an account? 
                <a href="register.php" class="text-[#e9c176] font-semibold hover:underline ml-1">Register Now</a>
            </p>
        </div>
    </div>
</body>
</html>