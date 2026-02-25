<?php 
session_start();
include '../koneksi.php';

// POINT 3: Proteksi Session - Hanya Petugas atau Admin yang bisa menjalankan file ini
if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal");
    exit();
}

// Pastikan ada ID yang dikirim
if(isset($_GET['id'])){
    $id = $_GET['id']; // Ini adalah PeminjamanID
    $tgl_sekarang = date('Y-m-d');

    // --- LANGKAH 3: LOGIKA PENGEMBALIAN STOK ---

    // 1. Ambil BukuID dari data peminjaman sebelum status diupdate
    $cari_buku = mysqli_query($koneksi, "SELECT BukuID FROM peminjaman WHERE PeminjamanID = '$id'");
    $data_buku = mysqli_fetch_array($cari_buku);
    $BukuID = $data_buku['BukuID'];

    // 2. Update status peminjaman menjadi 'kembali'
    $query = mysqli_query($koneksi, "UPDATE peminjaman SET 
        TanggalPengembalian = '$tgl_sekarang', 
        StatusPeminjaman = 'kembali' 
        WHERE PeminjamanID = '$id'");

    if($query){
        // 3. TAMBAHKAN STOK BUKU KEMBALI (+1)
        mysqli_query($koneksi, "UPDATE buku SET Stok = Stok + 1 WHERE BukuID = '$BukuID'");

        echo "<script>
                alert('Berhasil! Buku telah diterima kembali dan stok telah diperbarui.');
                window.location='index.php?pesan=berhasil_kembali';
              </script>";
    } else {
        echo "Gagal memproses pengembalian: " . mysqli_error($koneksi);
    }
} else {
    header("location:index.php");
}
?>