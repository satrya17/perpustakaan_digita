<?php 
session_start();
include '../koneksi.php'; 

if(isset($_POST['submit'])){
    $username = $_POST['Username'];
    $password = $_POST['Password']; // Sesuaikan jika pakai md5 atau password_hash
    $email    = $_POST['Email'];
    $nama     = $_POST['NamaLengkap'];
    $alamat   = $_POST['Alamat'];
    $Level    = $_POST['Level']; 

    // Perhatikan bagian ini: Saya HAPUS kolom 'Role' agar tidak error Unknown Column
    $sql = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Level) 
            VALUES ('$username', '$password', '$email', '$nama', '$alamat', '$Level')";
    
    $query = mysqli_query($koneksi, $sql);

    if($query){
        echo "<script>alert('Petugas berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        // Ini akan memunculkan error jika ada nama kolom yang typo lagi
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Petugas</title>
</head>
<body>
    <form method="post">
        <input type="text" name="Username" placeholder="Username" required><br>
        <input type="password" name="Password" placeholder="Password" required><br>
        <input type="email" name="Email" placeholder="Email" required><br>
        <input type="text" name="NamaLengkap" placeholder="Nama Lengkap" required><br>
        <textarea name="Alamat" placeholder="Alamat"></textarea><br>
        <select name="Level">
            <option value="petugas">Petugas</option>
            <option value="admin">Admin</option>
        </select><br>
        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>