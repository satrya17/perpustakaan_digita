<?php 
session_start();
include '../koneksi.php'; 

// Proteksi: Hanya Petugas (atau Admin) yang boleh masuk
if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Petugas - Digital Library</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 20px; background-color: #f0f2f5; color: #333; }
        .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; }
        h3 { margin-top: 30px; border-left: 5px solid #007bff; padding-left: 15px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; }
        th, td { padding: 12px; text-align: left; border: 1px solid #dee2e6; font-size: 14px; }
        th { background-color: #6c757d; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
        .bg-booking { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .bg-pinjam { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .bg-selesai { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        .btn { padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; transition: 0.2s; display: inline-block; border: none; cursor: pointer; }
        .btn-ambil { background-color: #ffc107; color: #212529; }
        .btn-kembali { background-color: #007bff; color: white; }
        .btn-pelanggan { background-color: #4361ee; color: white; margin-bottom: 20px; }
        .btn:hover { opacity: 0.8; transform: translateY(-1px); }
        
        .logout { float: right; color: #dc3545; text-decoration: none; font-weight: bold; border: 1px solid #dc3545; padding: 8px 15px; border-radius: 5px; }
        .logout:hover { background: #dc3545; color: white; }
        
        .alert { padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="container">
    <a href="../logout.php" class="logout" onclick="return confirm('Yakin ingin keluar?')">Log Out</a>
    <h1>Dashboard Petugas</h1>
    <p>Selamat bekerja, Petugas: <strong><?php echo $_SESSION['username']; ?></strong></p>
    <hr>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil_kembali'): ?>
        <div class="alert">✅ Berhasil memproses pengembalian buku!</div>
    <?php endif; ?>

    <div style="margin: 20px 0;">
        <a href="data_pelanggan.php" class="btn btn-pelanggan">👥 Kelola Data Pelanggan</a>
    </div>

    <h3 style="border-left-color: #ffc107;">🕒 1. Antrean Booking (Belum Diambil)</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Booking</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_b = 1;
            $sql_b = "SELECT peminjaman.*, user.NamaLengkap, buku.Judul 
                      FROM peminjaman 
                      JOIN user ON peminjaman.UserID = user.UserID 
                      JOIN buku ON peminjaman.BukuID = buku.BukuID 
                      WHERE StatusPeminjaman = 'booking'";
            $query_b = mysqli_query($koneksi, $sql_b);
            if(mysqli_num_rows($query_b) > 0){
                while($db = mysqli_fetch_array($query_b)){
            ?>
                <tr>
                    <td><?= $no_b++; ?></td>
                    <td><?= $db['NamaLengkap']; ?></td>
                    <td><?= $db['Judul']; ?></td>
                    <td><?= date('d/m/Y', strtotime($db['TanggalPeminjaman'])); ?></td>
                    <td>
                        <a href="konfirmasi_ambil.php?id=<?= $db['PeminjamanID']; ?>" class="btn btn-ambil" onclick="return confirm('Konfirmasi penyerahan buku?')">✅ Konfirmasi Ambil</a>
                    </td>
                </tr>
            <?php } } else { echo "<tr><td colspan='5' align='center' style='color:#999;'>Tidak ada antrean booking.</td></tr>"; } ?>
        </tbody>
    </table>

    <h3 style="border-left-color: #007bff;">📖 2. Sedang Dipinjam (Buku di Siswa)</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_p = 1;
            $sql_p = "SELECT peminjaman.*, user.NamaLengkap, buku.Judul 
                      FROM peminjaman 
                      JOIN user ON peminjaman.UserID = user.UserID 
                      JOIN buku ON peminjaman.BukuID = buku.BukuID 
                      WHERE StatusPeminjaman = 'dipinjam'";
            $query_p = mysqli_query($koneksi, $sql_p);
            if(mysqli_num_rows($query_p) > 0){
                while($dp = mysqli_fetch_array($query_p)){
            ?>
                <tr>
                    <td><?= $no_p++; ?></td>
                    <td><?= $dp['NamaLengkap']; ?></td>
                    <td><?= $dp['Judul']; ?></td>
                    <td><?= date('d/m/Y', strtotime($dp['TanggalPeminjaman'])); ?></td>
                    <td><?= ($dp['TanggalPengembalian'] != '0000-00-00') ? date('d/m/Y', strtotime($dp['TanggalPengembalian'])) : '-'; ?></td>
                    <td>
                        <a href="proses_kembali.php?id=<?= $dp['PeminjamanID']; ?>" class="btn btn-kembali" onclick="return confirm('Buku sudah diterima kembali?')">🔄 Set Kembali</a>
                    </td>
                </tr>
            <?php } } else { echo "<tr><td colspan='6' align='center' style='color:#999;'>Tidak ada buku yang sedang dipinjam.</td></tr>"; } ?>
        </tbody>
    </table>

    <h3 style="border-left-color: #28a745;">✅ 3. Riwayat Selesai</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Kembali Asli</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_s = 1;
            $sql_s = "SELECT peminjaman.*, user.NamaLengkap, buku.Judul 
                      FROM peminjaman 
                      JOIN user ON peminjaman.UserID = user.UserID 
                      JOIN buku ON peminjaman.BukuID = buku.BukuID 
                      WHERE StatusPeminjaman = 'kembali' ORDER BY PeminjamanID DESC LIMIT 5";
            $query_s = mysqli_query($koneksi, $sql_s);
            while($ds = mysqli_fetch_array($query_s)){
            ?>
                <tr>
                    <td><?= $no_s++; ?></td>
                    <td><?= $ds['NamaLengkap']; ?></td>
                    <td><?= $ds['Judul']; ?></td>
                    <td><?= date('d/m/Y', strtotime($ds['TanggalPengembalian'])); ?></td>
                    <td><span class="badge bg-selesai">Sudah Kembali</span></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>