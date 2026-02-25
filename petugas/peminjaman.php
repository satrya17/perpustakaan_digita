<?php
session_start();
include '../koneksi.php';

// Proteksi: Petugas atau Admin
if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal"); exit();
}

$pesan = $_GET['pesan'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Manajemen Peminjaman - Petugas</title>
    <style>
        :root{ --bg:#f6f8fb; --card:#fff; --accent:#377dff; --muted:#6b7280; --success:#16a34a; --danger:#ef4444; --radius:12px; --surface-1:#f8fafc }
        *{box-sizing:border-box}
        body{font-family:Inter, 'Segoe UI', system-ui, -apple-system, Roboto, sans-serif; margin:0; background:var(--bg); color:#0f172a;-webkit-font-smoothing:antialiased}
        .box{max-width:1100px;margin:28px auto;padding:20px;background:var(--card);border-radius:var(--radius);box-shadow:0 10px 30px rgba(2,6,23,0.06)}
        h2{margin:0 0 10px 0}
        .controls{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;color:#fff;text-decoration:none;font-weight:700}
        .btn-tambah{background:var(--success)}
        .btn-edit{background:var(--accent)}
        .btn-hapus{background:var(--danger)}
        .btn-csv{background:#0ea5a4}

        .table-responsive{width:100%;overflow:auto;border-radius:8px}
        table{width:100%;border-collapse:collapse;min-width:760px}
        thead th{background:var(--surface-1);padding:12px 14px;text-align:left;font-weight:700;border-bottom:1px solid #eef2f7}
        tbody td{padding:12px 14px;border-bottom:1px solid #f1f5f9}
        tbody tr:nth-child(even){background:#fbfdff}

        label{display:block;margin-top:10px;font-weight:600;color:var(--muted)}
        input,select{padding:10px;border-radius:8px;border:1px solid #e6eef6;width:100%;margin-top:6px}

        /* Form grid */
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
        @media (max-width:520px){
            table{min-width:480px}
            .btn{padding:8px 10px;font-size:13px}
        }
    </style>
</head>
<body>
<div class="box">
    <h2>Manajemen Peminjaman (Petugas)</h2>
    <div style="display:flex;gap:8px;margin-bottom:12px;">
        <a href="peminjaman.php?action=add" class="btn btn-tambah">➕ Tambah Peminjaman</a>
        <a href="export_peminjaman.php" class="btn btn-csv">⬇️ Eksport CSV</a>
    </div>

    <?php if($pesan=='berhasil'){ echo "<div style='background:#d4edda;padding:10px;border-radius:4px;color:#155724;'>✅ Operasi berhasil.</div>"; }
          if($pesan=='hapus'){ echo "<div style='background:#f8d7da;padding:10px;border-radius:4px;color:#721c24;'>🗑️ Data dihapus.</div>"; }
    ?>

    <?php
    if(isset($_GET['action']) && $_GET['action']=='add'){
        $users = mysqli_query($koneksi, "SELECT UserID, NamaLengkap FROM user ORDER BY NamaLengkap");
        $books = mysqli_query($koneksi, "SELECT BukuID, Judul FROM buku ORDER BY Judul");
        ?>
        <form method="post" action="proses_peminjaman.php">
            <input type="hidden" name="action" value="add">
                <div class="form-grid">
                    <div class="field">
                        <label>Nama Peminjam</label>
                        <div style="position:relative">
                            <input name="UserName" id="user_name_input" placeholder="Ketik nama" autocomplete="off">
                            <input type="hidden" name="UserID" id="UserID_input">
                            <!-- typing-only: nama akan ditambahkan otomatis jika belum ada -->
                            <!-- suggestions removed: typing-only mode -->
                        </div>
                    </div>
                    <div class="field">
                    <label>Buku</label>
                    <select name="BukuID" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php while($b = mysqli_fetch_assoc($books)){ echo "<option value='{$b['BukuID']}'>{$b['Judul']}</option>"; } ?>
                    </select>
                </div>
                <div class="field">
                    <label>Tanggal Peminjaman</label>
                    <input type="date" name="TanggalPeminjaman" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="field">
                    <label>Tanggal Pengembalian (opsional)</label>
                    <input type="date" name="TanggalPengembalian">
                </div>
                <div class="field full">
                    <label>Status</label>
                    <select name="StatusPeminjaman" required>
                        <option value="booking">booking</option>
                        <option value="dipinjam">dipinjam</option>
                        <option value="kembali">kembali</option>
                    </select>
                </div>
                <div class="full" style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
                    <button type="submit" class="btn btn-tambah">Simpan</button>
                    <a href="peminjaman.php" class="btn btn-outline" style="color:#374151;background:transparent;border:1px solid #e6eef6;padding:10px 12px">Batal</a>
                </div>
            </div>
        </form>
        <?php exit(); }

    if(isset($_GET['action']) && $_GET['action']=='edit' && isset($_GET['id'])){
        $id = intval($_GET['id']);
        $q = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE PeminjamanID='$id'");
        if(mysqli_num_rows($q)==0){ echo "<div>Data tidak ditemukan.</div>"; exit(); }
        $row = mysqli_fetch_assoc($q);
        $users = mysqli_query($koneksi, "SELECT UserID, NamaLengkap FROM user ORDER BY NamaLengkap");
        $books = mysqli_query($koneksi, "SELECT BukuID, Judul FROM buku ORDER BY Judul");
        ?>
        <form method="post" action="proses_peminjaman.php">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="PeminjamanID" value="<?php echo $row['PeminjamanID']; ?>">
            <div class="form-grid">
                    <div class="field">
                    <label>Nama Peminjam</label>
                    <?php $currentName = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT NamaLengkap FROM user WHERE UserID='".intval($row['UserID'])."'")); ?>
                    <div style="position:relative">
                        <input name="UserName" id="user_name_input_edit" value="<?php echo htmlspecialchars($currentName['NamaLengkap'] ?? '', ENT_QUOTES); ?>" placeholder="Ketik nama" autocomplete="off">
                        <input type="hidden" name="UserID" id="UserID_input_edit" value="<?php echo intval($row['UserID']); ?>">
                        <!-- typing-only: nama akan ditambahkan otomatis jika belum ada -->
                    </div>
                </div>
                <div class="field">
                    <label>Buku</label>
                    <select name="BukuID" required>
                        <?php while($b = mysqli_fetch_assoc($books)){
                            $sel = ($b['BukuID']==$row['BukuID'])?"selected":"";
                            echo "<option value='{$b['BukuID']}' $sel>{$b['Judul']}</option>";
                        } ?>
                    </select>
                </div>
                <div class="field">
                    <label>Tanggal Peminjaman</label>
                    <input type="date" name="TanggalPeminjaman" value="<?php echo $row['TanggalPeminjaman']; ?>" required>
                </div>
                <div class="field">
                    <label>Tanggal Pengembalian</label>
                    <input type="date" name="TanggalPengembalian" value="<?php echo $row['TanggalPengembalian']; ?>">
                </div>
                <div class="field full">
                    <label>Status</label>
                    <select name="StatusPeminjaman" required>
                        <option value="booking" <?php echo ($row['StatusPeminjaman']=='booking')? 'selected':''; ?>>booking</option>
                        <option value="dipinjam" <?php echo ($row['StatusPeminjaman']=='dipinjam')? 'selected':''; ?>>dipinjam</option>
                        <option value="kembali" <?php echo ($row['StatusPeminjaman']=='kembali')? 'selected':''; ?>>kembali</option>
                    </select>
                </div>
                <div class="full" style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
                    <button type="submit" class="btn btn-edit">Perbarui</button>
                    <a href="peminjaman.php" class="btn btn-outline" style="color:#374151;background:transparent;border:1px solid #e6eef6;padding:10px 12px">Batal</a>
                </div>
            </div>
        </form>
        <?php exit(); }

    $sql = "SELECT peminjaman.*, user.NamaLengkap, buku.Judul FROM peminjaman LEFT JOIN user ON peminjaman.UserID=user.UserID LEFT JOIN buku ON peminjaman.BukuID=buku.BukuID ORDER BY PeminjamanID DESC";
    $res = mysqli_query($koneksi, $sql);
    ?>

    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; if(mysqli_num_rows($res)>0){ while($r=mysqli_fetch_assoc($res)){ ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $r['NamaLengkap']; ?></td>
                <td><?php echo $r['Judul']; ?></td>
                <td><?php echo ($r['TanggalPeminjaman']?date('d/m/Y',strtotime($r['TanggalPeminjaman'])):'-'); ?></td>
                <td><?php echo ($r['TanggalPengembalian']?date('d/m/Y',strtotime($r['TanggalPengembalian'])):'-'); ?></td>
                <td><strong><?php echo strtoupper($r['StatusPeminjaman']); ?></strong></td>
                <td>
                    <a href="peminjaman.php?action=edit&id=<?php echo $r['PeminjamanID']; ?>" class="btn btn-edit">Edit</a>
                    <a href="hapus_peminjaman.php?id=<?php echo $r['PeminjamanID']; ?>" class="btn btn-hapus" onclick="return confirm('Hapus data peminjaman ini?')">Hapus</a>
                </td>
            </tr>
            <?php } } else { echo "<tr><td colspan='7' style='text-align:center;color:#666'>Belum ada data peminjaman.</td></tr>"; } ?>
        </tbody>
    </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        // typing-only mode: no suggestion list. On submit, try exact-match; if not found and "CreateUserIfMissing" checked, allow submit so server creates user.
        function handleSubmit(inputId, hiddenId){
            var input = document.getElementById(inputId);
            var hidden = document.getElementById(hiddenId);
            if(!input || !hidden) return;
            var form = input.closest('form');
            if(!form) return;
            form.addEventListener('submit', function(e){
                if(hidden.value) return true;
                e.preventDefault();
                var q = input.value.trim();
                if(!q){ alert('Masukkan nama peminjam.'); input.focus(); return; }
                fetch('search_user.php?q='+encodeURIComponent(q)+'&exact=1')
                    .then(function(r){ return r.json(); })
                    .then(function(data){
                        if(data && data.length === 1){
                            hidden.value = data[0].id;
                            form.submit();
                        } else if(data && data.length > 1){
                            alert('Nama peminjam tidak unik. Mohon tambahkan penanda (mis. nomor) atau daftarkan pengguna terlebih dahulu.'); input.focus();
                        } else {
                            // not found — allow server to create the user automatically
                            form.submit();
                        }
                    }).catch(function(){ alert('Gagal memeriksa nama. Coba lagi.'); input.focus(); });
            });
        }

        handleSubmit('user_name_input','UserID_input');
        handleSubmit('user_name_input_edit','UserID_input_edit');
    });
    </script>

</div>
</body>
</html>
