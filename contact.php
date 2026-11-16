<?php
require 'config.php';

$success = '';
$error = '';

// --- PROSES HAPUS DATA PESAN ---
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $delete = mysqli_query($koneksi, "DELETE FROM contact_messages WHERE id = $id");
    
    if ($delete) {
        header("Location: contact.php?pesan=dihapus");
        exit;
    } else {
        $error = "Gagal menghapus pesan.";
    }
}

if (isset($_GET['pesan']) && $_GET['pesan'] == 'dihapus') {
    $success = "Pesan berhasil dihapus.";
}

// --- PENCARIAN & PAGINATION ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$limit = 10; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$where_clause = !empty($search) ? "WHERE nama LIKE '%$search%' OR email LIKE '%$search%' OR pesan LIKE '%$search%'" : "";
$total_query  = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM contact_messages $where_clause");
$total_data   = mysqli_fetch_assoc($total_query)['total'];
$total_pages  = ceil($total_data / $limit);

$query_pesan = mysqli_query($koneksi, "SELECT * FROM contact_messages $where_clause ORDER BY id DESC LIMIT $start, $limit");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Masuk - SMAN 13 BEKASI</title>
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
            padding: 10px 16px;
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
            padding: 10px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 11px;
            cursor: pointer;
        }
        
        .ferrari-btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(220, 0, 0, 0.4);
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
            padding: 16px 20px;
            color: #ccc;
            font-size: 15px;
        }
        
        /* Action Buttons */
        .btn-reply {
            background: transparent;
            border: 1px solid rgba(33, 150, 243, 0.3);
            color: #42a5f5;
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
            justify-content: center;
            gap: 6px;
            width: 100%;
        }
        
        .btn-reply:hover {
            background: rgba(33, 150, 243, 0.15);
            border-color: #42a5f5;
            box-shadow: 0 0 30px rgba(33, 150, 243, 0.2);
            color: #64b5f6;
            transform: scale(1.05);
        }
        
        .btn-delete-message {
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
            justify-content: center;
            gap: 6px;
            width: 100%;
        }
        
        .btn-delete-message:hover {
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
        
        /* Sender info */
        .sender-name {
            font-weight: 700;
            color: #ffffff;
            font-size: 16px;
        }
        
        .sender-email {
            color: #42a5f5;
            font-size: 14px;
            text-decoration: none;
        }
        
        .sender-email:hover {
            text-decoration: underline;
            color: #64b5f6;
        }
        
        .message-date {
            color: #555;
            font-family: 'Orbitron', sans-serif;
            font-size: 9px;
            letter-spacing: 0.05em;
        }
        
        .message-content {
            color: #aaa;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
            max-height: 100px;
            overflow-y: auto;
        }
        
        /* Animasi untuk pesan baru */
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
        .ferrari-table tbody tr:nth-child(7) { animation-delay: 0.6s; }
        .ferrari-table tbody tr:nth-child(8) { animation-delay: 0.7s; }
        .ferrari-table tbody tr:nth-child(9) { animation-delay: 0.8s; }
        .ferrari-table tbody tr:nth-child(10) { animation-delay: 0.9s; }
        
        /* Badge unread */
        .badge-unread {
            background: #dc0000;
            color: white;
            font-size: 8px;
            padding: 2px 8px;
            border-radius: 10px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            animation: pulseBadge 2s ease-in-out infinite;
            margin-left: 8px;
        }
        
        @keyframes pulseBadge {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }
    </style>
</head>
<body class="grid-pattern flex min-h-screen">
    
    <?php include 'sidebar.php'; ?>
    
    <main class="flex-1 p-8 md:p-12 overflow-y-auto">
        
        <!-- Header -->
        <header class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <span class="inline-block w-2 h-2 ferrari-bg rounded-full pulse-dot"></span>
                <span class="ferrari-red font-['Orbitron'] text-xs tracking-[0.3em]">KOMUNIKASI</span>
                <span class="text-[10px] text-gray-600 font-['Orbitron'] tracking-wider border border-red-500/20 px-3 py-1 rounded-full">
                    <i class="fas fa-circle text-red-500 text-[6px] mr-1"></i>
                    LIVE
                </span>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white">
                        Pesan <span class="ferrari-red">Masuk</span>
                    </h1>
                    <p class="text-gray-400 text-sm mt-1 tracking-wide">Kelola pesan dari pengunjung website</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <form action="" method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" 
                               placeholder="Cari nama/email/pesan..." 
                               class="ferrari-input text-sm" style="padding: 10px 16px; min-width: 220px;">
                        <button type="submit" class="ferrari-btn-primary" style="padding: 10px 20px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

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

        <!-- Tabel Pesan -->
        <div class="ferrari-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="ferrari-table">
                    <thead>
                        <tr>
                            <th>Pengirim</th>
                            <th>Isi Pesan</th>
                            <th class="text-center" style="min-width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($query_pesan) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_pesan)): ?>
                            <tr>
                                <td class="align-top">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 font-['Orbitron'] text-sm border border-red-500/30 flex-shrink-0">
                                            <?= strtoupper(substr($row['nama'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="sender-name">
                                                <?= htmlspecialchars($row['nama']); ?>
                                                <span class="badge-unread">NEW</span>
                                            </div>
                                            <a href="mailto:<?= $row['email']; ?>" class="sender-email">
                                                <i class="fas fa-envelope mr-1" style="font-size: 10px;"></i>
                                                <?= htmlspecialchars($row['email']); ?>
                                            </a>
                                            <div class="message-date mt-1">
                                                <i class="far fa-clock mr-1"></i>
                                                <?= date('d M Y, H:i', strtotime($row['created_at'])); ?> WIB
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <div class="message-content">
                                        <?= nl2br(htmlspecialchars($row['pesan'])); ?>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <div class="space-y-2">
                                        <a href="mailto:<?= $row['email']; ?>" class="btn-reply">
                                            <i class="fas fa-reply"></i> Balas Email
                                        </a>
                                        <a href="contact.php?hapus=<?= $row['id']; ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')" 
                                           class="btn-delete-message">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-16">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Belum ada pesan masuk</p>
                                        <p class="text-xs text-gray-600 mt-2 font-['Rajdhani'] font-light tracking-wider">
                                            Pesan dari pengunjung akan muncul di sini
                                        </p>
                                    </div>
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
                    <a href="contact.php?page=<?= $i; ?>&search=<?= $search; ?>" 
                       class="<?= $page == $i ? 'active' : ''; ?>">
                        <?= str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        
        <!-- Footer Info -->
        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-700 font-['Orbitron'] tracking-[0.2em]">
                 SMK KBM 2 BEKASI ADMIN PANEL
            </p>
            <p class="text-[8px] text-gray-700 font-['Orbitron'] tracking-[0.2em] mt-1">
                Total Pesan: <?= $total_data; ?> Pesan
            </p>
        </div>
    </main>

</body>
</html>