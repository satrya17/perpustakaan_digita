<?php
include '../koneksi.php';

$ID = $_GET['id'];
$Tgl = date('Y-m-d');

// Update status dari booking menjadi dipinjam
$query = mysqli_query($koneksi, "UPDATE peminjaman SET 
                                 StatusPeminjaman='dipinjam', 
                                 TanggalPeminjaman='$Tgl' 
                                 WHERE PeminjamanID='$ID'");

if($query){
    header("location:index.php?pesan=konfirmasi");
} else {
    echo "Gagal mengkonfirmasi.";
}
?>