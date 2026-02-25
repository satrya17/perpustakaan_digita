<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php?pesan=gagal"); exit();
}

$action = $_POST['action'] ?? '';

if($action == 'add'){
    $username = mysqli_real_escape_string($koneksi, trim($_POST['Username']));
    $password = mysqli_real_escape_string($koneksi, trim($_POST['Password']));
    $email = mysqli_real_escape_string($koneksi, trim($_POST['Email']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['NamaLengkap']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['Alamat']));
    $level = 'peminjam';

    // Check if username exists
    $cek = mysqli_query($koneksi, "SELECT 1 FROM user WHERE Username='$username' LIMIT 1");
    if(mysqli_num_rows($cek) > 0){
        echo "Gagal menyimpan: Username sudah digunakan. Gunakan username lain."; exit();
    }

    $sql = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Level) 
            VALUES ('$username', '$password', '$email', '$nama', '$alamat', '$level')";
    $q = mysqli_query($koneksi, $sql);
    if($q){
        header('Location: user.php?pesan=berhasil');
    } else {
        echo "Gagal menyimpan: " . mysqli_error($koneksi);
    }
    exit();
}

if($action == 'edit'){
    $id = intval($_POST['UserID']);
    $username = mysqli_real_escape_string($koneksi, trim($_POST['Username']));
    $password = trim($_POST['Password']);
    $email = mysqli_real_escape_string($koneksi, trim($_POST['Email']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['NamaLengkap']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['Alamat']));

    // Check if username is already used by someone else
    $cek = mysqli_query($koneksi, "SELECT 1 FROM user WHERE Username='$username' AND UserID != '$id' LIMIT 1");
    if(mysqli_num_rows($cek) > 0){
        echo "Gagal update: Username sudah digunakan. Gunakan username lain."; exit();
    }

    if(!empty($password)){
        $password_esc = mysqli_real_escape_string($koneksi, $password);
        $sql = "UPDATE user SET Username='$username', Password='$password_esc', Email='$email', NamaLengkap='$nama', Alamat='$alamat' WHERE UserID='$id' AND Level='peminjam'";
    } else {
        $sql = "UPDATE user SET Username='$username', Email='$email', NamaLengkap='$nama', Alamat='$alamat' WHERE UserID='$id' AND Level='peminjam'";
    }

    $q = mysqli_query($koneksi, $sql);
    if($q){
        header('Location: user.php?pesan=berhasil');
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
    exit();
}

header('Location: user.php');
exit();
?>
