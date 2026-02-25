<?php
session_start();
include '../koneksi.php';

// Pastikan user sudah login
if(!isset($_SESSION['UserID'])){
    header("location:../index.php?pesan=gagal");
    exit();
}

// Ambil ID Buku dari URL dan ID User dari Session
$BukuID = $_GET['id'];
$UserID = $_SESSION['UserID'];
$TanggalBooking = date('Y-m-d');
$Status = "booking"; 

// --- LANGKAH 2: LOGIKA STOK ---

// 1. Cek sisa stok buku di database saat ini
$cek_stok = mysqli_query($koneksi, "SELECT Stok FROM buku WHERE BukuID = '$BukuID'");
$data = mysqli_fetch_array($cek_stok);
$stok_sekarang = $data['Stok'];

// 2. Lakukan validasi, hanya proses jika stok lebih dari 0
if($stok_sekarang > 0) {
    
    // Simpan ke tabel peminjaman
    $query = mysqli_query($koneksi, "INSERT INTO peminjaman (UserID, BukuID, TanggalPeminjaman, StatusPeminjaman) 
                                     VALUES ('$UserID', '$BukuID', '$TanggalBooking', '$Status')");

    if($query){ 
        // 3. Update stok di tabel buku (Kurangi 1)
        mysqli_query($koneksi, "UPDATE buku SET Stok = Stok - 1 WHERE BukuID = '$BukuID'");

        echo "<script>alert('Buku berhasil di-booking! Stok otomatis berkurang. Silahkan ambil ke perpustakaan.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal booking buku.'); window.location='index.php';</script>";
    }

} else {
    // Jika stok 0 atau habis
    echo "<script>alert('Mohon maaf, stok buku ini sedang habis dan tidak bisa di-booking.'); window.location='index.php';</script>";
}
?>