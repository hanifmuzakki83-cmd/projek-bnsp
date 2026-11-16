<?php
require 'config.php';

// Jika sudah ada session login, langsung dialihkan ke dashboard
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if (isset($_POST['login_btn'])) {
    // Ambil data dan bersihkan dari karakter berbahaya (SQL Injection)
    $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $password = trim($_POST['password']);

    // 1. Validasi Input Kosong
    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        // 2. Cari username di database
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            
            // 3. Verifikasi Password dengan password_verify()
            if (password_verify($password, $row['password'])) {
                
                // Jika cocok, buat session
                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nama'] = $row['nama_lengkap'];
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Username tidak terdaftar!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMK KBM 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Ferrari Style Login */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Rajdhani', sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated background with racing stripes */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(45deg, transparent 40%, rgba(220, 0, 0, 0.02) 40%, rgba(220, 0, 0, 0.02) 60%, transparent 60%),
                linear-gradient(-45deg, transparent 40%, rgba(220, 0, 0, 0.02) 40%, rgba(220, 0, 0, 0.02) 60%, transparent 60%);
            background-size: 60px 60px;
            z-index: 0;
        }
        
        /* Racing stripes overlay */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 100%;
            background: 
                linear-gradient(90deg, transparent 48%, rgba(220, 0, 0, 0.03) 48%, rgba(220, 0, 0, 0.03) 52%, transparent 52%),
                linear-gradient(90deg, transparent 23%, rgba(220, 0, 0, 0.02) 23%, rgba(220, 0, 0, 0.02) 27%, transparent 27%),
                linear-gradient(90deg, transparent 73%, rgba(220, 0, 0, 0.02) 73%, rgba(220, 0, 0, 0.02) 77%, transparent 77%);
            z-index: 0;
            pointer-events: none;
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }
        
        /* Ferrari Card */
        .ferrari-card {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
            border: 1px solid rgba(220, 0, 0, 0.2);
            border-radius: 2rem;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(220, 0, 0, 0.1);
        }
        
        /* Glowing border animation */
        .ferrari-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, transparent 40%, #dc0000, transparent 60%);
            border-radius: 2rem;
            z-index: -1;
            animation: borderGlow 4s ease-in-out infinite;
            background-size: 300% 300%;
        }
        
        @keyframes borderGlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Top racing stripe decoration */
        .ferrari-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #dc0000, transparent);
            animation: pulseLine 3s ease-in-out infinite;
        }
        
        @keyframes pulseLine {
            0%, 100% { opacity: 0.3; transform: translateX(-50%) scaleX(0.8); }
            50% { opacity: 1; transform: translateX(-50%) scaleX(1); }
        }
        
        /* Logo container */
        .logo-container {
            position: relative;
            display: inline-block;
        }
        
        .logo-container::before {
            content: '';
            position: absolute;
            inset: -15px;
            background: radial-gradient(circle, rgba(220, 0, 0, 0.15), transparent 70%);
            border-radius: 50%;
            animation: logoPulse 2s ease-in-out infinite;
        }
        
        @keyframes logoPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
        }
        
        /* Ferrari input fields */
        .ferrari-input {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(220, 0, 0, 0.2);
            color: #ffffff;
            font-family: 'Rajdhani', sans-serif;
            font-size: 16px;
            font-weight: 500;
            padding: 14px 18px;
            border-radius: 12px;
            transition: all 0.3s ease;
            width: 100%;
            outline: none;
        }
        
        .ferrari-input:focus {
            border-color: #dc0000;
            box-shadow: 0 0 30px rgba(220, 0, 0, 0.1), inset 0 0 30px rgba(220, 0, 0, 0.05);
            background: rgba(0, 0, 0, 0.7);
        }
        
        .ferrari-input::placeholder {
            color: #555;
            font-weight: 300;
            letter-spacing: 0.05em;
        }
        
        .ferrari-input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #1a1a1a inset !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        
        /* Ferrari button */
        .ferrari-btn {
            background: linear-gradient(135deg, #dc0000, #b00000);
            border: none;
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            padding: 16px;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            font-size: 14px;
        }
        
        .ferrari-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 40px rgba(220, 0, 0, 0.4);
        }
        
        .ferrari-btn:active {
            transform: scale(0.98);
        }
        
        .ferrari-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .ferrari-btn:hover::after {
            opacity: 1;
        }
        
        /* Error message */
        .error-message {
            background: rgba(220, 0, 0, 0.1);
            border-left: 3px solid #dc0000;
            color: #ff6b6b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        /* Label styling */
        .ferrari-label {
            font-family: 'Orbitron', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            display: block;
            margin-bottom: 8px;
        }
        
        /* Input wrapper with icon */
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
            font-size: 16px;
            transition: color 0.3s ease;
            pointer-events: none;
        }
        
        .input-wrapper .ferrari-input {
            padding-left: 48px;
        }
        
        .input-wrapper .ferrari-input:focus ~ .input-icon,
        .input-wrapper .ferrari-input:focus + .input-icon {
            color: #dc0000;
        }
        
        /* Footer text */
        .footer-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 9px;
            color: #333;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            text-align: center;
            margin-top: 20px;
        }
        
        .footer-text .ferrari-red {
            color: #dc0000;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .ferrari-card {
                padding: 32px 24px;
                border-radius: 1.5rem;
            }
            
            .login-container {
                max-width: 100%;
            }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            background: #0a0a0a;
        }
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        ::-webkit-scrollbar-thumb {
            background: #dc0000;
            border-radius: 2px;
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <div class="ferrari-card">
            
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="logo-container mb-4">
                    <div class="bg-white p-2 rounded-xl shadow-2xl inline-block border-2 border-red-500/30 relative z-10">
                        <img src="Smkn_kbm2.png" alt="SMK KBM 2" class="w-20 h-20 object-contain">
                    </div>
                </div>
                <h1 class="font-['Orbitron'] text-2xl font-bold text-white tracking-wider">
                    SMK <span class="ferrari-red">KBM</span> 2
                </h1>
                <p class="text-xs text-gray-500 font-['Orbitron'] tracking-[0.3em] mt-2">
                    ADMINISTRATOR PANEL
                </p>
            </div>
            
            <!-- Error Message -->
            <?php if(!empty($error)): ?>
                <div class="error-message mb-6">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form action="" method="POST" class="space-y-5">
                <!-- Username Field -->
                <div>
                    <label class="ferrari-label">
                        <i class="fas fa-user mr-2 text-red-500/50"></i>
                        Username
                    </label>
                    <div class="input-wrapper">
                        <input type="text" 
                               name="username" 
                               required 
                               autocomplete="username"
                               placeholder="Masukkan username Anda"
                               class="ferrari-input">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div>
                    <label class="ferrari-label">
                        <i class="fas fa-lock mr-2 text-red-500/50"></i>
                        Password
                    </label>
                    <div class="input-wrapper">
                        <input type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="Masukkan password Anda"
                               class="ferrari-input">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" name="login_btn" class="ferrari-btn mt-2">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk ke Panel
                </button>
            </form>
            
            <!-- Additional Info -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-600 font-['Rajdhani'] font-medium tracking-wide">
                    <i class="fas fa-shield-alt text-red-500/30 mr-1"></i>
                    Sistem keamanan terenkripsi
                    <i class="fas fa-shield-alt text-red-500/30 ml-1"></i>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer-text">
            <span class="block text-[8px] text-gray-700 mt-1 tracking-[0.2em]">
                © 2026 SMAN KBM 2 · ALL RIGHTS RESERVED
            </span>
        </div>
    </div>
    
</body>
</html>