<?php
require 'config.php';

// Username dan Password baru yang ingin kita buat
$username_baru = "admin";
$password_baru = "admin123"; // Silakan ganti sesuai keinginan Anda

// Mengaktifkan enkripsi password_hash() yang 100% valid untuk sistem PHP Anda
$password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

// Hapus user 'admin' lama terlebih dahulu agar tidak duplikat
mysqli_query($koneksi, "DELETE FROM users WHERE username = '$username_baru'");

// Masukkan data admin baru dengan hash yang baru dibuat
$query = "INSERT INTO users (username, password, nama_lengkap) VALUES ('$username_baru', '$password_hashed', 'Administrator Sekolah')";
$eksekusi = mysqli_query($koneksi, $query);

if ($eksekusi) {
    echo "<div style='font-family:sans-serif; padding:20px; background:#d4edda; color:#155724; border-radius:5px; margin:20px;'>";
    echo "<h3>✅ BERHASIL MERESET DATA ADMIN!</h3>";
    echo "Akun admin baru Anda telah berhasil disuntikkan ke database.<br><br>";
    echo "<b>Username:</b> " . $username_baru . "<br>";
    echo "<b>Password:</b> " . $password_baru . "<br><br>";
    echo "<a href='login.php' style='background:#155724; color:white; padding:8px 15px; text-decoration:none; border-radius:3px; display:inline-block;'>Pergi ke Halaman Login</a>";
    echo "</div>";
} else {
    echo "Gagal memperbarui database: " . mysqli_error($koneksi);
}
?>