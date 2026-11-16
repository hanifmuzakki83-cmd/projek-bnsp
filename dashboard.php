<?php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMK KBM 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Ferrari Style Dashboard */
        body {
            font-family: 'Rajdhani', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        
        .ferrari-red {
            color: #dc0000;
        }
        .ferrari-bg {
            background: #dc0000;
        }
        .ferrari-border {
            border-color: #dc0000;
        }
        
        /* Ferrari Cards */
        .ferrari-card {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
            border: 1px solid rgba(220, 0, 0, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .ferrari-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #dc0000, transparent);
            transition: all 0.6s ease;
        }
        
        .ferrari-card:hover::before {
            left: 100%;
        }
        
        .ferrari-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #dc0000;
            box-shadow: 0 30px 60px rgba(220, 0, 0, 0.15), inset 0 0 60px rgba(220, 0, 0, 0.05);
        }
        
        /* Metric Card */
        .metric-card {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
            border: 1px solid rgba(220, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }
        
        .metric-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #dc0000, #ff4444, #dc0000);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { background-position: -200% 0; }
            50% { background-position: 200% 0; }
        }
        
        .metric-card .icon-wrapper {
            transition: all 0.4s ease;
        }
        
        .metric-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(-10deg);
        }
        
        /* Activity Section */
        .ferrari-activity {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
            border: 1px solid rgba(220, 0, 0, 0.15);
            position: relative;
        }
        
        .ferrari-activity::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #dc0000, transparent);
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            background: #0a0a0a;
        }
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        ::-webkit-scrollbar-thumb {
            background: #dc0000;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #b00000;
        }
        
        /* Grid pattern background */
        .grid-pattern {
            background-image: 
                linear-gradient(rgba(220, 0, 0, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220, 0, 0, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        
        /* Pulse animation for live indicator */
        .pulse-dot {
            animation: pulseDot 2s ease-in-out infinite;
        }
        
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        
        /* Number counter glow */
        .number-glow {
            text-shadow: 0 0 30px rgba(220, 0, 0, 0.2);
        }
        
        /* Dashboard header */
        .dashboard-header {
            position: relative;
        }
        
        .dashboard-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220, 0, 0, 0.3), transparent);
        }
    </style>
