<?php 
session_start();
include '../koneksi.php';

// Pastikan user sudah login sebagai peminjam
if(!isset($_SESSION['role']) || $_SESSION['role'] != "peminjam"){
    header("location:../index.php?pesan=gagal");
    exit();
}

// Menangkap ID Buku dari URL
if(!isset($_GET['id'])){
    header("location:index.php");
    exit();
}

$BukuID = $_GET['id'];
$userID = $_SESSION['UserID'];

// Mengambil judul buku agar user tahu buku apa yang sedang diulas
$query_buku = mysqli_query($koneksi, "SELECT Judul FROM buku WHERE BukuID = '$BukuID'");
$b = mysqli_fetch_array($query_buku);

// Jika buku tidak ditemukan
if(!$b){
    echo "<script>alert('Buku tidak ditemukan'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beri Ulasan - Digital Library</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; padding: 40px; display: flex; justify-content: center; align-items: center; min-height: 80vh; }
        .card { background: white; padding: 30px; width: 100%; max-width: 400px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        h3 { color: #333; margin-top: 0; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #555; }
        input, textarea, select { width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        input:focus, textarea:focus, select:focus { border-color: #007bff; outline: none; box-shadow: 0 0 5px rgba(0,123,255,0.2); }
        button { background: #6f42c1; color: white; padding: 12px; width: 100%; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 25px; transition: 0.3s; }
        button:hover { background: #5a32a3; }
        .btn-batal { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 14px; }
        .btn-batal:hover { color: #ff0000; }
        .book-title { color: #007bff; }
    </style>
</head>
<body>
    <div class="card">
        <h3>⭐ Beri Ulasan</h3>
        <p>Anda mengulas buku: <br><strong class="book-title"><?php echo $b['Judul']; ?></strong></p>
        
        <form action="proses_ulasan.php" method="post">
            <input type="hidden" name="BukuID" value="<?php echo $BukuID; ?>">
            
            <label for="Rating">Rating (1-10):</label>
            <select name="Rating" id="Rating" required>
                <option value="">-- Pilih Skor --</option>
                <?php 
                // Loop untuk membuat pilihan rating 10 sampai 1
                for($i=10; $i>=1; $i--) {
                    echo "<option value='$i'>$i - " . ($i >= 8 ? 'Sangat Bagus' : ($i >= 5 ? 'Cukup' : 'Kurang')) . "</option>";
                } 
                ?>
            </select>

            <label for="Ulasan">Komentar / Ulasan Anda:</label>
            <textarea name="Ulasan" id="Ulasan" rows="5" placeholder="Tuliskan pendapat Anda tentang buku ini..." required></textarea>
            
            <button type="submit">Simpan Ulasan</button>
            <a href="index.php" class="btn-batal">Kembali ke Dashboard</a>
        </form>
    </div>
</body>
</html>