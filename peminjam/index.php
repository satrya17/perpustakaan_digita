<?php 
session_start();
include '../koneksi.php'; 

if(!isset($_SESSION['role']) || $_SESSION['role'] != "peminjam"){
    header("location:../index.php?pesan=gagal");
    exit();
}
$userID = $_SESSION['UserID'];

// Logika Filter Kategori
$filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Area - DigiLib Premium</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root { 
            --accent-color: #f39c12; 
            --premium-dark: #0a0a0a;
            --glass: rgba(255, 255, 255, 0.03);
            --text-white: #f8f9fa;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--premium-dark); 
            color: #e0e0e0;
            overflow-x: hidden;
        }

        .hero-member {
            background: linear-gradient(135deg, rgba(0,0,0,0.95) 0%, rgba(20,20,20,0.7) 100%), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1920&q=80');
            background-size: cover; background-position: center;
            padding: 120px 0 160px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
            border-bottom: 2px solid var(--accent-color);
        }

        .hero-title { font-family: 'Playfair Display', serif; font-size: 3.5rem; }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .book-card { position: relative; overflow: hidden; border: none; }
        
        .book-img-wrapper { 
            height: 380px; overflow: hidden; border-radius: 18px; 
            margin-bottom: 15px; position: relative; background: #1a1a1a;
        }

        .book-img-wrapper img { 
            width: 100%; height: 100%; object-fit: cover; 
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        .book-card:hover .book-img-wrapper img { transform: scale(1.15); }

        .book-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
            display: flex; flex-direction: column; justify-content: flex-end;
            padding: 25px; opacity: 0; transition: 0.4s; transform: translateY(15px);
        }

        .book-card:hover .book-overlay { opacity: 1; transform: translateY(0); }

        .detail-label { font-size: 0.65rem; color: var(--accent-color); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; display: block; }
        .detail-info { font-size: 0.85rem; color: #fff; margin-bottom: 12px; display: block; }

        .btn-premium {
            background: var(--accent-color); color: #000; font-weight: 700; border-radius: 12px;
            padding: 10px 15px; border: none; font-size: 0.75rem; text-decoration: none; 
            display: inline-block; text-align: center; transition: 0.3s;
        }
        .btn-premium:hover { background: #fff; color: #000; transform: translateY(-2px); }

        .btn-booking {
            border: 1px solid rgba(255,255,255,0.3); color: #fff; font-weight: 600; border-radius: 12px;
            padding: 10px 15px; font-size: 0.75rem; text-decoration: none; transition: 0.3s;
        }
        .btn-booking:hover { border-color: var(--accent-color); color: var(--accent-color); }
        
        .btn-review {
            background: rgba(46, 204, 113, 0.1); color: #2ecc71; border: 1px solid #2ecc71;
            font-size: 0.65rem; font-weight: 700; padding: 5px 10px; border-radius: 8px;
            text-decoration: none; transition: 0.3s; display: inline-block;
        }
        .btn-review:hover { background: #2ecc71; color: #000; }

        .stok-badge {
            position: absolute; top: 15px; right: 15px; z-index: 10;
            background: rgba(0,0,0,0.7); backdrop-filter: blur(5px);
            padding: 5px 12px; border-radius: 10px; font-size: 0.7rem; font-weight: 700;
        }

        .filter-btn {
            background: rgba(255,255,255,0.05); color: #888; border: 1px solid rgba(255,255,255,0.1);
            padding: 8px 20px; border-radius: 100px; font-size: 0.8rem; font-weight: 600;
            text-decoration: none; transition: 0.3s; margin-right: 10px; display: inline-block;
        }
        .filter-btn:hover, .filter-btn.active { background: var(--accent-color); color: #000; border-color: var(--accent-color); }

        footer { border-top: 1px solid rgba(255,255,255,0.05); padding: 50px 0; background: #050505; }
    </style>
</head>
<body>

    <header class="hero-member">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
                <h2 class="fw-800 m-0" style="letter-spacing: 3px;">DIGI<span class="text-warning">LIB.</span></h2>
                <a href="../logout.php" class="btn btn-outline-light rounded-pill px-4 btn-sm fw-bold">KELUAR</a>
            </div>
            <div data-aos="fade-right">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">AKSES EKSKLUSIF</span>
                <h1 class="hero-title fw-bold mb-3">Selamat Datang, <br><span class="text-warning"><?php echo strtoupper($_SESSION['username']); ?></span></h1>
                <p class="lead opacity-50 col-md-6">Eksplorasi koleksi terbaik dengan standar kenyamanan premium.</p>
            </div>
        </div>
    </header>

    <div class="container" style="margin-top: -100px;">
        
        <div class="row mb-5" data-aos="fade-up">
            <div class="col-12 mb-4">
                <h4 class="fw-bold" style="letter-spacing: 2px; font-size: 0.9rem; color: var(--accent-color);">LINIMASA AKTIVITAS DETAIL</h4>
            </div>
            <div class="row g-3">
                <?php 
                $sql_pinjam = "SELECT peminjaman.*, buku.Judul, buku.Penulis, buku.BukuID FROM peminjaman 
                               INNER JOIN buku ON peminjaman.BukuID = buku.BukuID 
                               WHERE peminjaman.UserID = '$userID' ORDER BY PeminjamanID DESC LIMIT 3";
                $query_pinjam = mysqli_query($koneksi, $sql_pinjam);
                if(mysqli_num_rows($query_pinjam) > 0){
                    while($p = mysqli_fetch_array($query_pinjam)){
                        $is_returned = ($p['StatusPeminjaman'] == 'kembali');
                ?>
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100" style="border-left: 4px solid <?php echo $is_returned ? '#2ecc71' : '#f39c12'; ?>;">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="detail-label">ID Pinjam: #<?php echo $p['PeminjamanID']; ?></span>
                            <span class="badge <?php echo $is_returned ? 'bg-success' : 'bg-warning text-dark'; ?> px-2" style="font-size: 0.6rem;">
                                <?php echo strtoupper($p['StatusPeminjaman']); ?>
                            </span>
                        </div>
                        <h6 class="fw-bold text-white mb-1"><?php echo $p['Judul']; ?></h6>
                        <p class="text-muted small mb-2">Penulis: <?php echo $p['Penulis']; ?></p>
                        
                        <?php if($is_returned): ?>
                            <a href="tambah_ulasan.php?id=<?php echo $p['BukuID']; ?>" class="btn-review mb-3 mt-1">BERI ULASAN</a>
                        <?php endif; ?>

                        <div class="border-top border-secondary pt-2 mt-auto opacity-50 d-flex justify-content-between" style="font-size: 0.7rem;">
                            <span>Pinjam: <?php echo date('d M Y', strtotime($p['TanggalPeminjaman'])); ?></span>
                            <span>Batas: <?php echo ($p['TanggalPengembalian'] != '0000-00-00') ? date('d M Y', strtotime($p['TanggalPengembalian'])) : '-'; ?></span>
                        </div>
                    </div>
                </div>
                <?php } } else { echo "<p class='text-muted px-3'>Belum ada riwayat aktivitas.</p>"; } ?>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12" data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <h3 class="fw-bold m-0">Eksplorasi Koleksi</h3>
                    <div class="filter-wrapper">
                        <a href="index.php" class="filter-btn <?php echo $filter == '' ? 'active' : ''; ?>">Semua</a>
                        <a href="index.php?kategori=Teknologi" class="filter-btn <?php echo $filter == 'Teknologi' ? 'active' : ''; ?>">Teknologi</a>
                        <a href="index.php?kategori=Sastra" class="filter-btn <?php echo $filter == 'Sastra' ? 'active' : ''; ?>">Sastra</a>
                        <a href="index.php?kategori=Religi" class="filter-btn <?php echo $filter == 'Religi' ? 'active' : ''; ?>">Religi</a>
                    </div>
                </div>
            </div>

            <?php 
            // Query dengan Filter
            $sql_buku = "SELECT * FROM buku";
            if($filter != '') {
                $sql_buku .= " WHERE Kategori = '$filter'";
            }
            $sql_buku .= " ORDER BY BukuID DESC";
            
            $query_buku = mysqli_query($koneksi, $sql_buku);
            while($b = mysqli_fetch_array($query_buku)){
                $judul_lower = strtolower($b['Judul']);
                $stok = $b['Stok']; 
                
                // --- LOGIKA GAMBAR OTOMATIS BERDASARKAN JUDUL ---
                $foto_buku = "https://images.unsplash.com/photo-1544640808-32ca72ac7f67?q=80&w=400&h=600&auto=format&fit=crop"; // Default
                
                if (strpos($judul_lower, 'php') !== false) $foto_buku = "https://cdn.gramedia.com/uploads/picture_meta/2024/4/5/4trainecsj85vblmfggjnz.png";
                elseif (strpos($judul_lower, 'sistem informasi') !== false) $foto_buku = "https://deepublishstore.com/wp-content/uploads/2021/10/MAHIRM3.jpg";
                elseif (strpos($judul_lower, 'logika') !== false) $foto_buku = "https://ebooks.gramedia.com/ebook-covers/48219/image_highres/ID_LPC2019MTH07LPC.jpg";
                elseif (strpos($judul_lower, 'allah') !== false) $foto_buku = "https://cdn.gramedia.com/uploads/items/9786024181710_Dan-Allah-Pun-Bersyukur.jpg";
                elseif (strpos($judul_lower, 'python') !== false) $foto_buku = "https://cdn.gramedia.com/uploads/items/9786230050299.jpg";
                elseif (strpos($judul_lower, 'filosofi') !== false) $foto_buku = "https://cdn.gramedia.com/uploads/items/9786020623399_filosofi_teras.jpg";
                elseif (strpos($judul_lower, 'bumi manusia') !== false) $foto_buku = "https://cdn.gramedia.com/uploads/items/9789799731235_Bumi-Manusia.jpg";
            ?>
            <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in">
                <div class="glass-card book-card h-100 p-2">
                    <div class="book-img-wrapper">
                        <div class="stok-badge <?php echo ($stok <= 0) ? 'text-danger' : 'text-warning'; ?>">
                            STOK: <?php echo $stok; ?>
                        </div>
                        <img src="<?php echo $foto_buku; ?>" alt="Sampul">
                        <div class="book-overlay">
                            <span class="detail-label">Kategori</span>
                            <span class="detail-info text-warning fw-bold"><?php echo isset($b['Kategori']) ? $b['Kategori'] : 'Umum'; ?></span>
                            
                            <span class="detail-label">Penulis</span>
                            <span class="detail-info"><?php echo $b['Penulis']; ?></span>
                            
                            <div class="mt-2 d-flex gap-2">
                                <?php if($stok > 0): ?>
                                    <a href="pinjam_buku.php?id=<?php echo $b['BukuID']; ?>" class="btn-premium flex-grow-1">PINJAM</a>
                                    <a href="proses_booking.php?id=<?php echo $b['BukuID']; ?>" class="btn-booking">BOOKING</a>
                                <?php else: ?>
                                    <button class="btn btn-dark w-100 disabled" style="font-size: 0.75rem; border: 1px solid #ff4757; color: #ff4757;">STOK HABIS</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 pb-2">
                        <h6 class="fw-bold text-white mb-1 text-truncate"><?php echo $b['Judul']; ?></h6>
                        <p class="text-muted small mb-0"><?php echo $b['Penulis']; ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="row mt-5 pt-4">
            <div class="col-12 mb-4" data-aos="fade-up">
                <h3 class="fw-bold">Ulasan Terkini</h3>
            </div>
            <div class="row g-4">
                <?php 
                $sql_ulasan = "SELECT ulasanbuku.*, user.Username, buku.Judul 
                               FROM ulasanbuku 
                               INNER JOIN user ON ulasanbuku.UserID = user.UserID 
                               INNER JOIN buku ON ulasanbuku.BukuID = buku.BukuID 
                               ORDER BY ulasanbuku.UlasanID DESC LIMIT 3";
                $query_ulasan = mysqli_query($koneksi, $sql_ulasan);
                
                if(mysqli_num_rows($query_ulasan) > 0){
                    while($u = mysqli_fetch_array($query_ulasan)){
                    ?>
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="glass-card p-4 h-100 border-start border-warning border-3">
                            <div class="text-warning mb-2">
                                <?php for($i=1; $i<=$u['Rating']; $i++) echo "★"; ?>
                            </div>
                            <h6 class="fw-bold text-white mb-2"><?php echo $u['Judul']; ?></h6>
                            <p class="small mb-4 opacity-75">"<?php echo $u['Ulasan']; ?>"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="bg-warning rounded-circle me-3 d-flex justify-content-center align-items-center text-dark fw-bold" style="width:30px; height:30px; font-size: 0.7rem;">
                                    <?php echo strtoupper(substr($u['Username'], 0, 1)); ?>
                                </div>
                                <span class="fw-bold small text-white"><?php echo $u['Username']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php } } else { echo "<p class='text-muted px-3'>Belum ada ulasan.</p>"; } ?>
            </div>
        </div>
    </div>

    <footer class="mt-5">
        <div class="container text-center">
            <p class="small opacity-50">&copy; 2026 DIGILIB PREMIUM ARCHIVE.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> 
        AOS.init({ duration: 1000, once: true, easing: 'ease-out-back' }); 
    </script>
</body>
</html>