<?php 
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$login = mysqli_query($koneksi, "SELECT * FROM user WHERE Username='$username' AND Password='$password'");
$cek = mysqli_num_rows($login);

if($cek > 0){
    $data = mysqli_fetch_assoc($login);

    $_SESSION['username'] = $username;
    $_SESSION['UserID']   = $data['UserID'];
    
    // PERBAIKAN: Gunakan 'Level' (L besar) sesuai nama kolom di database
    $_SESSION['role']     = $data['Level']; 

    // Logika pengalihan menggunakan perbandingan yang benar
    if($data['Level'] == "admin"){
        header("location:admin/index.php");
    } else if($data['Level'] == "petugas"){
        header("location:petugas/index.php");
    } else if($data['Level'] == "peminjam"){
        header("location:peminjam/index.php");
    } else {
        header("location:index.php?pesan=gagal");
    }
} else {
    header("location:index.php?pesan=gagal");
}
?>