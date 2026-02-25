<?php
session_start();
include '../koneksi.php';

$BukuID = $_GET['id'];
$UserID = $_SESSION['UserID'];
$TglPinjam = date('Y-m-d');
$TglKembali = date('Y-m-d', strtotime('+7 days')); // Otomatis 7 hari

$query = mysqli_query($koneksi, "INSERT INTO peminjaman (UserID, BukuID, TanggalPeminjaman, TanggalPengembalian, StatusPeminjaman) 
                                 VALUES ('$UserID', '$BukuID', '$TglPinjam', '$TglKembali', 'Dipinjam')");

if($query){
    echo "<script>alert('Berhasil Meminjam! Silahkan baca di dashboard.'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal meminjam.'); window.location='index.php';</script>";
}
?>