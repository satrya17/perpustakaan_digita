<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php?pesan=gagal"); exit();
}

if(!isset($_GET['id'])){
    header('Location: petugas.php'); exit();
}

$id = intval($_GET['id']);

// Verify user exists and is petugas/admin
$q = mysqli_query($koneksi, "SELECT 1 FROM user WHERE UserID='$id' AND Level IN ('petugas','admin') LIMIT 1");
if(mysqli_num_rows($q) == 0){
    header('Location: petugas.php'); exit();
}

// Delete
$sql = "DELETE FROM user WHERE UserID='$id'";
$del = mysqli_query($koneksi, $sql);
if($del){
    header('Location: petugas.php?pesan=hapus');
} else {
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}
exit();
?>