</head>
<body class="grid-pattern flex min-h-screen">

    <!-- Memanggil Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Konten Utama - Ferrari Style -->
    <main class="flex-1 p-8 md:p-12 overflow-y-auto">
        
        <!-- Header Dashboard -->
        <header class="dashboard-header mb-10 pb-6 flex flex-col md:flex-row justify-between items-start md:items-end">
            <div>
                <div class="flex items-center space-x-4 mb-3">
                    <span class="inline-block w-2 h-2 ferrari-bg rounded-full pulse-dot"></span>
                    <span class="ferrari-red font-['Orbitron'] text-xs tracking-[0.3em]">RINGKASAN SISTEM</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white glow-text">
                    Dashboard <span class="ferrari-red">Utama</span>
                </h1>
                <p class="text-gray-400 mt-2 text-lg tracking-wide font-light">
                    Selamat datang kembali di panel administrasi <span class="text-white font-semibold">SMK KBM 2</span>
                </p>
            </div>
            <div class="hidden md:flex items-center space-x-4 mt-4 md:mt-0">
                <div class="bg-[#1a1a1a] border border-red-500/20 px-5 py-3 rounded-full flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 font-bold font-['Orbitron'] text-sm border border-red-500/30">
                            A
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-black"></div>
                    </div>
                    <div class="text-sm">
                        <p class="font-bold text-white font-['Rajdhani']">Administrator</p>
                        <p class="text-gray-500 text-xs font-['Orbitron'] tracking-wider">admin@smkkbm2.sch.id</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Kartu Metrik (Statistik) - Ferrari Style -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
            
            <!-- Card 1: Artikel -->
            <div class="metric-card p-6 rounded-2xl group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">ARTIKEL</p>
                        <p class="text-3xl font-bold text-white number-glow font-['Orbitron']">
                            <?= mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM artikel")); ?>
                        </p>
                    </div>
                    <div class="icon-wrapper w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center text-lg border border-red-500/20">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-600 font-['Orbitron'] tracking-wider">
                    
                </div>
            </div>

            <!-- Card 2: Galeri -->
            <div class="metric-card p-6 rounded-2xl group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">GALERI</p>
                        <p class="text-3xl font-bold text-white number-glow font-['Orbitron']">
                            <?= mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM gallery")); ?>
                        </p>
                    </div>
                    <div class="icon-wrapper w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-lg border border-blue-500/20">
                        <i class="fas fa-images"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-600 font-['Orbitron'] tracking-wider">
            
                </div>
            </div>

            <!-- Card 3: Produk -->
            <div class="metric-card p-6 rounded-2xl group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">PRODUK</p>
                        <p class="text-3xl font-bold text-white number-glow font-['Orbitron']">
                            <?= mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM produk_layanan")); ?>
                        </p>
                    </div>
                    <div class="icon-wrapper w-12 h-12 rounded-xl bg-yellow-500/10 text-yellow-400 flex items-center justify-center text-lg border border-yellow-500/20">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-600 font-['Orbitron'] tracking-wider">
    
                </div>
            </div>

            <!-- Card 4: Pesan -->
            <div class="metric-card p-6 rounded-2xl group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">PESAN</p>
                        <p class="text-3xl font-bold text-white number-glow font-['Orbitron']">
                            <?= mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM contact_messages")); ?>
                        </p>
                    </div>
                    <div class="icon-wrapper w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-lg border border-purple-500/20">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-600 font-['Orbitron'] tracking-wider">
            
                </div>
            </div>

            <!-- Card 5: Pesanan -->
            <div class="metric-card p-6 rounded-2xl group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">PESANAN</p>
                        <p class="text-3xl font-bold text-white number-glow font-['Orbitron']">
                            <?php 
                                $query_pesanan = @mysqli_query($koneksi, "SELECT id FROM pesanan");
                                echo $query_pesanan ? mysqli_num_rows($query_pesanan) : "0"; 
                            ?>
                        </p>
                    </div>
                    <div class="icon-wrapper w-12 h-12 rounded-xl bg-orange-500/10 text-orange-400 flex items-center justify-center text-lg border border-orange-500/20">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-600 font-['Orbitron'] tracking-wider">
                
                </div>
            </div>
        </div>
        
        <!-- Aktivitas Terbaru & Quick Actions - Ferrari Style -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Aktivitas Terbaru -->
            <div class="lg:col-span-2 ferrari-activity rounded-2xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-white font-['Orbitron'] tracking-wider">
                            <i class="fas fa-chart-line ferrari-red mr-2"></i>
                            Aktivitas Terbaru
                        </h3>
                        <p class="text-xs text-gray-500 font-['Orbitron'] tracking-[0.2em] mt-1">REAL-TIME UPDATE</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions - Ferrari Style -->
            <div class="ferrari-activity rounded-2xl p-8">
                <h3 class="text-lg font-bold text-white font-['Orbitron'] tracking-wider mb-6">
                    <i class="fas fa-bolt ferrari-red mr-2"></i>
                    Aksi Cepat
                </h3>
                
                <div class="space-y-3">
                    <a href="artikel.php" class="flex items-center justify-between p-4 bg-red-500/5 rounded-xl border border-red-500/10 hover:border-red-500/30 hover:bg-red-500/10 transition-all group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-plus-circle text-red-500 text-lg"></i>
                            <span class="text-sm text-white font-['Rajdhani'] font-medium">Tambah Artikel</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-600 group-hover:text-red-500 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="gallery.php" class="flex items-center justify-between p-4 bg-blue-500/5 rounded-xl border border-blue-500/10 hover:border-blue-500/30 hover:bg-blue-500/10 transition-all group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-upload text-blue-400 text-lg"></i>
                            <span class="text-sm text-white font-['Rajdhani'] font-medium">Upload Galeri</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-600 group-hover:text-blue-400 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="produk.php" class="flex items-center justify-between p-4 bg-yellow-500/5 rounded-xl border border-yellow-500/10 hover:border-yellow-500/30 hover:bg-yellow-500/10 transition-all group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-plus text-yellow-400 text-lg"></i>
                            <span class="text-sm text-white font-['Rajdhani'] font-medium">Tambah Produk</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-600 group-hover:text-yellow-400 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="pesanan.php" class="flex items-center justify-between p-4 bg-orange-500/5 rounded-xl border border-orange-500/10 hover:border-orange-500/30 hover:bg-orange-500/10 transition-all group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-eye text-orange-400 text-lg"></i>
                            <span class="text-sm text-white font-['Rajdhani'] font-medium">Lihat Pesanan</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-600 group-hover:text-orange-400 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="contact.php" class="flex items-center justify-between p-4 bg-purple-500/5 rounded-xl border border-purple-500/10 hover:border-purple-500/30 hover:bg-purple-500/10 transition-all group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-reply-all text-purple-400 text-lg"></i>
                            <span class="text-sm text-white font-['Rajdhani'] font-medium">Balas Pesan</span>
                        </div>
                        <i class="fas fa-arrow-right text-gray-600 group-hover:text-purple-400 group-hover:translate-x-1 transition-all"></i>
                    </a>
                </div>
                
                <!-- System Status -->
                <div class="mt-6 pt-6 border-t border-red-500/10">
                    <div class="flex items-center justify-between text-xs font-['Orbitron'] tracking-wider">
                        <span class="text-gray-500">SISTEM STATUS</span>
                        <span class="text-green-500 flex items-center">
                            <i class="fas fa-circle text-[6px] mr-1"></i>
                            OPERASIONAL
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Dashboard -->
        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-700 font-['Orbitron'] tracking-[0.2em]">
                 SMK KBM 2 ADMIN PANEL
            </p>
        </div>

    </main>

</body>
</html>