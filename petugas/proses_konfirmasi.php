<?php 
include '../koneksi.php';

$id = $_GET['id'];
$tgl_sekarang = date('Y-m-d');
$tgl_kembali = date('Y-m-d', strtotime('+7 days')); // Batas pinjam 7 hari

// Update status dari Booking menjadi Dipinjam
$query = mysqli_query($koneksi, "UPDATE peminjaman SET 
    TanggalPeminjaman = '$tgl_sekarang',
    TanggalPengembalian = '$tgl_kembali',
    StatusPeminjaman = 'Dipinjam' 
    WHERE PeminjamanID = '$id'");

if($query){
    echo "<script>
            alert('Buku berhasil diserahkan! Status berubah menjadi Dipinjam.');
            window.location='index.php'; 
          </script>";
} else {
    echo "<script>
            alert('Gagal konfirmasi.');
            window.location='index.php';
          </script>";
}
?>