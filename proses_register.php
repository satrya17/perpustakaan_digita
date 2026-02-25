<?php 
include 'koneksi.php';

$username = $_POST['Username'];
$password = $_POST['Password']; 
$email    = $_POST['Email'];
$nama     = $_POST['NamaLengkap'];
$alamat   = $_POST['Alamat'];
$level    = $_POST['Level']; 

$cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE Username='$username'");

if(mysqli_num_rows($cek_user) > 0) {
    echo "<script>alert('Username sudah digunakan!'); window.location='register.php';</script>";
} else {
    // PERBAIKAN: Hapus kolom 'Role' karena di database Anda hanya ada 'Level'
    $query = mysqli_query($koneksi, "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Level) 
                                     VALUES ('$username', '$password', '$email', '$nama', '$alamat', '$level')");

    if($query){
        echo "<script>alert('Registrasi Berhasil!'); window.location='index.php';</script>";
    } else {
        echo "Error Database: " . mysqli_error($koneksi);
    }
}
?>