<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DigiLib Premium</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
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
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Canvas untuk partikel JS */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            border-color: rgba(243, 156, 18, 0.3);
        }

        .brand-logo {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .brand-logo span { color: var(--accent-color); }

        .btn-back {
            display: inline-block;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            font-size: 0.8rem;
            margin-bottom: 30px;
            transition: 0.3s;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .btn-back:hover { color: var(--accent-color); }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent-color);
            margin-bottom: 8px;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 14px 20px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: #fff;
            font-size: 0.95rem;
            transition: 0.3s;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.2);
        }

        button {
            width: 100%;
            padding: 16px;
            background: var(--accent-color);
            color: #000;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.4s;
            margin-top: 10px;
        }

        button:hover {
            background: #fff;
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .alert {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .footer-text {
            margin-top: 30px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.4);
        }

        .footer-text a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <div id="particles-js"></div>

    <div class="login-container">
        <div class="glass-card">
            <a href="index.php" class="btn-back">← KEMBALI</a>
            
            <div class="brand-logo">DIGI<span>LIB.</span></div>
            <p style="font-size: 0.9rem; opacity: 0.5; margin-bottom: 40px;">Premium Archive Access</p>

            <?php 
            if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"){
                echo "<div class='alert'>⚠️ Akses ditolak. Username atau Password salah!</div>";
            }
            ?>

            <form action="proses_login.php" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit">Masuk Sekarang</button>
            </form>

            <p class="footer-text">
                Belum memiliki akses? <a href="register.php">Daftar Akun</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS('particles-js', {
            "particles": {
                "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#f39c12" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.2, "random": false },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#f39c12", "opacity": 0.1, "width": 1 },
                "move": { "enable": true, "speed": 2, "direction": "none", "random": false, "straight": false, "out_mode": "out", "bounce": false }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } },
                "modes": { "grab": { "distance": 140, "line_linked": { "opacity": 1 } } }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>