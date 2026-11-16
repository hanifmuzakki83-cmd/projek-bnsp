<?php
// Hubungkan ke database
require 'config.php';

// Pastikan form disubmit melalui tombol proses_checkout
if (isset($_POST['proses_checkout'])) {
    
    // Tangkap dan bersihkan data dari form
    $id_produk    = mysqli_real_escape_string($koneksi, $_POST['id_produk']);
    $nama_pembeli = mysqli_real_escape_string($koneksi, $_POST['nama_pembeli']);
    $kelas        = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jumlah       = (int)$_POST['jumlah'];
    $keterangan   = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Validasi dasar
    if (empty($id_produk) || empty($nama_pembeli) || empty($kelas) || $jumlah < 1) {
        echo "<script>
                alert('Pastikan semua form yang wajib telah diisi!');
                window.history.back();
              </script>";
        exit;
    }

    // AMAN: Ambil harga langsung dari database, jangan percaya harga dari HTML/JS
    $query_produk = mysqli_query($koneksi, "SELECT harga FROM produk_layanan WHERE id = '$id_produk'");
    $data_produk  = mysqli_fetch_assoc($query_produk);
    
    if ($data_produk) {
        // Hitung total harga
        $harga_satuan = $data_produk['harga'];
        $total_harga  = $harga_satuan * $jumlah;

        // Masukkan data pesanan ke database
        $query_insert = "INSERT INTO pesanan (id_produk, nama_pembeli, kelas, jumlah, keterangan, total_harga, status) 
                         VALUES ('$id_produk', '$nama_pembeli', '$kelas', '$jumlah', '$keterangan', '$total_harga', 'Pending')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>
                    alert('Pesanan berhasil dibuat! Silakan tunggu panggilan atau konfirmasi dari pihak Koperasi.');
                    window.location.href = 'index.php#products';
                  </script>";
        } else {
            echo "<script>
                    alert('Terjadi kesalahan pada sistem database. Pesanan gagal diproses.');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Produk tidak ditemukan atau sudah dihapus!');
                window.location.href = 'index.php';
              </script>";
    }
} else {
    // Jika ada yang mencoba akses file ini secara langsung via URL
    header("Location: index.php");
    exit;
}
?>