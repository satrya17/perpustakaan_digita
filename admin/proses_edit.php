<?php 
include '../koneksi.php';

// Menangkap data dari form
$id       = $_POST['BukuID'];
$judul    = $_POST['Judul'];
$penulis  = $_POST['Penulis'];
$penerbit = $_POST['Penerbit'];
$tahun    = $_POST['TahunTerbit'];

// Mengupdate data berdasarkan BukuID
$query = mysqli_query($koneksi, "UPDATE buku SET Judul='$judul', Penulis='$penulis', Penerbit='$penerbit', TahunTerbit='$tahun' WHERE BukuID='$id'");

if($query){
    header("location:index.php?pesan=update_berhasil");
} else {
    echo "Gagal mengupdate data: " . mysqli_error($koneksi);
}
?>