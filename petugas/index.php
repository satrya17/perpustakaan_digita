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
        :root{
            --bg: #f6f8fb;
            --card: #ffffff;
            --muted: #6c757d;
            --accent: #377dff;
            --success: #16a34a;
            --danger: #ef4444;
            --warning: #f59e0b;
            --glass: rgba(2,6,23,0.06);
            --radius: 12px;
            --surface-1: #f8fafc;
        }
        *{box-sizing:border-box}
        body{font-family:Inter, 'Segoe UI', Roboto, system-ui, -apple-system, sans-serif; margin:0; background:var(--bg); color:#0f172a;-webkit-font-smoothing:antialiased}
        .container{max-width:1100px;margin:28px auto;padding:24px;background:var(--card);border-radius:var(--radius);box-shadow:0 8px 30px var(--glass)}

        /* Header */
        .header{display:flex;align-items:center;justify-content:space-between;gap:12px}
        .header h1{margin:0;font-size:20px}
        .meta{color:var(--muted);font-size:14px}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;border:0;font-weight:600;cursor:pointer;text-decoration:none;color:#fff}
        .btn-primary{background:var(--accent)}
        .btn-outline{background:transparent;color:var(--accent);border:1px solid rgba(55,125,255,0.12)}
        .btn-ambil{background:var(--warning);color:#111}
        .btn-kembali{background:var(--accent)}
        .btn-pelanggan{background:#4361ee}
        .btn-success{background:var(--success)}
        .btn-warning{background:var(--warning);color:#111}
        .btn-danger{background:var(--danger)}
        .logout{background:transparent;color:var(--danger);border:1px solid rgba(239,68,68,0.12);padding:8px 12px;border-radius:8px;text-decoration:none}

        /* Sections */
        .section{margin-top:20px}
        .section h3{margin:0 0 12px 0;font-size:16px;color:#0f172a;padding-left:12px;border-left:5px solid transparent}
        .card{background:linear-gradient(180deg,rgba(255,255,255,0.6),#fff);padding:16px;border-radius:10px;margin-top:12px}

        /* Table responsive */
        .table-responsive{width:100%;overflow:auto;border-radius:8px}
        table{width:100%;border-collapse:collapse;min-width:720px}
        thead th{background:var(--surface-1);padding:12px 14px;text-align:left;font-weight:700;border-bottom:1px solid #eef2f7}
        tbody td{padding:12px 14px;border-bottom:1px solid #f1f5f9}
        tbody tr:nth-child(even){background:#fbfdff}
        .table-actions a{margin-right:8px}

        /* badges */
        .badge{display:inline-flex;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px}
        .badge-booking{background:#fff7e6;color:#855700}
        .badge-pinjam{background:#e8f8ff;color:#055160}
        .badge-selesai{background:#e6f4ea;color:#135e2b}
        .alert{padding:10px 12px;border-radius:8px;background:#e6ffed;color:#064e2a;margin-top:10px;border:1px solid #d1f7dc}

        @media (max-width:900px){
            .container{margin:12px;padding:16px}
            .header{flex-direction:column;align-items:flex-start;gap:8px}
            .table-responsive{min-width:0}
            table{min-width:640px}
        }

        @media (max-width:520px){
            table{min-width:480px}
            .btn{padding:8px 10px;font-size:13px}
        }
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
        <a href="peminjaman.php" class="btn" style="background:#007bff;color:#fff;margin-left:8px;">🔁 Manajemen Peminjaman</a>
    </div>

    <h3 style="border-left-color: #ffc107;">🕒 1. Antrean Booking (Belum Diambil)</h3>
    <div class="table-responsive">
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
    </div>

    <h3 style="border-left-color: #007bff;">📖 2. Sedang Dipinjam (Buku di Siswa)</h3>
    <div class="table-responsive">
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
    </div>

    <h3 style="border-left-color: #28a745;">✅ 3. Riwayat Selesai</h3>
    <div class="table-responsive">
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
</div>

</body>
</html>