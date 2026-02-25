<?php 
include '../koneksi.php';

$NamaKategori = mysqli_real_escape_string($koneksi, $_POST['NamaKategori']);

$query = mysqli_query($koneksi, "INSERT INTO kategoribuku (NamaKategori) VALUES ('$NamaKategori')");

if($query){
    // Setelah simpan, balik ke daftar kategori
    header("location:kategori.php?pesan=sukses");
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>