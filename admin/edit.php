<?php 
session_start();
include '../koneksi.php';

// Proteksi halaman
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php?pesan=gagal");
    exit();
}

// Mengambil ID buku dari URL
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE BukuID='$id'");
$d = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku - Digital Library</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { background: #f9f9f9; padding: 20px; border-radius: 8px; display: inline-block; }
        td { padding: 5px; }
    </style>
</head>
<body>
    <h2>Edit Data Buku</h2>
    <a href="index.php"> Kembali</a>
    <br><br>

    <form method="post" action="proses_edit.php">
        <input type="hidden" name="BukuID" value="<?php echo $d['BukuID']; ?>">
        
        <table>
            <tr>
                <td>Judul Buku</td>
                <td><input type="text" name="Judul" value="<?php echo $d['Judul']; ?>" required></td>
            </tr>
            <tr>
                <td>Penulis</td>
                <td><input type="text" name="Penulis" value="<?php echo $d['Penulis']; ?>" required></td>
            </tr>
            <tr>
                <td>Penerbit</td>
                <td><input type="text" name="Penerbit" value="<?php echo $d['Penerbit']; ?>" required></td>
            </tr>
            <tr>
                <td>Tahun Terbit</td>
                <td><input type="number" name="TahunTerbit" value="<?php echo $d['TahunTerbit']; ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit">Update Data</button></td>
            </tr>
        </table>
    </form>
</body>
</html>