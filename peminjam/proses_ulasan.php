<?php 
session_start();
include '../koneksi.php';

// Proteksi: Pastikan hanya user yang sudah login dan melalui form yang bisa mengakses file ini
if (!isset($_SESSION['UserID']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("location:index.php");
    exit();
}

// Menangkap data dari form tambah_ulasan.php
$UserID = $_SESSION['UserID'];
$BukuID = mysqli_real_escape_string($koneksi, $_POST['BukuID']);
$Ulasan = mysqli_real_escape_string($koneksi, $_POST['Ulasan']);
$Rating = mysqli_real_escape_string($koneksi, $_POST['Rating']);

// Query INSERT untuk memasukkan ulasan ke dalam tabel database
// Pastikan nama tabel Anda di database adalah 'ulasanbuku' (tanpa spasi/underscore)
$sql = "INSERT INTO ulasanbuku (UserID, BukuID, Ulasan, Rating) 
        VALUES ('$UserID', '$BukuID', '$Ulasan', '$Rating')";

$query = mysqli_query($koneksi, $sql);

if($query){
    // Jika berhasil, arahkan kembali ke dashboard dengan pesan sukses
    header("location:index.php?pesan=sukses_ulasan");
    exit();
} else {
    // Jika gagal, tampilkan pesan error dari database untuk memudahkan pengecekan
    echo "<h3>Gagal Menyimpan Ulasan</h3>";
    echo "Pesan Error: " . mysqli_error($koneksi);
    echo "<br><a href='index.php'>Kembali ke Dashboard</a>";
}
?>