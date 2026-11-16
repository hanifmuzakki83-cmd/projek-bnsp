<?php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<style>
    /* Ferrari Style Sidebar */
    .sidebar-ferrari-v2 {
        background: linear-gradient(180deg, #0a0a0a 0%, #1a0000 50%, #0a0a0a 100%);
        border-right: 2px solid rgba(220, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 280px;
        flex-shrink: 0;
    }
    
    .sidebar-ferrari-v2::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 2px;
        height: 100%;
        background: linear-gradient(180deg, transparent, #dc0000, transparent);
        animation: pulseLine 3s ease-in-out infinite;
    }
    
    @keyframes pulseLine {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 1; }
    }
    
    /* Ferrari Logo */
    .ferrari-logo-v2 {
        position: relative;
        display: inline-block;
    }
    
    .ferrari-logo-v2::after {
        content: '';
        position: absolute;
        inset: -10px;
        background: radial-gradient(circle, rgba(220, 0, 0, 0.2), transparent 70%);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    
    .ferrari-logo-v2:hover::after {
        opacity: 1;
    }
    
    /* Ferrari Menu Items */
    .ferrari-menu-item-v2 {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        display: block;
        padding: 12px 16px;
        border-radius: 12px;
        text-decoration: none;
        color: #888;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 500;
        font-size: 15px;
        letter-spacing: 0.03em;
    }
    
    .ferrari-menu-item-v2::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(220, 0, 0, 0.08), transparent);
        transition: left 0.5s ease;
    }
    
    .ferrari-menu-item-v2:hover::before {
        left: 100%;
    }
    
    .ferrari-menu-item-v2:hover {
        border-color: rgba(220, 0, 0, 0.3);
        transform: translateX(5px);
        color: #ffffff;
    }
    
    .ferrari-menu-item-v2.active {
        background: linear-gradient(135deg, rgba(220, 0, 0, 0.15), rgba(220, 0, 0, 0.05));
        border-color: #dc0000;
        color: #ffffff;
        box-shadow: 0 0 30px rgba(220, 0, 0, 0.08), inset 0 0 30px rgba(220, 0, 0, 0.03);
        transform: translateX(5px);
    }
    
    .ferrari-menu-item-v2.active::after {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 3px;
        background: #dc0000;
        box-shadow: 0 0 20px rgba(220, 0, 0, 0.5);
    }
    
    .ferrari-menu-item-v2 .menu-icon {
        width: 24px;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        margin-right: 12px;
        color: #555;
    }
    
    .ferrari-menu-item-v2:hover .menu-icon {
        transform: scale(1.1) rotate(-5deg);
        color: #dc0000;
    }
    
    .ferrari-menu-item-v2.active .menu-icon {
        color: #dc0000;
    }
    
    
    
    /* ============================================= */
    /* END KHUSUS MENU DATA PESANAN */
    /* ============================================= */
    
    /* Badge notification default */
    .notification-badge-v2 {
        background: #dc0000;
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        font-family: 'Orbitron', sans-serif;
        animation: pulseBadge 2s ease-in-out infinite;
        margin-left: auto;
    }
    
    @keyframes pulseBadge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); box-shadow: 0 0 20px rgba(220, 0, 0, 0.4); }
    }
    
    /* Racing stripe decoration */
    .racing-stripe-v2 {
        position: relative;
    }
    
    .racing-stripe-v2::after {
        content: '▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(220, 0, 0, 0.08);
        font-size: 6px;
        letter-spacing: 2px;
        white-space: nowrap;
    }
    
    /* User info */
    .user-info-v2 {
        background: rgba(220, 0, 0, 0.05);
        border: 1px solid rgba(220, 0, 0, 0.1);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 12px;
    }
    
    .user-info-v2 .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc0000, #b00000);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        font-size: 14px;
        color: white;
    }
    
    /* Logout button */
    .logout-btn-v2 {
        background: transparent;
        border: 1px solid rgba(220, 0, 0, 0.2);
        color: #888;
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        padding: 12px;
        border-radius: 12px;
        width: 100%;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: block;
        cursor: pointer;
    }
    
    .logout-btn-v2:hover {
        background: rgba(220, 0, 0, 0.1);
        border-color: #dc0000;
        color: #dc0000;
        transform: scale(1.02);
        box-shadow: 0 0 30px rgba(220, 0, 0, 0.1);
    }
    
    /* Scrollbar styling */
    .sidebar-ferrari-v2 ::-webkit-scrollbar {
        width: 3px;
    }
    .sidebar-ferrari-v2 ::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar-ferrari-v2 ::-webkit-scrollbar-thumb {
        background: #dc0000;
        border-radius: 2px;
    }
    
    /* Divider */
    .sidebar-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(220, 0, 0, 0.2), transparent);
        margin: 8px 0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sidebar-ferrari-v2 {
            width: 100%;
            min-height: auto;
        }
    }
