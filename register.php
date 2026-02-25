<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - DigiLib Premium</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    
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
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            padding: 40px 0;
        }

        /* Canvas untuk partikel JS */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 1;
            top: 0;
            left: 0;
        }

        .register-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            transition: 0.3s;
        }

        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-align: center;
            margin-bottom: 5px;
        }

        .brand-logo span { color: var(--accent-color); }

        h2 { 
            font-size: 1.2rem; 
            text-align: center; 
            margin-bottom: 30px; 
            font-weight: 400;
            opacity: 0.7;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--accent-color);
            margin-bottom: 8px;
            font-weight: 700;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 18px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: #fff;
            font-size: 0.9rem;
            transition: 0.3s;
            box-sizing: border-box;
            font-family: inherit;
        }

        textarea { resize: none; }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255,255,255,0.08);
        }

        /* Style untuk Select Option */
        select option {
            background: #1a1a1a;
            color: #fff;
        }

        button {
            width: 100%;
            padding: 15px;
            background: var(--accent-color);
            color: #000;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.4s;
            margin-top: 10px;
        }

        button:hover {
            background: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(243, 156, 18, 0.2);
        }

        .login-link {
            margin-top: 25px;
            text-align: center;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.4);
        }

        .login-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 700;
        }

        /* Custom Scrollbar untuk Textarea */
        textarea::-webkit-scrollbar { width: 5px; }
        textarea::-webkit-scrollbar-thumb { background: var(--accent-color); border-radius: 10px; }
    </style>
</head>
<body>

    <div id="particles-js"></div>

    <div class="register-container">
        <div class="glass-card">
            <div class="brand-logo">DIGI<span>LIB.</span></div>
            <h2>Pendaftaran Akun Baru</h2>

            <form action="proses_register.php" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="Username" placeholder="Username unik" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="Password" placeholder="••••••••" required>
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="Email" placeholder="nama@email.com" required>
                </div>
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="NamaLengkap" placeholder="Nama sesuai identitas" required>
                </div>
                
                <div class="form-group">
                    <label>Alamat Domisili</label>
                    <textarea name="Alamat" rows="2" placeholder="Alamat lengkap saat ini" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Hak Akses (Role)</label>
                    <select name="Level" required>
                        <option value="peminjam">Peminjam (Siswa/Umum)</option>
                        <option value="petugas">Petugas Perpustakaan</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                
                <button type="submit">Buat Akun Sekarang</button>
            </form>
            
            <div class="login-link">
                Sudah menjadi anggota? <a href="login.php">Masuk ke Akun</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS('particles-js', {
            "particles": {
                "number": { "value": 60, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#f39c12" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.15, "random": false },
                "size": { "value": 2, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#f39c12", "opacity": 0.1, "width": 1 },
                "move": { "enable": true, "speed": 1.5, "direction": "none", "random": false, "straight": false, "out_mode": "out", "bounce": false }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } },
                "modes": { "grab": { "distance": 140, "line_linked": { "opacity": 0.8 } } }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>