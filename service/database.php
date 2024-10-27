<?php 

$hostname = "localhost";          // Host server, biasanya "localhost"
$username = "root";               // Username database
$password = "";                   // Password database (kosong jika default pada XAMPP/LAMP)
$database_name = "bimbel_db";     // Nama database yang akan digunakan

// Membuat koneksi ke database
$db = mysqli_connect($hostname, $username, $password, $database_name);

// Mengecek apakah koneksi berhasil
if (!$db) {
    echo "Koneksi database rusak";
    die("Error: " . mysqli_connect_error());
}

?>
