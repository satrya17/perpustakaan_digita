<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiLib - Sistem Reservasi Perpustakaan Fisik</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --glass: rgba(255, 255, 255, 0.2);
            --glass-heavy: rgba(255, 255, 255, 0.4);
            --primary-gradient: linear-gradient(135deg, #2c3e50 0%, #000000 100%);
            --accent-color: #f39c12;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            overflow-x: hidden;
        }

        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 90vh;
            color: white;
            display: flex;
            align-items: center;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%);
        }

        .hero-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 3.5rem;
            line-height: 1.1;
        }

        .btn-modern {
            background: var(--accent-color);
            color: white;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .btn-modern:hover {
            background: #d35400;
            transform: translateY(-3px);
            color: white;
        }

        .book-card {
            border: 1px solid #eee;
            border-radius: 15px;
            background: white;
            transition: all 0.3s ease;
        }

        .book-card:hover {
            border-color: var(--accent-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .status-badge {
            background: #e7f3ff;
            color: #007bff;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        footer { background: #1a1a1a; color: white; padding: 40px 0; }
    </style>
</head>
<body>

    <header class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8" data-aos="fade-up">
                    <span class="badge bg-warning text-dark mb-3">SISTEM RESERVASI ONLINE</span>
                    <h1 class="hero-title mb-4">Cek Koleksi dari Rumah, <br> Ambil di Perpustakaan.</h1>
                    <p class="lead mb-5">Kelola peminjaman buku fisik Anda dengan lebih cerdas. Booking buku favorit Anda sekarang dan ambil kapan saja tanpa antre.</p>
                    <a href="login.php" class="btn-modern">Cari & Booking Buku &rarr;</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container" style="margin-top: -80px;">
        <div class="row g-4">
            <div class="col-12 text-center mb-2" data-aos="fade-up">
                <h3 class="fw-bold text-white shadow-sm">Koleksi Terbaru Tersedia</h3>
            </div>
            <?php 
            // Menampilkan 3 buku terbaru untuk di-booking
            $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY BukuID DESC LIMIT 3");
            while($b = mysqli_fetch_array($query)){
            ?>
            <div class="col-md-4" data-aos="zoom-in">
                <div class="book-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="status-badge">Tersedia Fisik</span>
                        <div class="text-warning">⭐⭐⭐⭐</div>
                    </div>
                    <h4 class="fw-bold"><?php echo $b['Judul']; ?></h4>
                    <p class="text-muted mb-4">Penulis: <?php echo $b['Penulis']; ?></p>
                    <hr>
                    <div class="d-grid">
                        <a href="login.php" class="btn btn-outline-dark rounded-pill">Booking Sekarang</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <section class="container py-5 mt-5">
        <div class="row align-items-center g-5">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="fw-bold mb-4">Bagaimana Cara Kerjanya?</h2>
                <div class="mb-4">
                    <h5>1. Pilih Buku Secara Online</h5>
                    <p class="text-muted">Cari koleksi buku fisik kami melalui katalog digital ini.</p>
                </div>
                <div class="mb-4">
                    <h5>2. Lakukan Reservasi (Booking)</h5>
                    <p class="text-muted">Gunakan akun Anda untuk melakukan booking agar stok aman untuk Anda.</p>
                </div>
                <div class="mb-4">
                    <h5>3. Ambil di Perpustakaan</h5>
                    <p class="text-muted">Tunjukkan bukti booking kepada petugas dan ambil buku fisik Anda.</p>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <div class="p-5 bg-dark text-white rounded-4 shadow-lg">
                    <h3 class="fw-bold">Siap Mengelola Data?</h3>
                    <p class="opacity-75">Admin dan Petugas dapat memantau siapa saja yang melakukan booking hari ini melalui dashboard manajemen.</p>
                    <div class="mt-4">
                        <a href="login.php" class="btn btn-warning fw-bold px-4">Login Petugas</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 DigiLib Physical Management System. Literasi Untuk Semua.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> AOS.init(); </script>
</body>
</html>