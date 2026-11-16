<?php
require 'config.php';

$error = '';
$success = '';

// --- PROSES HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    // Mencegah admin menghapus akunnya sendiri
    if ($id == $_SESSION['user_id']) {
        $error = "Anda tidak dapat menghapus akun yang sedang digunakan!";
    } else {
        mysqli_query($koneksi, "DELETE FROM users WHERE id = $id");
        header("Location: admin_data.php");
        exit;
    }
}

// --- PROSES TAMBAH DATA ---
if (isset($_POST['tambah_admin'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password     = $_POST['password'];
    
    if (empty($nama_lengkap) || empty($username) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        // Cek apakah username sudah ada
        $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan, silakan pilih yang lain.";
        } else {
            // Hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "INSERT INTO users (username, password, nama_lengkap) VALUES ('$username', '$hashed_password', '$nama_lengkap')");
            $success = "Admin berhasil ditambahkan!";
        }
    }
}

// --- PENCARIAN & PAGINATION ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$limit = 5; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$where_clause = !empty($search) ? "WHERE nama_lengkap LIKE '%$search%' OR username LIKE '%$search%'" : "";
$total_query  = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users $where_clause");
$total_data   = mysqli_fetch_assoc($total_query)['total'];
$total_pages  = ceil($total_data / $limit);

$query_admin = mysqli_query($koneksi, "SELECT * FROM users $where_clause ORDER BY id DESC LIMIT $start, $limit");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Admin - SMAN 13 BEKASI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Ferrari Style */
        body {
            font-family: 'Rajdhani', sans-serif;
            background: #0a0a0a;
            color: #fdf9f9;
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
        
        /* Ferrari Card */
        .ferrari-card {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
            border: 1px solid rgba(220, 0, 0, 0.2);
            border-radius: 1.5rem;
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
        
        /* Ferrari Input */
        .ferrari-input {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(220, 0, 0, 0.2);
            color: #fcf4f4;
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            font-weight: 500;
            padding: 12px 16px;
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
        }
        
        /* Ferrari Button */
        .ferrari-btn-primary {
            background: linear-gradient(135deg, #dc0000, #b00000);
            border: none;
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 12px;
            cursor: pointer;
        }
        
        .ferrari-btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(220, 0, 0, 0.4);
        }
        
        .ferrari-btn-success {
            background: linear-gradient(135deg, #00c853, #009624);
            border: none;
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 12px;
            cursor: pointer;
        }
        
        .ferrari-btn-success:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 200, 83, 0.4);
        }
        
        /* Ferrari Table */
        .ferrari-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .ferrari-table thead {
            background: linear-gradient(135deg, rgba(220, 0, 0, 0.15), rgba(220, 0, 0, 0.05));
            border-bottom: 2px solid rgba(220, 0, 0, 0.2);
        }
        
        .ferrari-table thead th {
            padding: 16px 20px;
            text-align: left;
            font-family: 'Orbitron', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #888;
        }
        
        .ferrari-table tbody tr {
            border-bottom: 1px solid rgba(220, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .ferrari-table tbody tr:hover {
            background: rgba(220, 0, 0, 0.05);
            transform: scale(1.01);
        }
        
        .ferrari-table tbody td {
            padding: 14px 20px;
            color: #ccc;
            font-size: 15px;
        }
        
        /* Delete button */
        .btn-delete {
            color: #ff4444;
            font-family: 'Orbitron', sans-serif;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 6px 14px;
            border: 1px solid rgba(255, 68, 68, 0.3);
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-delete:hover {
            background: rgba(255, 68, 68, 0.15);
            border-color: #ff4444;
            box-shadow: 0 0 30px rgba(255, 68, 68, 0.2);
            color: #ff6666;
        }
        
        /* Pagination */
        .ferrari-pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 24px;
        }
        
        .ferrari-pagination a {
            padding: 8px 16px;
            border-radius: 10px;
            font-family: 'Orbitron', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(220, 0, 0, 0.2);
            color: #888;
            background: transparent;
        }
        
        .ferrari-pagination a:hover {
            border-color: #dc0000;
            color: #ffffff;
            transform: translateY(-2px);
        }
        
        .ferrari-pagination a.active {
            background: linear-gradient(135deg, #dc0000, #b00000);
            border-color: #dc0000;
            color: white;
            box-shadow: 0 5px 20px rgba(220, 0, 0, 0.3);
        }
        
        /* Message */
        .message-error {
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
        }
        
        .message-success {
            background: rgba(0, 200, 83, 0.1);
            border-left: 3px solid #00c853;
            color: #69db7c;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            background: #0a0a0a;
        }
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        ::-webkit-scrollbar-thumb {
            background: #dc0000;
            border-radius: 3px;
        }
        
        /* Grid pattern */
        .grid-pattern {
            background-image: 
                linear-gradient(rgba(220, 0, 0, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220, 0, 0, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
    </style>
</head>
<body class="grid-pattern flex min-h-screen">
    <?php include 'sidebar.php'; ?>
    
    <main class="flex-1 p-8 md:p-12 overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="inline-block w-2 h-2 ferrari-bg rounded-full pulse-dot"></span>
                    <span class="ferrari-red font-['Orbitron'] text-xs tracking-[0.3em]">MANAJEMEN</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white">
                    Data <span class="ferrari-red">Admin</span>
                </h1>
                <p class="text-gray-400 text-sm mt-1 tracking-wide">Kelola akun administrator sekolah</p>
            </div>
            <div class="mt-4 md:mt-0">
                <form action="" method="GET" class="flex space-x-2">
                    <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" 
                           placeholder="Cari nama/username..." 
                           class="ferrari-input text-sm" style="padding: 10px 16px; min-width: 200px;">
                    <button type="submit" class="ferrari-btn-primary" style="padding: 10px 20px;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Messages -->
        <?php if($error): ?>
            <div class="message-error mb-6">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                <?= $error; ?>
            </div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="message-success mb-6">
                <i class="fas fa-check-circle text-green-500"></i>
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Admin -->
        <div class="ferrari-card p-6 md:p-8 mb-8">
            <h3 class="text-lg font-bold text-white font-['Orbitron'] text-sm tracking-wider mb-6">
                <i class="fas fa-user-plus ferrari-red mr-2"></i>
                Tambah Admin Baru
            </h3>
            <form action="" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-white-500 tracking-[0.2em] mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text" name="nama_lengkap" placeholder="Nama lengkap admin" 
                               class="ferrari-input" required>
                    </div>
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            Username
                        </label>
                        <input type="text" name="username" placeholder="Username untuk login" 
                               class="ferrari-input" required>
                    </div>
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            Password
                        </label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" 
                               class="ferrari-input" required minlength="6">
                    </div>
                </div>
                <button type="submit" name="tambah_admin" class="ferrari-btn-success">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Admin
                </button>
            </form>
        </div>

        <!-- Tabel Data Admin -->
        <div class="ferrari-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="ferrari-table">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Dibuat Pada</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($query_admin) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_admin)): ?>
                            <tr>
                                <td class="font-semibold text-white">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 font-['Orbitron'] text-xs border border-red-500/30">
                                            <?= strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                        </div>
                                        <span><?= htmlspecialchars($row['nama_lengkap']); ?></span>
                                    </div>
                                </td>
                                <td class="text-gray-400">
                                    <i class="fas fa-user-circle text-gray-600 mr-2"></i>
                                    <?= htmlspecialchars($row['username']); ?>
                                </td>
                                <td class="text-gray-500 text-sm">
                                    <i class="far fa-calendar-alt text-gray-600 mr-2"></i>
                                    <?= date('d M Y, H:i', strtotime($row['created_at'])); ?>
                                </td>
                                <td class="text-center">
                                    <?php if($row['id'] == $_SESSION['user_id']): ?>
                                        <span class="text-xs text-gray-500 font-['Orbitron'] tracking-wider">
                                            <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                                            Akun Aktif
                                        </span>
                                    <?php else: ?>
                                        <a href="admin_data.php?hapus=<?= $row['id']; ?>" 
                                           onclick="return confirm('Hapus admin ini?')" 
                                           class="btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-12 text-gray-500">
                                    <i class="fas fa-users text-4xl text-gray-700 block mb-3"></i>
                                    <p class="font-['Orbitron'] text-sm tracking-wider">Tidak ada data admin ditemukan</p>
                                    <?php if(!empty($search)): ?>
                                        <p class="text-xs text-gray-600 mt-1">Coba dengan kata kunci yang berbeda</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <div class="ferrari-pagination">
                <?php for($i=1; $i<=$total_pages; $i++): ?>
                    <a href="admin_data.php?page=<?= $i; ?>&search=<?= $search; ?>" 
                       class="<?= $page == $i ? 'active' : ''; ?>">
                        <?= str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        
        <!-- Footer Info -->
        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-700 font-['Orbitron'] tracking-[0.2em]">
                    SMK KBM 2 ADMIN PANEL
            </p>
            <p class="text-[8px] text-gray-700 font-['Orbitron'] tracking-[0.2em] mt-1">
                Total Data: <?= $total_data; ?> Admin
            </p>
        </div>
    </main>
</body>
</html>

<style>
    .pulse-dot {
        animation: pulseDot 2s ease-in-out infinite;
    }
    
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }
</style>