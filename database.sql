CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keterangan VARCHAR(255) NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produk_layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(255) NOT NULL,
    harga INT NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pesanan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_produk INT(11) NOT NULL,
    nama_pembeli VARCHAR(100) NOT NULL,
    kelas VARCHAR(50) NOT NULL,
    jumlah INT(11) NOT NULL,
    keterangan TEXT,
    total_harga INT(11) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    tanggal_pesan TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Akun Default Admin (Username: admin, Password: password123)
INSERT INTO users (username, password, nama_lengkap) 
VALUES ('admin', '$2y$10$wR2q7aP8WshGleWlQfE9x.V0tX4J10m17G.j3jP1/w7xKk7sV6Z2q', 'Administrator Sekolah');