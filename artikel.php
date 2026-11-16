<?php
require 'config.php';

// --- PROSES HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    // Hapus berkas gambar lama dari penyimpanan server terlebih dahulu
    $img_res = mysqli_query($koneksi, "SELECT gambar FROM artikel WHERE id = $id");
    $img_data = mysqli_fetch_assoc($img_res);
    if ($img_data && file_exists('uploads/' . $img_data['gambar'])) {
        unlink('uploads/' . $img_data['gambar']);
    }
    
    mysqli_query($koneksi, "DELETE FROM artikel WHERE id = $id");
    header("Location: artikel.php");
    exit;
}

// --- PROSES TAMBAH DATA ---
$error = '';
$success = '';
if (isset($_POST['tambah_artikel'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);
    
    // Validasi Form
    if (empty($judul) || empty($konten) || empty($_FILES['gambar']['name'])) {
        $error = "Judul, Konten, dan Gambar tidak boleh kosong!";
    } else {
        // Upload Gambar
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file  = $_FILES['gambar']['tmp_name'];
        $ext       = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $file_baru = 'art_' . time() . '_' . uniqid() . '.' . $ext;
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($ext, $allowed_ext)) {
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }

            if (move_uploaded_file($tmp_file, 'uploads/' . $file_baru)) {
                mysqli_query($koneksi, "INSERT INTO artikel (judul, konten, gambar) VALUES ('$judul', '$konten', '$file_baru')");
                $success = "Artikel berhasil ditambahkan!";
            } else {
                $error = "Gagal mengunggah berkas gambar.";
            }
        } else {
            $error = "Format file tidak didukung! Gunakan JPG, PNG, atau WEBP.";
        }
    }
}

// --- FITUR PENCARIAN & PAGINATION ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$limit = 5; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$where_clause = !empty($search) ? "WHERE judul LIKE '%$search%'" : "";
$total_query  = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM artikel $where_clause");
$total_data   = mysqli_fetch_assoc($total_query)['total'];
$total_pages  = ceil($total_data / $limit);

$query_artikel = mysqli_query($koneksi, "SELECT * FROM artikel $where_clause ORDER BY id DESC LIMIT $start, $limit");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Artikel - SMAN 13 BEKASI</title>
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
        
        /* Image preview */
        .image-preview {
            width: 80px;
            height: 56px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid rgba(220, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .image-preview:hover {
            transform: scale(1.1);
            border-color: #dc0000;
            box-shadow: 0 5px 20px rgba(220, 0, 0, 0.3);
        }
        
        .pulse-dot {
            animation: pulseDot 2s ease-in-out infinite;
        }
        
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
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
                    <span class="ferrari-red font-['Orbitron'] text-xs tracking-[0.3em]">KONTEN</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white">
                    Manajemen <span class="ferrari-red">Artikel</span>
                </h1>
                <p class="text-gray-400 text-sm mt-1 tracking-wide">Kelola artikel dan publikasi sekolah</p>
            </div>
            <div class="mt-4 md:mt-0">
                <form action="" method="GET" class="flex space-x-2">
                    <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" 
                           placeholder="Cari judul..." 
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

        <!-- Form Tambah Artikel -->
        <div class="ferrari-card p-6 md:p-8 mb-8">
            <h3 class="text-lg font-bold text-white font-['Orbitron'] text-sm tracking-wider mb-6">
                <i class="fas fa-plus-circle ferrari-red mr-2"></i>
                Tambah Artikel Baru
            </h3>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            <i class="fas fa-heading text-red-500/50 mr-1"></i>
                            Judul Artikel
                        </label>
                        <input type="text" name="judul" required placeholder="Masukkan judul artikel..." 
                               class="ferrari-input">
                    </div>
                    <div>
                        <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                            <i class="fas fa-image text-red-500/50 mr-1"></i>
                            Gambar Sampul
                        </label>
                        <input type="file" name="gambar" required accept="image/*" 
                               class="ferrari-input-file">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-['Orbitron'] text-gray-500 tracking-[0.2em] mb-2">
                        <i class="fas fa-align-left text-red-500/50 mr-1"></i>
                        Isi Konten
                    </label>
                    <textarea name="konten" rows="5" required placeholder="Tulis konten artikel di sini..." 
                              class="ferrari-input" style="resize: vertical;"></textarea>
                </div>
                
                <button type="submit" name="tambah_artikel" class="ferrari-btn-success">
                    <i class="fas fa-save mr-2"></i>
                    Simpan & Unggah Artikel
                </button>
            </form>
        </div>

        <!-- Tabel Data Artikel -->
        <div class="ferrari-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="ferrari-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Tanggal Post</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($query_artikel) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_artikel)): ?>
                            <tr>
                                <td>
                                    <img src="uploads/<?= $row['gambar']; ?>" 
                                         alt="<?= htmlspecialchars($row['judul']); ?>" 
                                         class="image-preview">
                                </td>
                                <td class="font-semibold text-white">
                                    <?= htmlspecialchars($row['judul']); ?>
                                </td>
                                <td class="text-gray-500 text-sm">
                                    <i class="far fa-calendar-alt text-gray-600 mr-2"></i>
                                    <?= date('d M Y, H:i', strtotime($row['created_at'])); ?>
                                </td>
                                <td class="text-center">
                                    <a href="artikel.php?hapus=<?= $row['id']; ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')" 
                                       class="ferrari-btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-16 text-gray-500">
                                    <i class="fas fa-newspaper text-5xl text-gray-700 block mb-4"></i>
                                    <p class="font-['Orbitron'] text-sm tracking-wider">Belum ada data artikel</p>
                                    <?php if(!empty($search)): ?>
                                        <p class="text-xs text-gray-600 mt-1">Coba dengan kata kunci yang berbeda</p>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-600 mt-1">Mulai dengan menambahkan artikel baru</p>
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
                    <a href="artikel.php?page=<?= $i; ?>&search=<?= $search; ?>" 
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
                Total Data: <?= $total_data; ?> Artikel
            </p>
        </div>
    </main>

</body>
</html>