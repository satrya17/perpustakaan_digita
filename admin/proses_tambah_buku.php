<?php 
session_start();
include '../koneksi.php';

// Pastikan hanya admin yang bisa memproses
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php?pesan=gagal");
    exit();
}

// Menangkap data dari form menggunakan mysqli_real_escape_string agar aman
$judul     = mysqli_real_escape_string($koneksi, $_POST['Judul']);
$penulis   = mysqli_real_escape_string($koneksi, $_POST['Penulis']);
$penerbit  = mysqli_real_escape_string($koneksi, $_POST['Penerbit']);
$tahun     = mysqli_real_escape_string($koneksi, $_POST['TahunTerbit']);
$stok      = mysqli_real_escape_string($koneksi, $_POST['Stok']);
$kategori  = mysqli_real_escape_string($koneksi, $_POST['Kategori']); // Menangkap teks kategori (Teknologi/Sastra/dll)

/**
 * 1. Simpan langsung ke tabel 'buku'
 * Karena BukuID biasanya Auto Increment, kita kosongkan ('') bagian ID-nya.
 * Pastikan urutan variabel sesuai dengan urutan kolom di phpMyAdmin Anda:
 * (BukuID, Judul, Penulis, Penerbit, TahunTerbit, Stok, Kategori)
 */
$query = "INSERT INTO buku VALUES ('', '$judul', '$penulis', '$penerbit', '$tahun', '$stok', '$kategori')";

if(mysqli_query($koneksi, $query)){
    // Jika berhasil, kembali ke halaman utama admin
    header("location:index.php?pesan=berhasil");
    exit();
} else {
    // Jika gagal, tampilkan pesan error
    echo "Gagal menambahkan data buku: " . mysqli_error($koneksi);
}
?>