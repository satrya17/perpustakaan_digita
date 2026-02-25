<?php 
session_start();
include '../koneksi.php'; 

// Proteksi Halaman
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php?pesan=gagal");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Digital Library</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 20px; background-color: #f8f9fa; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 5px; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; }
        
        /* Navigasi */
        .nav-actions { margin: 20px 0; display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Tombol */
        .btn { padding: 10px 18px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; transition: 0.3s; border: none; cursor: pointer; font-size: 13px; }
        .btn-tambah { background-color: #28a745; color: white; }
        .btn-kategori { background-color: #6f42c1; color: white; }
        .btn-laporan { background-color: #17a2b8; color: white; }
        .btn-konfirmasi { background-color: #ffc107; color: #212529; font-size: 11px; padding: 5px 10px; }
        .btn:hover { opacity: 0.8; transform: translateY(-1px); }
        
        .logout { color: #dc3545; text-decoration: none; font-weight: bold; padding: 8px 15px; border: 1px solid #dc3545; border-radius: 5px; }
        .logout:hover { background: #dc3545; color: white; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: white; margin-bottom: 30px; }
        th, td { padding: 12px; text-align: left; border: 1px solid #dee2e6; }
        th { background-color: #343a40; color: white; font-size: 14px; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        
        .badge-booking { background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; border: 1px solid #ffeeba; }
        .badge-kategori { background: #e9ecef; color: #495057; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; border: 1px solid #ced4da; }
        .btn-edit { color: #007bff; text-decoration: none; font-weight: bold; }
        .btn-hapus { color: #dc3545; text-decoration: none; font-weight: bold; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: bold; }
        
        h3 { border-left: 5px solid #007bff; padding-left: 10px; margin-top: 30px; color: #333; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <div>
            <h1>Dashboard Admin</h1>
            <p>Halo, <strong><?php echo $_SESSION['username']; ?></strong>! Selamat bertugas.</p>
        </div>
        <a href="../logout.php" class="logout" onclick="return confirm('Yakin ingin keluar?')">Keluar</a>
    </div>
    
    <hr>

    <div class="nav-actions">
        <a href="tambah_buku.php" class="btn btn-tambah">➕ Tambah Buku Baru</a>
        <a href="kategori.php" class="btn btn-kategori">📁 Manajemen Kategori</a>
        <a href="laporan.php" class="btn btn-laporan">📊 Cetak Laporan Peminjaman</a>
    </div>

    <?php 
    if(isset($_GET['pesan'])){
        if($_GET['pesan']=="berhasil"){
            echo "<div class='alert' style='background: #d4edda; color: #155724;'>✅ Data berhasil disimpan!</div>";
        } else if($_GET['pesan']=="hapus"){
            echo "<div class='alert' style='background: #f8d7da; color: #721c24;'>🗑️ Data berhasil dihapus!</div>";
        } else if($_GET['pesan']=="konfirmasi"){
            echo "<div class='alert' style='background: #cce5ff; color: #004085;'>📖 Buku telah berhasil diambil oleh peminjam!</div>";
        }
    }
    ?>

    <h3>🕒 Daftar Reservasi (Booking) Fisik</h3>
    <p style="font-size: 13px; color: #666;">Daftar siswa/user yang telah memesan buku secara online dan akan mengambilnya ke perpustakaan.</p>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Booking</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_booking = 1;
            // Mengambil data dengan status 'booking'
            $sql_booking = "SELECT peminjaman.*, user.NamaLengkap, buku.Judul 
                            FROM peminjaman 
                            JOIN user ON peminjaman.UserID = user.UserID 
                            JOIN buku ON peminjaman.BukuID = buku.BukuID 
                            WHERE StatusPeminjaman='booking'
                            ORDER BY TanggalPeminjaman DESC";
            $res_booking = mysqli_query($koneksi, $sql_booking);
            
            if(mysqli_num_rows($res_booking) > 0){
                while($db = mysqli_fetch_array($res_booking)){
                    ?>
                    <tr>
                        <td><?php echo $no_booking++; ?></td>
                        <td><?php echo $db['NamaLengkap']; ?></td>
                        <td><?php echo $db['Judul']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($db['TanggalPeminjaman'])); ?></td>
                        <td><span class="badge-booking">PENDING AMBIL</span></td>
                        <td>
                            <a href="konfirmasi_ambil.php?id=<?php echo $db['PeminjamanID']; ?>" 
                               class="btn btn-konfirmasi" 
                               onclick="return confirm('Konfirmasi bahwa buku fisik telah diserahkan?')">
                               Konfirmasi Ambil
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6' align='center' style='color:#999;'>Tidak ada antrean booking hari ini.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>📚 Daftar Koleksi Buku</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Judul Buku</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $sql = "SELECT buku.*, kategoribuku.NamaKategori 
                    FROM buku 
                    LEFT JOIN kategoribuku_relasi ON buku.BukuID = kategoribuku_relasi.BukuID 
                    LEFT JOIN kategoribuku ON kategoribuku_relasi.KategoriID = kategoribuku.KategoriID";
            $data = mysqli_query($koneksi, $sql);
            while($d = mysqli_fetch_array($data)){
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo $d['Judul']; ?></strong></td>
                    <td>
                        <span class="badge-kategori">
                            <?php echo ($d['NamaKategori'] ? $d['NamaKategori'] : "Tanpa Kategori"); ?>
                        </span>
                    </td>
                    <td><?php echo $d['Penulis']; ?></td>
                    <td><?php echo $d['Penerbit']; ?></td>
                    <td><?php echo $d['TahunTerbit']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $d['BukuID']; ?>" class="btn-edit">Edit</a> | 
                        <a href="hapus.php?id=<?php echo $d['BukuID']; ?>" class="btn-hapus" onclick="return confirm('Hapus buku ini?')">Hapus</a>
                    </td>
                </tr>
                <?php 
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>