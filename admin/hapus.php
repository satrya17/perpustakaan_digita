<?php 
include '../koneksi.php';

// Menangkap data id yang dikirim dari url
$id = $_GET['id'];

// Menghapus data dari tabel buku berdasarkan id
$query = mysqli_query($koneksi, "DELETE FROM buku WHERE BukuID='$id'");

if($query){
    // Jika berhasil, alihkan kembali ke index.php
    header("location:index.php?pesan=hapus");
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>