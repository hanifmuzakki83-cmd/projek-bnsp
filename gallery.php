<?php
require 'config.php';

$error = '';

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $img_res = mysqli_query($koneksi, "SELECT gambar FROM gallery WHERE id = $id");
    $img_data = mysqli_fetch_assoc($img_res);
    if ($img_data && file_exists('uploads/' . $img_data['gambar'])) {
        unlink('uploads/' . $img_data['gambar']);
    }
    mysqli_query($koneksi, "DELETE FROM gallery WHERE id = $id");
    header("Location: gallery.php");
    exit;
}

if (isset($_POST['tambah_foto'])) {
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    
    if (empty($keterangan) || empty($_FILES['gambar']['name'])) {
        $error = "Keterangan dan Gambar wajib diisi!";
    } else {
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file  = $_FILES['gambar']['tmp_name'];
        $ext       = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $file_baru = 'gal_' . time() . '_' . uniqid() . '.' . $ext;
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if(in_array($ext, $allowed_ext)) {
            if (!is_dir('uploads')) mkdir('uploads', 0777, true);
            if (move_uploaded_file($tmp_file, 'uploads/' . $file_baru)) {
                mysqli_query($koneksi, "INSERT INTO gallery (keterangan, gambar) VALUES ('$keterangan', '$file_baru')");
                header("Location: gallery.php");
                exit;
            } else {
                $error = "Gagal mengunggah berkas gambar.";
            }
        } else {
            $error = "Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WEBP.";
        }
    }
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$limit = 6; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;
$where_clause = !empty($search) ? "WHERE keterangan LIKE '%$search%'" : "";
$total_query  = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM gallery $where_clause");
$total_data   = mysqli_fetch_assoc($total_query)['total'];
$total_pages  = ceil($total_data / $limit);
$query_galeri = mysqli_query($koneksi, "SELECT * FROM gallery $where_clause ORDER BY id DESC LIMIT $start, $limit");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Galeri - SMAN 13 BEKASI</title>
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
        
        /* Gallery Card */
        .gallery-card {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
            border: 1px solid rgba(220, 0, 0, 0.15);
            border-radius: 1.5rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            group: true;
        }
        
        .gallery-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #dc0000;
            box-shadow: 0 30px 60px rgba(220, 0, 0, 0.15);
        }
        
        .gallery-card .image-wrapper {
            position: relative;
            overflow: hidden;
            height: 220px;
        }
        
        .gallery-card .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .gallery-card:hover .image-wrapper img {
            transform: scale(1.1);
        }
        
        .gallery-card .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 50%, rgba(220, 0, 0, 0.3));
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        
        .gallery-card:hover .image-overlay {
            opacity: 1;
        }
        
        .gallery-card .card-content {
            padding: 16px 20px 20px;
        }
        
        .gallery-card .card-content .keterangan {
            color: #ffffff;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .gallery-card .card-content .tanggal {
            color: #888;
            font-family: 'Orbitron', sans-serif;
            font-size: 10px;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }
        
        .btn-delete-gallery {
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
        
        .btn-delete-gallery:hover {
            background: rgba(255, 68, 68, 0.15);
            border-color: #ff4444;
            box-shadow: 0 0 30px rgba(255, 68, 68, 0.2);
            color: #ff6666;
            transform: scale(1.05);
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
                    <span class="ferrari-red font-['Orbitron'] text-xs tracking-[0.3em]">MEDIA</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white">
                    Manajemen <span class="ferrari-red">Galeri</span>
                </h1>
                <p class="text-gray-400 text-sm mt-1 tracking-wide">Kelola foto dan dokumentasi sekolah</p>
            </div>
            <div class="mt-4 md:mt-0">
                <form action="" method="GET" class="flex space-x-2">
                    <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" 
                           placeholder="Cari keterangan..." 
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

        <!-- Form Tambah Foto -->
        <div class="ferrari-card p-6 md:p-8 mb-8">
            <h3 class="text-lg font-bold text-white font-['Orbitron'] text-sm tracking-wider mb-6">
                <i class="fas fa-plus-circle ferrari-red mr-2"></i>
                Tambah Foto Galeri
            </h3>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            <i class="fas fa-tag text-red-500/50 mr-1"></i>
                            Keterangan Foto
                        </label>
                        <input type="text" name="keterangan" placeholder="Masukkan keterangan foto..." 
                               class="ferrari-input">
                    </div>
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            <i class="fas fa-image text-red-500/50 mr-1"></i>
                            Pilih File Gambar
                        </label>
                        <input type="file" name="gambar" required accept="image/*" 
                               class="ferrari-input-file">
                    </div>
                </div>
                <button type="submit" name="tambah_foto" class="ferrari-btn-success">
                    <i class="fas fa-upload mr-2"></i>
                    Unggah Foto
                </button>
            </form>
        </div>

        <!-- Gallery Grid -->
        <?php if(mysqli_num_rows($query_galeri) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while($row = mysqli_fetch_assoc($query_galeri)): ?>
                <div class="gallery-card group">
                    <div class="image-wrapper">
                        <img src="uploads/<?= $row['gambar']; ?>" alt="<?= htmlspecialchars($row['keterangan']); ?>">
                        <div class="image-overlay"></div>
                        <!-- Badge -->
                        <div class="absolute top-3 right-3 bg-red-500/90 text-white text-[8px] font-['Orbitron'] tracking-wider px-3 py-1 rounded-full border border-red-500/30">
                            <i class="fas fa-camera mr-1"></i>
                            FOTO
                        </div>
                    </div>
                    <div class="card-content">
                        <p class="keterangan"><?= htmlspecialchars($row['keterangan']); ?></p>
                        <p class="tanggal">
                            <i class="far fa-calendar-alt mr-1"></i>
                            <?= date('d M Y, H:i', strtotime($row['created_at'])); ?>
                        </p>
                        <a href="gallery.php?hapus=<?= $row['id']; ?>" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')" 
                           class="btn-delete-gallery">
                            <i class="fas fa-trash"></i> Hapus Foto
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="ferrari-card p-12">
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <p>Belum ada foto dalam galeri</p>
                    <?php if(!empty($search)): ?>
                        <p class="text-xs text-gray-600 mt-2 font-['Rajdhani'] font-light">
                            Coba dengan kata kunci yang berbeda
                        </p>
                    <?php else: ?>
                        <p class="text-xs text-gray-600 mt-2 font-['Rajdhani'] font-light">
                            Mulai dengan mengunggah foto pertama Anda
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <div class="ferrari-pagination">
                <?php for($i=1; $i<=$total_pages; $i++): ?>
                    <a href="gallery.php?page=<?= $i; ?>&search=<?= $search; ?>" 
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
                Total Data: <?= $total_data; ?> Foto
            </p>
        </div>
    </main>

</body>
</html>