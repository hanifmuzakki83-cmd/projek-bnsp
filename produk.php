<?php
require 'config.php';

$error = '';

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $img_res = mysqli_query($koneksi, "SELECT gambar FROM produk_layanan WHERE id = $id");
    $img_data = mysqli_fetch_assoc($img_res);
    if ($img_data && file_exists('uploads/' . $img_data['gambar'])) {
        unlink('uploads/' . $img_data['gambar']);
    }
    mysqli_query($koneksi, "DELETE FROM produk_layanan WHERE id = $id");
    header("Location: produk.php");
    exit;
}

if (isset($_POST['tambah_produk'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $harga = intval($_POST['harga']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    
    if (empty($nama_produk) || empty($harga) || empty($deskripsi) || empty($_FILES['gambar']['name'])) {
        $error = "Semua kolom wajib diisi!";
    } else {
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file  = $_FILES['gambar']['tmp_name'];
        $ext       = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $file_baru = 'prd_' . time() . '_' . uniqid() . '.' . $ext;
        
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        if (move_uploaded_file($tmp_file, 'uploads/' . $file_baru)) {
            mysqli_query($koneksi, "INSERT INTO produk_layanan (nama_produk, harga, deskripsi, gambar) VALUES ('$nama_produk', $harga, '$deskripsi', '$file_baru')");
            header("Location: produk.php");
            exit;
        } else {
            $error = "Gagal mengunggah berkas gambar.";
        }
    }
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$limit = 5; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
$where_clause = !empty($search) ? "WHERE nama_produk LIKE '%$search%'" : "";
$total_query  = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM produk_layanan $where_clause");
$total_data   = mysqli_fetch_assoc($total_query)['total'];
$total_pages  = ceil($total_data / $limit);
$query_produk = mysqli_query($koneksi, "SELECT * FROM produk_layanan $where_clause ORDER BY id DESC LIMIT $start, $limit");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk - SMAN 13 BEKASI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Ferrari Style */
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
            color: #ffffff;
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
        
        .ferrari-input-file {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(220, 0, 0, 0.2);
            color: #ffffff;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            padding: 10px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
            width: 100%;
            outline: none;
        }
        
        .ferrari-input-file::-webkit-file-upload-button {
            background: linear-gradient(135deg, #dc0000, #b00000);
            border: none;
            color: white;
            padding: 6px 16px;
            border-radius: 8px;
            font-family: 'Orbitron', sans-serif;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .ferrari-input-file::-webkit-file-upload-button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(220, 0, 0, 0.3);
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
            padding: 12px 28px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 12px;
            cursor: pointer;
        }
        
        .ferrari-btn-success:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 200, 83, 0.4);
        }
        
        .ferrari-btn-danger {
            background: transparent;
            border: 1px solid rgba(255, 68, 68, 0.3);
            color: #ff4444;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 6px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .ferrari-btn-danger:hover {
            background: rgba(255, 68, 68, 0.15);
            border-color: #ff4444;
            box-shadow: 0 0 30px rgba(255, 68, 68, 0.2);
            color: #ff6666;
            transform: scale(1.05);
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
        
        /* Image preview */
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid rgba(220, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .product-image:hover {
            transform: scale(1.1);
            border-color: #dc0000;
            box-shadow: 0 5px 20px rgba(220, 0, 0, 0.3);
        }
        
        /* Price tag */
        .price-tag {
            color: #ffa500;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 14px;
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
        
        .pulse-dot {
            animation: pulseDot 2s ease-in-out infinite;
        }
        
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 64px;
            color: #1a1a1a;
            margin-bottom: 16px;
        }
        
        .empty-state p {
            color: #555;
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            letter-spacing: 0.05em;
        }
        
        /* Description column */
        .desc-text {
            color: #888;
            font-size: 13px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
                    <span class="ferrari-red font-['Orbitron'] text-xs tracking-[0.3em]">KOPERASI</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white">
                    Manajemen <span class="ferrari-red">Produk</span>
                </h1>
                <p class="text-gray-400 text-sm mt-1 tracking-wide">Kelola produk koperasi dan layanan sekolah</p>
            </div>
            <div class="mt-4 md:mt-0">
                <form action="" method="GET" class="flex space-x-2">
                    <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" 
                           placeholder="Cari produk..." 
                           class="ferrari-input text-sm" style="padding: 10px 16px; min-width: 200px;">
                    <button type="submit" class="ferrari-btn-primary" style="padding: 10px 20px;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Error Message -->
        <?php if($error): ?>
            <div class="message-error mb-6">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Produk -->
        <div class="ferrari-card p-6 md:p-8 mb-8">
            <h3 class="text-lg font-bold text-white font-['Orbitron'] text-sm tracking-wider mb-6">
                <i class="fas fa-plus-circle ferrari-red mr-2"></i>
                Tambah Produk / Layanan
            </h3>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            <i class="fas fa-tag text-red-500/50 mr-1"></i>
                            Nama Produk
                        </label>
                        <input type="text" name="nama_produk" placeholder="Masukkan nama produk..." 
                               class="ferrari-input">
                    </div>
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            <i class="fas fa-money-bill-wave text-red-500/50 mr-1"></i>
                            Harga (Rp)
                        </label>
                        <input type="number" name="harga" placeholder="0" 
                               class="ferrari-input">
                    </div>
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            <i class="fas fa-image text-red-500/50 mr-1"></i>
                            Gambar Produk
                        </label>
                        <input type="file" name="gambar" required accept="image/*" 
                               class="ferrari-input-file">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                        <i class="fas fa-align-left text-red-500/50 mr-1"></i>
                        Deskripsi Singkat
                    </label>
                    <textarea name="deskripsi" rows="3" placeholder="Tulis deskripsi produk di sini..." 
                              class="ferrari-input" style="resize: vertical;"></textarea>
                </div>
                <button type="submit" name="tambah_produk" class="ferrari-btn-success">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Produk
                </button>
            </form>
        </div>

        <!-- Tabel Data Produk -->
        <div class="ferrari-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="ferrari-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($query_produk) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_produk)): ?>
                            <tr>
                                <td>
                                    <img src="uploads/<?= $row['gambar']; ?>" 
                                         alt="<?= htmlspecialchars($row['nama_produk']); ?>" 
                                         class="product-image">
                                </td>
                                <td class="font-semibold text-white">
                                    <?= htmlspecialchars($row['nama_produk']); ?>
                                </td>
                                <td>
                                    <span class="price-tag">
                                        <i class="fas fa-tag mr-1" style="font-size: 10px; color: #ffa500;"></i>
                                        Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="desc-text"><?= htmlspecialchars($row['deskripsi']); ?></div>
                                </td>
                                <td class="text-center">
                                    <a href="produk.php?hapus=<?= $row['id']; ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" 
                                       class="ferrari-btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-16 text-gray-500">
                                    <i class="fas fa-box-open text-5xl text-gray-700 block mb-4"></i>
                                    <p class="font-['Orbitron'] text-sm tracking-wider">Belum ada data produk</p>
                                    <?php if(!empty($search)): ?>
                                        <p class="text-xs text-gray-600 mt-1">Coba dengan kata kunci yang berbeda</p>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-600 mt-1">Mulai dengan menambahkan produk baru</p>
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
                    <a href="produk.php?page=<?= $i; ?>&search=<?= $search; ?>" 
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
                Total Data: <?= $total_data; ?> Produk
            </p>
        </div>
    </main>

</body>
</html>