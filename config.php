<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "databaset";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Jalankan session secara global
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>