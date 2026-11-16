<?php
require 'config.php'; // Pastikan path ini benar mengarah ke config.php

if (isset($_POST['kirim_pesan'])) {
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);

    if (!empty($nama) && !empty($email) && !empty($pesan)) {
        $query = "INSERT INTO contact_messages (nama, email, pesan) VALUES ('$nama', '$email', '$pesan')";
        mysqli_query($koneksi, $query);
        
        // Memunculkan alert sukses menggunakan Javascript lalu melempar kembali ke index.html
        echo "<script>
            alert('Terima kasih! Pesan Anda telah berhasil dikirim.');
            window.location.href = 'index.html';
        </script>";
        exit;
    }
}

// Jika diakses secara langsung tanpa submit, kembalikan ke index
header("Location: index.html");
exit;
?>