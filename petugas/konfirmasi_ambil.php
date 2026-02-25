<?php 
session_start();
include '../koneksi.php';

// Proteksi: Pastikan hanya petugas/admin yang bisa akses proses ini
if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal");
    exit();
}

// Ambil ID peminjaman dari URL
if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Tentukan tanggal hari ini dan batas kembali (misal 7 hari ke depan)
    $tgl_sekarang = date('Y-m-d');
    $tgl_kembali = date('Y-m-d', strtotime('+7 days'));

    // UPDATE: Ubah status 'booking' menjadi 'dipinjam'
    // Kita juga perbarui TanggalPeminjaman ke hari ini (saat buku fisik diambil)
    // Dan isi TanggalPengembalian sebagai deadline
    $sql = "UPDATE peminjaman SET 
            TanggalPeminjaman = '$tgl_sekarang', 
            TanggalPengembalian = '$tgl_kembali', 
            StatusPeminjaman = 'dipinjam' 
            WHERE PeminjamanID = '$id' AND StatusPeminjaman = 'booking'";

    $query = mysqli_query($koneksi, $sql);

    if($query){
        echo "<script>
                alert('Konfirmasi Berhasil! Status buku kini: Dipinjam.');
                window.location='index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal melakukan konfirmasi. Silahkan coba lagi.');
                window.location='index.php';
              </script>";
    }
} else {
    // Jika tidak ada ID, kembalikan ke dashboard
    header("location:index.php");
}
?>