</style>

<div class="sidebar-ferrari-v2">
    <!-- Header / Branding -->
    <div class="p-6 border-b border-red-500/20 racing-stripe-v2">
        <a href="dashboard.php" class="flex items-center space-x-3 text-decoration-none">
            <div class="ferrari-logo-v2">
                <div class="absolute inset-0 bg-red-500 blur-xl opacity-30 rounded-full"></div>
                <div class="bg-white p-2 rounded-lg shadow-2xl relative z-10 border-2 border-red-500/30">
                    <img src="Smkn_kbm2.png" alt="SMK KBM 2" class="w-10 h-10 object-contain">
                </div>
            </div>
            <div>
                <span class="font-['Orbitron'] text-sm font-bold tracking-widest text-white block leading-tight">
                    SMK <span class="text-red-500">KBM</span> 2
                </span>
                <span class="text-[10px] text-white-500 font-['Orbitron'] tracking-[0.2em]">admin panel</span>
            </div>
        </a>
    </div>
    
    <!-- Navigation -->
    <div class="flex-1 px-4 py-6 overflow-y-auto">
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="dashboard.php" class="ferrari-menu-item-v2 <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span>Dashboard</span>
            </a>
            
            <!-- Data Admin -->
            <a href="admin_data.php" class="ferrari-menu-item-v2 <?= (basename($_SERVER['PHP_SELF']) == 'admin_data.php') ? 'active' : ''; ?>">
                <i class="fas fa-users-cog menu-icon"></i>
                <span>Data Admin</span>
            </a>
            
            <!-- Artikel -->
            <a href="artikel.php" class="ferrari-menu-item-v2 <?= (basename($_SERVER['PHP_SELF']) == 'artikel.php') ? 'active' : ''; ?>">
                <i class="fas fa-newspaper menu-icon"></i>
                <span>Artikel</span>
            </a>
            
            <!-- Gallery -->
            <a href="gallery.php" class="ferrari-menu-item-v2 <?= (basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'active' : ''; ?>">
                <i class="fas fa-images menu-icon"></i>
                <span>Gallery</span>
            </a>
            
            <!-- Produk/Layanan -->
            <a href="produk.php" class="ferrari-menu-item-v2 <?= (basename($_SERVER['PHP_SELF']) == 'produk.php') ? 'active' : ''; ?>">
                <i class="fas fa-box-open menu-icon"></i>
                <span>Produk/Layanan</span>
            </a>

            <!-- Data Pesanan (Checkout) - KHUSUS WARNA ORANGE -->
            <a href="pesanan.php" class="ferrari-menu-item-v2 pesanan-menu <?= (basename($_SERVER['PHP_SELF']) == 'pesanan.php') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart menu-icon"></i>
                <span>Data Pesanan</span>
            </a>
            
            <!-- Pesan Masuk -->
            <a href="contact.php" class="ferrari-menu-item-v2 <?= (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">
                <i class="fas fa-envelope menu-icon"></i>
                <span>Pesan Masuk</span>
            </a>
            
            <!-- Divider -->
            <div class="sidebar-divider"></div>
            
        </nav>
    </div>
    
    <!-- Footer / User Info & Logout -->
    <div class="p-6 border-t border-red-500/20">
        <!-- User Info -->
        <div class="user-info-v2 flex items-center space-x-3">
            <div class="user-avatar">
                <?= strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white font-['Rajdhani'] truncate">
                    <?= htmlspecialchars($_SESSION['nama']); ?>
                </p>
                <p class="text-[10px] text-gray-500 font-['Orbitron'] tracking-wider">
                    <i class="fas fa-circle text-green-500 text-[6px] mr-1"></i>
                    Online
                </p>
            </div>
            <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></div>
        </div>
        
        <!-- Logout Button -->
        <a href="logout.php" 
           onclick="return confirm('Apakah anda yakin ingin keluar dari sistem?');" 
           class="logout-btn-v2">
            <i class="fas fa-sign-out-alt mr-2"></i>
            Keluar Sistem
        </a>
        
        <!-- Footer Branding -->
        <div class="mt-4 text-center">
            <p class="text-[8px] text-gray-700 font-['Orbitron'] tracking-[0.3em]"
            <p class="text-[7px] text-gray-700 font-['Orbitron'] tracking-[0.2em] mt-1">
               SMK KBM 2
            </p>
        </div>
    </div>
</div>

<!-- Pulse dot animation -->
<style>
    .pulse-dot {
        animation: pulseDot 2s ease-in-out infinite;
    }
    
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }
</style>