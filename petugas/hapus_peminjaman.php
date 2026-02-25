<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal"); exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $q = mysqli_query($koneksi, "DELETE FROM peminjaman WHERE PeminjamanID='$id'");
    if($q) header('Location: peminjaman.php?pesan=hapus'); else echo "Gagal menghapus: " . mysqli_error($koneksi);
} else {
    header('Location: peminjaman.php');
}
exit();
