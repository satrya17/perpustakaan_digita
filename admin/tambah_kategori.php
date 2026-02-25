<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f7f6; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        input { width: 100%; padding: 12px; margin: 15px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h3>➕ Tambah Kategori</h3>
        <form action="proses_kategori.php" method="post">
            <input type="text" name="NamaKategori" placeholder="Contoh: Fiksi atau Komputer" required autofocus>
            <button type="submit">Simpan Kategori</button>
            <a href="kategori.php" style="display:block; text-align:center; margin-top:15px; color:gray; text-decoration:none; font-size: 14px;">Batal</a>
        </form>
    </div>
</body>
</html>