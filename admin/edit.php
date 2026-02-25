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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Digital Library</title>
    <style>
        /* Desain Modern Senada dengan Tambah Buku */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 40px; 
            background-color: #e9ecef; 
        }
        .card { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 600px; 
            margin: auto; 
        }
        h2 { text-align: center; color: #333; margin-top: 0; }
        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
        
        table { width: 100%; }
        td { padding: 8px 0; }
        label { font-weight: bold; color: #555; display: block; margin-bottom: 5px; }
        
        input, select { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 14px;
        }
        input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0,123,255,0.2);
        }

        .btn-group { margin-top: 20px; text-align: right; }
        .btn-update { 
            background-color: #007bff; 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            transition: 0.3s;
        }
        .btn-update:hover { background-color: #0056b3; }
        .btn-kembali { 
            text-decoration: none; 
            color: #6c757d; 
            font-size: 14px; 
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-kembali:hover { color: #333; }

        /* Style untuk preview foto yang sudah ada */
        #imagePreview {
            margin-top: 10px;
            max-width: 120px;
            border-radius: 8px;
            display: block;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>

<div class="card">
    <a href="index.php" class="btn-kembali">⬅ Kembali ke Daftar</a>
    <h2>Edit Data Buku</h2>
    <p style="text-align: center; color: #777; font-size: 14px;">Perbarui informasi buku di bawah ini</p>
    <hr>

    <form method="post" action="proses_edit.php">
        <input type="hidden" name="BukuID" value="<?php echo $d['BukuID']; ?>">
        
        <table>
            <tr>
                <td><label>Judul Buku</label>
                <input type="text" name="Judul" value="<?php echo $d['Judul']; ?>" placeholder="Judul buku..." required></td>
            </tr>
            <tr>
                <td><label>Kategori Buku</label>
                <select name="Kategori" required>
                    <?php
                    // Mengambil kategori secara dinamis
                    $kategori_query = mysqli_query($koneksi, "SELECT * FROM kategoribuku ORDER BY NamaKategori ASC");
                    while($k = mysqli_fetch_array($kategori_query)){
                        // Logika agar kategori yang sedang terpilih otomatis menjadi 'selected'
                        $selected = ($k['NamaKategori'] == $d['Kategori']) ? "selected" : "";
                        echo "<option value='".$k['NamaKategori']."' $selected>".$k['NamaKategori']."</option>";
                    }
                    ?>
                </select></td>
            </tr>
            <tr>
                <td><label>Penulis</label>
                <input type="text" name="Penulis" value="<?php echo $d['Penulis']; ?>" placeholder="Nama penulis..." required></td>
            </tr>
            <tr>
                <td><label>Penerbit</label>
                <input type="text" name="Penerbit" value="<?php echo $d['Penerbit']; ?>" placeholder="Nama penerbit..." required></td>
            </tr>
            <tr>
                <td><label>Tahun Terbit</label>
                <input type="number" name="TahunTerbit" value="<?php echo $d['TahunTerbit']; ?>" required></td>
            </tr>
            <tr>
                <td><label>Jumlah Stok</label>
                <input type="number" name="Stok" value="<?php echo $d['Stok']; ?>" required></td>
            </tr>
            <tr>
                <td>
                    <label>URL Foto Sampul</label>
                    <input type="text" id="fotoInput" name="Foto" value="<?php echo $d['Foto']; ?>" placeholder="https://link-gambar.com/foto.jpg">
                    <?php if(!empty($d['Foto'])): ?>
                        <img id="imagePreview" src="<?php echo $d['Foto']; ?>" alt="Preview">
                    <?php else: ?>
                        <img id="imagePreview" style="display:none;" alt="Preview">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="btn-group">
                        <button type="submit" class="btn-update">🔄 Update Data Buku</button>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    // Preview gambar otomatis saat URL diubah
    const fotoInput = document.getElementById('fotoInput');
    const imagePreview = document.getElementById('imagePreview');

    fotoInput.addEventListener('input', function() {
        if (this.value) {
            imagePreview.src = this.value;
            imagePreview.style.display = 'block';
        } else {
            imagePreview.style.display = 'none';
        }
    });
</script>

</body>
</html>