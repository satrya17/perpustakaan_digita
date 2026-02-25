<?php
session_start();
include '../koneksi.php';

// Proteksi: Admin saja
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../index.php?pesan=gagal"); exit();
}

$pesan = $_GET['pesan'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Manajemen User (Peminjam) - Admin</title>
    <style>
        :root{ --bg:#f6f8fb; --card:#fff; --accent:#377dff; --muted:#6b7280; --success:#16a34a; --danger:#ef4444; --warning:#f59e0b; --radius:12px; --surface-1:#f8fafc }
        *{box-sizing:border-box}
        body{font-family:Inter, 'Segoe UI', system-ui, -apple-system, Roboto, sans-serif; margin:0; background:var(--bg); color:#0f172a;-webkit-font-smoothing:antialiased}
        .box{max-width:1200px;margin:28px auto;padding:20px;background:var(--card);border-radius:var(--radius);box-shadow:0 10px 30px rgba(2,6,23,0.06)}
        h2{margin:0 0 10px 0}
        .controls{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;color:#fff;text-decoration:none;font-weight:700}
        .btn-tambah{background:var(--success)}
        .btn-edit{background:var(--accent)}
        .btn-hapus{background:var(--danger)}
        .btn-back{background:#6b7280}
        .btn:hover{opacity:0.9;transform:translateY(-2px)}

        .table-responsive{width:100%;overflow:auto;border-radius:8px}
        table{width:100%;border-collapse:collapse;min-width:760px}
        thead th{background:var(--surface-1);padding:12px 14px;text-align:left;font-weight:700;border-bottom:1px solid #eef2f7}
        tbody td{padding:12px 14px;border-bottom:1px solid #f1f5f9}
        tbody tr:nth-child(even){background:#fbfdff}

        label{display:block;margin-top:10px;font-weight:600;color:var(--muted)}
        input,select,textarea{padding:10px;border-radius:8px;border:1px solid #e6eef6;width:100%;margin-top:6px;font-family:inherit}
        textarea{resize:vertical}

        .form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-top:8px}
        .form-grid .field{display:flex;flex-direction:column}
        .form-grid .full{grid-column:1/3}

        @media (max-width:720px){
            .form-grid{grid-template-columns:1fr}
            .form-grid .full{grid-column:1/2}
        }

        @media (max-width:900px){
            .box{margin:12px;padding:14px}
            table{min-width:640px}
        }

        .alert{padding:12px;margin-bottom:12px;border-radius:8px}
        .alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
        .alert-danger{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
    </style>
</head>
<body>
<div class="box">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <h2>Manajemen User (Peminjam)</h2>
        <a href="index.php" class="btn btn-back">← Kembali ke Dashboard</a>
    </div>

    <?php if($pesan=='berhasil'){ echo "<div class='alert alert-success'>✅ Operasi berhasil.</div>"; }
          if($pesan=='hapus'){ echo "<div class='alert alert-danger'>🗑️ User dihapus.</div>"; }
    ?>

    <?php
    if(isset($_GET['action']) && $_GET['action']=='add'){
        ?>
        <form method="post" action="proses_user.php">
            <input type="hidden" name="action" value="add">
            <div class="form-grid">
                <div class="field">
                    <label>Username</label>
                    <input type="text" name="Username" placeholder="username unik" required>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="Password" placeholder="password" required>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="Email" placeholder="email@example.com" required>
                </div>
                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="NamaLengkap" placeholder="nama lengkap" required>
                </div>
                <div class="field full">
                    <label>Alamat</label>
                    <textarea name="Alamat" placeholder="alamat lengkap" rows="3"></textarea>
                </div>
                <div class="full" style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
                    <button type="submit" class="btn btn-tambah">Simpan User</button>
                    <a href="user.php" class="btn btn-back">Batal</a>
                </div>
            </div>
        </form>
        <?php exit(); }

    if(isset($_GET['action']) && $_GET['action']=='edit' && isset($_GET['id'])){
        $id = intval($_GET['id']);
        $q = mysqli_query($koneksi, "SELECT * FROM user WHERE UserID='$id' AND Level='peminjam'");
        if(mysqli_num_rows($q)==0){ echo "<div class='alert alert-danger'>User tidak ditemukan.</div>"; exit(); }
        $row = mysqli_fetch_assoc($q);
        ?>
        <form method="post" action="proses_user.php">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="UserID" value="<?php echo $row['UserID']; ?>">
            <div class="form-grid">
                <div class="field">
                    <label>Username</label>
                    <input type="text" name="Username" value="<?php echo htmlspecialchars($row['Username'], ENT_QUOTES); ?>" required>
                </div>
                <div class="field">
                    <label>Password (kosongkan untuk tidak mengubah)</label>
                    <input type="password" name="Password" placeholder="biarkan kosong jika tidak diubah">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="Email" value="<?php echo htmlspecialchars($row['Email'], ENT_QUOTES); ?>" required>
                </div>
                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="NamaLengkap" value="<?php echo htmlspecialchars($row['NamaLengkap'], ENT_QUOTES); ?>" required>
                </div>
                <div class="field full">
                    <label>Alamat</label>
                    <textarea name="Alamat" rows="3"><?php echo htmlspecialchars($row['Alamat'], ENT_QUOTES); ?></textarea>
                </div>
                <div class="full" style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
                    <button type="submit" class="btn btn-edit">Perbarui User</button>
                    <a href="user.php" class="btn btn-back">Batal</a>
                </div>
            </div>
        </form>
        <?php exit(); }

    $sql = "SELECT * FROM user WHERE Level='peminjam' ORDER BY NamaLengkap ASC";
    $res = mysqli_query($koneksi, $sql);
    ?>

    <div style="display:flex;gap:8px;margin-bottom:12px;">
        <a href="user.php?action=add" class="btn btn-tambah">➕ Tambah User Baru</a>
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
                <th>Aksi</th>
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
                <td>
                    <a href="user.php?action=edit&id=<?php echo $r['UserID']; ?>" class="btn btn-edit">Edit</a>
                    <a href="hapus_user.php?id=<?php echo $r['UserID']; ?>" class="btn btn-hapus" onclick="return confirm('Hapus user ini?')">Hapus</a>
                </td>
            </tr>
            <?php } } else { echo "<tr><td colspan='6' style='text-align:center;color:#666'>Belum ada user terdaftar.</td></tr>"; } ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
