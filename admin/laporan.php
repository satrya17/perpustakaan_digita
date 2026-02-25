<?php 
session_start();
include '../koneksi.php'; 

// Proteksi Halaman: Hanya Admin yang bisa akses
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php?pesan=gagal");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perpustakaan Digital</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 15px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 10px; text-align: left; font-size: 13px; }
        th { background-color: #f2f2f2; text-transform: uppercase; }
        
        .no-print { margin-bottom: 30px; display: flex; gap: 10px; }
        .btn { padding: 10px 20px; text-decoration: none; color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .btn-print { background: #28a745; }
        .btn-back { background: #6c757d; }
        
        .footer-ttd { margin-top: 50px; float: right; width: 250px; text-align: center; }
        .space-ttd { height: 80px; }

        /* Pengaturan saat dicetak */
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
            @page { size: landscape; margin: 1.5cm; }
            table { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak ke PDF / Printer</button>
        <a href="index.php" class="btn btn-back">⬅️ Kembali ke Dashboard</a>
    </div>

    <div class="header">
        <h2>Laporan Rekapitulasi Peminjaman Buku</h2>
        <h3>Digital Library System</h3>
        <p>Laporan ini memuat seluruh histori transaksi peminjaman buku oleh member.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            // Query JOIN untuk mengambil data dari 3 tabel sekaligus
            $query = mysqli_query($koneksi, "SELECT peminjaman.*, user.NamaLengkap, buku.Judul 
                                            FROM peminjaman 
                                            INNER JOIN user ON peminjaman.UserID = user.UserID 
                                            INNER JOIN buku ON peminjaman.BukuID = buku.BukuID
                                            ORDER BY TanggalPeminjaman DESC");
            
            if(mysqli_num_rows($query) > 0){
                while($data = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['NamaLengkap']; ?></td>
                        <td><?php echo $data['Judul']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($data['TanggalPeminjaman'])); ?></td>
                        <td>
                            <?php 
                                echo ($data['TanggalPengembalian'] == '0000-00-00' || !$data['TanggalPengembalian']) 
                                ? '-' 
                                : date('d/m/Y', strtotime($data['TanggalPengembalian'])); 
                            ?>
                        </td>
                        <td><strong><?php echo strtoupper($data['StatusPeminjaman']); ?></strong></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data peminjaman.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Dicetak pada: <?php echo date('d F Y'); ?></p>
        <p>Petugas Perpustakaan,</p>
        <div class="space-ttd"></div>
        <p><strong>( <?php echo $_SESSION['username']; ?> )</strong></p>
    </div>

</body>
</html>