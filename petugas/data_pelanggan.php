<?php
session_start();
include '../koneksi.php';

// Proteksi: Petugas atau Admin
if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal"); exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Data Pelanggan - Petugas</title>
    <style>
        :root{ --bg:#f6f8fb; --card:#fff; --accent:#377dff; --muted:#6b7280; --success:#16a34a; --danger:#ef4444; --radius:12px; --surface-1:#f8fafc }
        *{box-sizing:border-box}
        body{font-family:Inter, 'Segoe UI', system-ui, -apple-system, Roboto, sans-serif; margin:0; background:var(--bg); color:#0f172a;-webkit-font-smoothing:antialiased}
        .box{max-width:1200px;margin:28px auto;padding:20px;background:var(--card);border-radius:var(--radius);box-shadow:0 10px 30px rgba(2,6,23,0.06)}
        h2{margin:0 0 10px 0}
        .controls{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;color:#fff;text-decoration:none;font-weight:700}
        .btn-back{background:#6b7280}
        .btn-search{background:var(--accent)}
        .btn:hover{opacity:0.9}

        .table-responsive{width:100%;overflow:auto;border-radius:8px}
        table{width:100%;border-collapse:collapse;min-width:760px}
        thead th{background:var(--surface-1);padding:12px 14px;text-align:left;font-weight:700;border-bottom:1px solid #eef2f7}
        tbody td{padding:12px 14px;border-bottom:1px solid #f1f5f9}
        tbody tr:nth-child(even){background:#fbfdff}

        .search-box{display:flex;gap:10px;margin-bottom:12px}
        .search-box input{padding:10px;border-radius:8px;border:1px solid #e6eef6;flex:1;max-width:300px}
        .search-box button{padding:10px 14px;background:var(--accent);color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:700}

        @media (max-width:900px){
            .box{margin:12px;padding:14px}
            table{min-width:640px}
        }

        .info-text{font-size:13px;color:var(--muted);margin-bottom:12px}
    </style>
</head>
<body>
<div class="box">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <h2>Kelola Data Pelanggan</h2>
        <a href="index.php" class="btn btn-back">← Kembali ke Dashboard</a>
    </div>

    <p class="info-text">Daftar semua pelanggan (member/peminjam) terdaftar di perpustakaan.</p>

    <?php
    $search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, trim($_GET['search'])) : '';
    
    if($search){
        $sql = "SELECT UserID, Username, NamaLengkap, Email, Alamat FROM user WHERE Level='peminjam' AND (NamaLengkap LIKE '%$search%' OR Username LIKE '%$search%' OR Email LIKE '%$search%') ORDER BY NamaLengkap ASC";
    } else {
        $sql = "SELECT UserID, Username, NamaLengkap, Email, Alamat FROM user WHERE Level='peminjam' ORDER BY NamaLengkap ASC";
    }
    
    $res = mysqli_query($koneksi, $sql);
    ?>

    <div class="search-box">
        <form method="get" style="display:flex;gap:10px;width:100%">
            <input type="text" name="search" placeholder="Cari nama, username, atau email..." value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
            <button type="submit" class="btn btn-search">🔍 Cari</button>
            <?php if($search): ?>
                <a href="data_pelanggan.php" class="btn" style="background:#6b7280">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; if(mysqli_num_rows($res)>0){ while($r=mysqli_fetch_assoc($res)){ ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><strong><?php echo htmlspecialchars($r['Username'], ENT_QUOTES); ?></strong></td>
                <td><?php echo htmlspecialchars($r['NamaLengkap'], ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars($r['Email'], ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars(substr($r['Alamat'], 0, 50), ENT_QUOTES) . (strlen($r['Alamat'])>50?'...':''); ?></td>
            </tr>
            <?php } } else { echo "<tr><td colspan='5' style='text-align:center;color:#666'>Tidak ada pelanggan ditemukan.</td></tr>"; } ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
