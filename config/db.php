<?php
// Konfigurasi database
$host = "localhost";          // Server database (biasanya localhost di XAMPP)
$user = "root";               // Username default MySQL di XAMPP
$pass = "";                   // Password default kosong di XAMPP
$dbname = "silara";           // Nama database yang kamu buat di phpMyAdmin

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// (Opsional) Atur charset untuk mendukung karakter UTF-8
$conn->set_charset("utf8mb4");
?>