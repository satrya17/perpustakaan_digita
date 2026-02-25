<?php 
session_start();
include '../koneksi.php'; 

// Proteksi Halaman: Hanya Admin yang bisa akses
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori - Digital Library</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); max-width: 900px; margin: auto; }
        
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        h2 { margin: 0; color: #333; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        th, td { border: 1px solid #eee; padding: 15px; text-align: left; }
        th { background: #007bff; color: white; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        tr:hover { background-color: #f9f9f9; }
        
        .btn { padding: 10px 18px; text-decoration: none; border-radius: 6px; color: white; font-size: 14px; display: inline-block; font-weight: bold; transition: 0.3s; }
        .btn-tambah { background: #28a745; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2); }
        .btn-tambah:hover { background: #218838; transform: translateY(-1px); }
        .btn-hapus { background: #dc3545; padding: 6px 12px; font-size: 12px; }
        .btn-hapus:hover { background: #c82333; }
        .btn-kembali { color: #007bff; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-kembali:hover { text-decoration: underline; }
        
        .empty-state { text-align: center; color: #888; padding: 40px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <div>
            <a href="index.php" class="btn-kembali">⬅ Kembali ke Dashboard</a>
            <h2>📁 Manajemen Kategori</h2>
        </div>
        <a href="tambah_kategori.php" class="btn btn-tambah">+ Tambah Kategori Baru</a>
    </div>

    <?php 
    if(isset($_GET['pesan'])){
        if($_GET['pesan']=="berhasil"){
            echo "<div style='background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>Data kategori berhasil disimpan!</div>";
        }
    }
    ?>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Kategori Buku</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Mengambil data kategori
            $data = mysqli_query($koneksi, "SELECT * FROM kategoribuku ORDER BY NamaKategori ASC");
            
            if(mysqli_num_rows($data) > 0){
                while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo $d['NamaKategori']; ?></strong></td>
                        <td>
                            <a href="hapus_kategori.php?id=<?php echo $d['KategoriID']; ?>" 
                               class="btn btn-hapus" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Buku dengan kategori ini mungkin akan terpengaruh.')">
                               🗑 Hapus
                            </a>
                        </td>
                    </tr>
                    <?php 
                }
            } else {
                echo "<tr><td colspan='3' class='empty-state'>Belum ada kategori buku. Klik tombol tambah untuk memulai.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>