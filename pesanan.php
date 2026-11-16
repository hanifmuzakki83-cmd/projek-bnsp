<?php
require 'config.php';

// Proses Update Status Pesanan jika ada request
if (isset($_POST['update_status'])) {
    $id_pesanan = mysqli_real_escape_string($koneksi, $_POST['id_pesanan']);
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    $update = mysqli_query($koneksi, "UPDATE pesanan SET status = '$status_baru' WHERE id = '$id_pesanan'");
    
    if ($update) {
        echo "<script>alert('Status pesanan berhasil diperbarui!'); window.location.href='pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status!');</script>";
    }
}

// Ambil data pesanan digabung (JOIN) dengan tabel produk untuk mendapatkan nama produk
$query_pesanan = mysqli_query($koneksi, "
    SELECT p.*, pr.nama_produk 
    FROM pesanan p 
    LEFT JOIN produk_layanan pr ON p.id_produk = pr.id 
    ORDER BY p.tanggal_pesan DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - SMAN 13 BEKASI</title>
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
        .ferrari-orange {
            color: #dc0000;
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
        
        /* Ferrari Table */
        .ferrari-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .ferrari-table thead {
            background: linear-gradient(135deg, rgba(220, 0, 0, 0.2), rgba(220, 0, 0, 0.05));
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
        
        /* Status Badge */
        .status-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-family: 'Orbitron', sans-serif;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: inline-block;
        }
        
        .status-pending {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .status-diproses {
            background: rgba(33, 150, 243, 0.15);
            color: #42a5f5;
            border: 1px solid rgba(33, 150, 243, 0.3);
        }
        
        .status-selesai {
            background: rgba(76, 175, 80, 0.15);
            color: #66bb6a;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .status-batal {
            background: rgba(244, 67, 54, 0.15);
            color: #ef5350;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        
        /* Select dropdown */
        .ferrari-select {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(220, 0, 0, 0.2);
            color: #ffffff;
            font-family: 'Rajdhani', sans-serif;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            outline: none;
            cursor: pointer;
        }
        
        .ferrari-select:focus {
            border-color: #dc0000;
            box-shadow: 0 0 20px rgba(220, 0, 0, 0.1);
        }
        
        .ferrari-select option {
            background: #1a1a1a;
            color: #ffffff;
        }
        
        /* Button Save */
        .btn-save-status {
            background: linear-gradient(135deg, #dc0000, #b00000);
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .btn-save-status:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(220, 0, 0, 0.4);
        }
        
        /* Price */
        .price-total {
            color: #ffa500;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 15px;
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
        
        /* Animasi untuk pesanan baru */
        @keyframes fadeInRow {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .ferrari-table tbody tr {
            animation: fadeInRow 0.5s ease forwards;
        }
        
        .ferrari-table tbody tr:nth-child(2) { animation-delay: 0.1s; }
        .ferrari-table tbody tr:nth-child(3) { animation-delay: 0.2s; }
        .ferrari-table tbody tr:nth-child(4) { animation-delay: 0.3s; }
        .ferrari-table tbody tr:nth-child(5) { animation-delay: 0.4s; }
        .ferrari-table tbody tr:nth-child(6) { animation-delay: 0.5s; }
    </style>
</head>
<body class="grid-pattern flex min-h-screen">

    <!-- Memanggil Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Konten Utama -->
    <main class="flex-1 p-8 md:p-12 overflow-y-auto">
        
        <!-- Header -->
        <header class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <span class="inline-block w-2 h-2 ferrari-bg rounded-full pulse-dot"></span>
                <span class="ferrari-orange font-['Orbitron'] text-xs tracking-[0.3em]">KOPERASI SEKOLAH</span>
                <span class="text-[10px] text-gray-600 font-['Orbitron'] tracking-wider border border-orange-500/20 px-3 py-1 rounded-full">
                    <i class="fas fa-circle text-orange-500 text-[6px] mr-1"></i>
                    LIVE
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white">
                Data <span class="ferrari-orange">Pesanan</span>
            </h1>
            <p class="text-gray-400 text-sm mt-1 tracking-wide">Kelola dan pantau seluruh transaksi pemesanan produk</p>
        </header>

        <!-- Tabel Pesanan -->
        <div class="ferrari-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="ferrari-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pembeli & Kelas</th>
                            <th>Produk (Qty)</th>
                            <th>Keterangan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query_pesanan) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($query_pesanan)): ?>
                            <tr>
                                <!-- Tanggal -->
                                <td>
                                    <div class="text-sm font-semibold text-white"><?= date('d M Y', strtotime($row['tanggal_pesan'])); ?></div>
                                    <div class="text-xs text-gray-500 font-['Orbitron'] tracking-wider">
                                        <i class="far fa-clock text-gray-600 mr-1"></i>
                                        <?= date('H:i', strtotime($row['tanggal_pesan'])); ?> WIB
                                    </div>
                                </td>
                                
                                <!-- Pembeli -->
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-500 font-['Orbitron'] text-xs border border-orange-500/30">
                                            <?= strtoupper(substr($row['nama_pembeli'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-white"><?= htmlspecialchars($row['nama_pembeli']); ?></div>
                                            <div class="text-xs text-gray-500 font-['Orbitron'] tracking-wider">
                                                <i class="fas fa-id-card text-gray-600 mr-1"></i>
                                                <?= htmlspecialchars($row['kelas']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Produk -->
                                <td>
                                    <div class="text-sm font-semibold text-white">
                                        <?= htmlspecialchars($row['nama_produk'] ? $row['nama_produk'] : 'Produk Dihapus'); ?>
                                    </div>
                                    <div class="text-xs text-gray-500 font-['Orbitron'] tracking-wider">
                                        <i class="fas fa-box text-gray-600 mr-1"></i>
                                        Qty: <span class="text-orange-500 font-bold"><?= $row['jumlah']; ?></span> item
                                    </div>
                                </td>
                                
                                <!-- Keterangan -->
                                <td>
                                    <div class="text-sm text-gray-400 truncate max-w-xs" title="<?= htmlspecialchars($row['keterangan']); ?>">
                                        <?= $row['keterangan'] ? htmlspecialchars($row['keterangan']) : '<span class="text-gray-600 text-xs font-[\'Orbitron\'] tracking-wider">-</span>'; ?>
                                    </div>
                                </td>
                                
                                <!-- Total Harga -->
                                <td>
                                    <div class="price-total">
                                        <i class="fas fa-tag mr-1" style="font-size: 10px; color: #ffa500;"></i>
                                        Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                                    </div>
                                </td>
                                
                                <!-- Status Badge -->
                                <td>
                                    <?php 
                                        $statusClass = "status-pending";
                                        if ($row['status'] == 'Pending') $statusClass = "status-pending";
                                        if ($row['status'] == 'Diproses') $statusClass = "status-diproses";
                                        if ($row['status'] == 'Selesai') $statusClass = "status-selesai";
                                        if ($row['status'] == 'Batal') $statusClass = "status-batal";
                                    ?>
                                    <span class="status-badge <?= $statusClass; ?>">
                                        <?php if ($row['status'] == 'Pending'): ?>
                                            <i class="fas fa-clock mr-1"></i>
                                        <?php elseif ($row['status'] == 'Diproses'): ?>
                                            <i class="fas fa-spinner fa-spin mr-1"></i>
                                        <?php elseif ($row['status'] == 'Selesai'): ?>
                                            <i class="fas fa-check mr-1"></i>
                                        <?php elseif ($row['status'] == 'Batal'): ?>
                                            <i class="fas fa-times mr-1"></i>
                                        <?php endif; ?>
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                                
                                <!-- Aksi (Ubah Status) -->
                                <td>
                                    <form action="" method="POST" class="flex items-center space-x-2 justify-center">
                                        <input type="hidden" name="id_pesanan" value="<?= $row['id']; ?>">
                                        <select name="status" class="ferrari-select">
                                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                            <option value="Diproses" <?= $row['status'] == 'Diproses' ? 'selected' : ''; ?>>⚙️ Diproses</option>
                                            <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : ''; ?>>✅ Selesai</option>
                                            <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : ''; ?>>❌ Batal</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn-save-status" title="Simpan Status">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-16">
                                    <div class="empty-state">
                                        <i class="fas fa-shopping-cart"></i>
                                        <p>Belum ada pesanan yang masuk</p>
                                        <p class="text-xs text-gray-600 mt-2 font-['Rajdhani'] font-light tracking-wider">
                                            Pesanan akan muncul di sini setelah pelanggan melakukan checkout
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-700 font-['Orbitron'] tracking-[0.2em]">
                SMK KBM 2 ADMIN PANEL
            </p>
            <p class="text-[8px] text-gray-700 font-['Orbitron'] tracking-[0.2em] mt-1">
                Total Pesanan: <?= mysqli_num_rows($query_pesanan); ?> Transaksi
            </p>
        </div>
    </main>

</body>
</html>