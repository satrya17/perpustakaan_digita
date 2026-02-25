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
    <title>Tambah Buku - Digital Library</title>
    <style>
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
        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0,123,255,0.2);
        }

        .btn-group { margin-top: 20px; text-align: right; }
        .btn-simpan { 
            background-color: #28a745; 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            transition: 0.3s;
        }
        .btn-simpan:hover { background-color: #218838; }
        .btn-kembali { 
            text-decoration: none; 
            color: #007bff; 
            font-size: 14px; 
            display: inline-block;
            margin-bottom: 20px;
        }
        #imagePreview {
            margin-top: 10px;
            max-width: 150px;
            border-radius: 8px;
            display: none;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>

<div class="card">
    <a href="index.php" class="btn-kembali">⬅ Kembali ke Dashboard</a>
    <h2>Tambah Data Buku</h2>
    <p style="text-align: center; color: #777; font-size: 14px;">Isi formulir di bawah untuk menambah koleksi perpustakaan</p>
    <hr>

    <form method="post" action="proses_tambah_buku.php">
        <table>
            <tr>
                <td><label>Judul Buku</label>
                <input type="text" name="Judul" placeholder="Masukkan judul buku..." required></td>
            </tr>
            <tr>
                <td><label>Kategori Buku</label>
                <select name="Kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    // Mengambil kategori secara dinamis dari tabel kategoribuku
                    $kategori_query = mysqli_query($koneksi, "SELECT * FROM kategoribuku ORDER BY NamaKategori ASC");
                    while($k = mysqli_fetch_array($kategori_query)){
                        echo "<option value='".$k['NamaKategori']."'>".$k['NamaKategori']."</option>";
                    }
                    ?>
                </select></td>
            </tr>
            <tr>
                <td><label>Penulis</label>
                <input type="text" name="Penulis" placeholder="Nama penulis..." required></td>
            </tr>
            <tr>
                <td><label>Penerbit</label>
                <input type="text" name="Penerbit" placeholder="Nama penerbit..." required></td>
            </tr>
            <tr>
                <td><label>Tahun Terbit</label>
                <input type="number" name="TahunTerbit" placeholder="Tahun (Contoh: 2024)" required></td>
            </tr>
            <tr>
                <td><label>Jumlah Stok</label>
                <input type="number" name="Stok" placeholder="Jumlah buku yang tersedia" required></td>
            </tr>
            <tr>
                <td>
                    <label>Link Foto Sampul (URL Gambar)</label>
                    <input type="text" id="fotoInput" name="Foto" placeholder="Tempel link gambar dari internet di sini...">
                    <img id="imagePreview" src="" alt="Preview Sampul">
                </td>
            </tr>
            <tr>
                <td>
                    <div class="btn-group">
                        <button type="submit" class="btn-simpan">💾 Simpan Buku Baru</button>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    // Fitur Preview Gambar Otomatis
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