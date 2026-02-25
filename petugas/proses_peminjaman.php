<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal"); exit();
}

$action = $_POST['action'] ?? '';
if($action == 'add'){
    $UserID = isset($_POST['UserID']) ? intval($_POST['UserID']) : 0;
    $BukuID = intval($_POST['BukuID']);
    $TanggalPeminjaman = mysqli_real_escape_string($koneksi, $_POST['TanggalPeminjaman']);
    $TanggalPengembalian = !empty($_POST['TanggalPengembalian']) ? mysqli_real_escape_string($koneksi, $_POST['TanggalPengembalian']) : null;
    $Status = mysqli_real_escape_string($koneksi, $_POST['StatusPeminjaman']);

    // Jika UserID kosong, coba resolve dari UserName (exact). Jika tidak ada dan pengguna memilih pembuatan, buat akun baru.
    if(empty($UserID) && !empty($_POST['UserName'])){
        $uname = trim($_POST['UserName']);
        $uname_esc = mysqli_real_escape_string($koneksi, $uname);
        // coba exact case-insensitive
        $resu = mysqli_query($koneksi, "SELECT UserID FROM user WHERE LOWER(TRIM(NamaLengkap)) = LOWER(TRIM('$uname_esc')) LIMIT 1");
        if(mysqli_num_rows($resu) > 0){
            $ur = mysqli_fetch_assoc($resu);
            $UserID = intval($ur['UserID']);
        } else {
            // tidak ditemukan exact — buat user baru otomatis
            $base = preg_replace('/[^a-z0-9]/', '', strtolower($uname));
            if($base == '') $base = 'user';
            $username = '';
            for($i=0;$i<10;$i++){
                $try = $base . rand(1000,9999);
                $chk = mysqli_query($koneksi, "SELECT 1 FROM user WHERE Username='".mysqli_real_escape_string($koneksi,$try)."' LIMIT 1");
                if(mysqli_num_rows($chk) == 0){ $username = $try; break; }
            }
            if($username == ''){ echo "Gagal menyimpan: Tidak dapat membuat username unik."; exit(); }
            $password = 'p@ss'.rand(1000,9999);
            $username_esc = mysqli_real_escape_string($koneksi, $username);
            $password_esc = mysqli_real_escape_string($koneksi, $password);
            $email_esc = '';
            $alamat_esc = '';
            $level = 'peminjam';
            $qins = mysqli_query($koneksi, "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Level) VALUES ('{$username_esc}','{$password_esc}','{$email_esc}','{$uname_esc}','{$alamat_esc}','{$level}')");
            if($qins){
                $UserID = intval(mysqli_insert_id($koneksi));
            } else {
                echo "Gagal menyimpan: error membuat user - " . mysqli_error($koneksi); exit();
            }
        }
    }

    if(empty($UserID)){
        echo "Gagal menyimpan: User tidak valid. Pilih peminjam atau buat pengguna baru."; exit();
    }

    $tglKembaliSQL = $TanggalPengembalian ? "'".$TanggalPengembalian."'" : "NULL";
    $sql = "INSERT INTO peminjaman (UserID,BukuID,TanggalPeminjaman,TanggalPengembalian,StatusPeminjaman) VALUES ('{$UserID}','{$BukuID}','{$TanggalPeminjaman}', $tglKembaliSQL ,'{$Status}')";
    $q = mysqli_query($koneksi, $sql);
    if($q) header('Location: peminjaman.php?pesan=berhasil'); else echo "Gagal menyimpan: " . mysqli_error($koneksi);
    exit();
}

if($action == 'edit'){
    $id = intval($_POST['PeminjamanID']);
    $UserID = isset($_POST['UserID']) ? intval($_POST['UserID']) : 0;
    $BukuID = intval($_POST['BukuID']);
    $TanggalPeminjaman = mysqli_real_escape_string($koneksi, $_POST['TanggalPeminjaman']);
    $TanggalPengembalian = !empty($_POST['TanggalPengembalian']) ? mysqli_real_escape_string($koneksi, $_POST['TanggalPengembalian']) : null;
    $Status = mysqli_real_escape_string($koneksi, $_POST['StatusPeminjaman']);

    if(empty($UserID) && !empty($_POST['UserName'])){
        $uname = trim($_POST['UserName']);
        $uname_esc = mysqli_real_escape_string($koneksi, $uname);
        $resu = mysqli_query($koneksi, "SELECT UserID FROM user WHERE LOWER(TRIM(NamaLengkap)) = LOWER(TRIM('$uname_esc')) LIMIT 1");
        if(mysqli_num_rows($resu) > 0){
            $ur = mysqli_fetch_assoc($resu);
            $UserID = intval($ur['UserID']);
        } else {
            // tidak ditemukan exact — buat user baru otomatis
            $base = preg_replace('/[^a-z0-9]/', '', strtolower($uname));
            if($base == '') $base = 'user';
            $username = '';
            for($i=0;$i<10;$i++){
                $try = $base . rand(1000,9999);
                $chk = mysqli_query($koneksi, "SELECT 1 FROM user WHERE Username='".mysqli_real_escape_string($koneksi,$try)."' LIMIT 1");
                if(mysqli_num_rows($chk) == 0){ $username = $try; break; }
            }
            if($username == ''){ echo "Gagal update: Tidak dapat membuat username unik."; exit(); }
            $password = 'p@ss'.rand(1000,9999);
            $username_esc = mysqli_real_escape_string($koneksi, $username);
            $password_esc = mysqli_real_escape_string($koneksi, $password);
            $email_esc = '';
            $alamat_esc = '';
            $level = 'peminjam';
            $qins = mysqli_query($koneksi, "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Level) VALUES ('{$username_esc}','{$password_esc}','{$email_esc}','{$uname_esc}','{$alamat_esc}','{$level}')");
            if($qins){
                $UserID = intval(mysqli_insert_id($koneksi));
            } else {
                echo "Gagal update: error membuat user - " . mysqli_error($koneksi); exit();
            }
        }
    }

    if(empty($UserID)){
        echo "Gagal update: User tidak valid. Pilih peminjam atau buat pengguna baru."; exit();
    }

    $tglKembaliSQL = $TanggalPengembalian ? "'".$TanggalPengembalian."'" : "NULL";
    $sql = "UPDATE peminjaman SET UserID='{$UserID}', BukuID='{$BukuID}', TanggalPeminjaman='{$TanggalPeminjaman}', TanggalPengembalian=$tglKembaliSQL, StatusPeminjaman='{$Status}' WHERE PeminjamanID='{$id}'";
    $q = mysqli_query($koneksi, $sql);
    if($q) header('Location: peminjaman.php?pesan=berhasil'); else echo "Gagal update: " . mysqli_error($koneksi);
    exit();
}

header('Location: peminjaman.php');
exit();
