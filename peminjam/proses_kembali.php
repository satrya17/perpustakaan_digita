<?php
include '../koneksi.php';

$id = $_GET['id'];
$tgl_sekarang = date('Y-m-d');

// Update status menjadi 'Dikembalikan' dan set tanggal pengembalian ke hari ini
$query = mysqli_query($koneksi, "UPDATE peminjaman SET 
    StatusPeminjaman = 'Dikembalikan', 
    TanggalPengembalian = '$tgl_sekarang' 
    WHERE PeminjamanID = '$id'");

if($query){
    echo "<script>alert('Buku berhasil dikembalikan!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal mengembalikan buku.'); window.location='index.php';</script>";
}
?>