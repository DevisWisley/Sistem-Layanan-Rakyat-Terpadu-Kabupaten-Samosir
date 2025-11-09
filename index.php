<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILARA - Sistem Layanan Rakyat Terpadu Kabupaten Samosir</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #1e90ff;
            --accent: #6c63ff;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            padding-top: 76px;
            /* Space for fixed navbar */
            scroll-behavior: smooth;
        }

        /* SIMPLIFIED NAVBAR STYLES */
        .navbar {
            transition: all 0.3s ease;
            padding: 1rem 0;
            background: transparent !important;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar.scrolled .navbar-brand {
            color: var(--primary) !important;
        }

        .nav-link {
            font-weight: 500;
            margin: 0 0.3rem;
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
            cursor: pointer;
            position: relative;
        }

        .navbar.scrolled .nav-link {
            color: var(--dark) !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
        }

        .navbar.scrolled .nav-link:hover,
        .navbar.scrolled .nav-link.active {
            color: var(--primary) !important;
            background: rgba(13, 110, 253, 0.1);
        }

        /* Active section indicator */
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link.active::after {
            width: 80%;
        }

        /* Mobile menu styles */
        .navbar-toggler {
            border: none;
            padding: 0.4rem 0.6rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar.scrolled .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                padding: 1rem;
                margin-top: 1rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            .nav-link {
                color: var(--dark) !important;
                text-align: center;
                margin: 0.3rem 0;
            }

            .nav-link:hover,
            .nav-link.active {
                color: var(--primary) !important;
                background: rgba(13, 110, 253, 0.1);
            }

            .navbar-brand {
                color: white !important;
            }
        }

        /* Button styles */
        .btn-login {
            background: white;
            color: var(--primary) !important;
            border: 2px solid white;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: transparent;
            color: white !important;
            transform: translateY(-2px);
        }

        .navbar.scrolled .btn-login {
            background: var(--primary);
            color: white !important;
            border-color: var(--primary);
        }

        .navbar.scrolled .btn-login:hover {
            background: transparent;
            color: var(--primary) !important;
        }

        .btn-register {
            background: transparent;
            color: white !important;
            border: 2px solid white;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: white;
            color: var(--primary) !important;
            transform: translateY(-2px);
        }

        .navbar.scrolled .btn-register {
            background: transparent;
            color: var(--primary) !important;
            border-color: var(--primary);
        }

        .navbar.scrolled .btn-register:hover {
            background: var(--primary);
            color: white !important;
        }

        /* Hero section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-top: -76px;
            /* Compensate for body padding */
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,192C1248,192,1344,128,1392,96L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
        }

        /* Section styles with IDs for navigation */
        .page-section {
            min-height: 100vh;
            padding: 100px 0;
            position: relative;
        }

        #hero {
            padding: 150px 0;
        }

        #features {
            background-color: var(--light);
        }

        #about {
            background-color: white;
        }

        /* Existing feature styles */
        .feature-card {
            border-radius: 20px;
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            background: rgba(13, 110, 253, 0.1);
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            transform: scale(1.1);
        }

        .feature-card:hover .feature-icon i {
            color: white !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--accent);
            top: 10%;
            left: 5%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: var(--primary);
            bottom: 10%;
            right: 5%;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }

            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        .stats-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            position: relative;
            overflow: hidden;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .footer {
            background: var(--dark);
            color: white;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: white;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#hero">
                <i class="fa fa-layer-group me-2"></i> SILARA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link active" data-section="hero">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="about">Tentang</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="auth/login.php" class="btn btn-login">
                            <i class="fa fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="auth/register.php" class="btn btn-register">
                            <i class="fa fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="hero" class="hero-section text-white page-section">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>

        <div class="container py-5 position-relative" style="z-index: 1;">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="fw-bold display-4 mb-3">Sistem Layanan Rakyat Terpadu Kabupaten Samosir</h1>
                    <p class="lead text-light mb-4">
                        Portal pelayanan publik digital untuk masyarakat Samosir — cepat, transparan, dan terintegrasi.
                        Nikmati kemudahan akses layanan publik kapan saja, di mana saja.
                    </p>
                    <div class="mt-4">
                        <a href="auth/login.php" class="btn btn-light btn-lg me-3 shadow-sm px-4 py-2 fw-medium">
                            <i class="fa fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a href="auth/register.php" class="btn btn-outline-light btn-lg shadow-sm px-4 py-2 fw-medium">
                            <i class="fa fa-user-plus me-1"></i> Register
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                    <div class="text-center">
                        <img src="https://wahananews.co/photo/berita/dir042022/menelusuri-sejarah-terbentuknya-danau-toba-dan-pulau-samosir_0Uounr7D9I.jpg"
                            alt="SILARA Dashboard" class="img-fluid rounded-3 shadow-lg" style="max-height: 400px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURE SECTION -->
    <section id="features" class="py-5 bg-light position-relative overflow-hidden page-section">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary section-title">Fitur Utama</h2>
                <p class="text-muted fs-5">Layanan unggulan untuk kemudahan masyarakat.</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 feature-card p-4">
                        <div class="feature-icon mb-3">
                            <i class="fa fa-users fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold text-center">Data Penduduk</h5>
                        <p class="text-muted mb-0 text-center">
                            Mengelola dan memantau data masyarakat secara terpusat dan aman dengan pembaruan real-time.
                            Akses informasi kependudukan dengan mudah dan cepat.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 feature-card p-4">
                        <div class="feature-icon mb-3">
                            <i class="fa fa-briefcase fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold text-center">Data Layanan</h5>
                        <p class="text-muted mb-0 text-center">
                            Akses berbagai layanan publik yang dapat diajukan secara online dengan mudah dan cepat.
                            Nikmati kemudahan tanpa harus datang ke kantor.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 feature-card p-4">
                        <div class="feature-icon mb-3">
                            <i class="fa fa-folder-open fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold text-center">Data Pengajuan</h5>
                        <p class="text-muted mb-0 text-center">
                            Pantau status pengajuan Anda secara langsung, transparan, dan informatif dari satu
                            dashboard.
                            Tidak perlu menunggu lama untuk mengetahui progres.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section class="py-5 stats-section text-white">
        <div class="container py-5">
            <div class="row text-center">
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <h2 class="display-4 fw-bold">10K+</h2>
                        <p class="mb-0">Pengguna Terdaftar</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <h2 class="display-4 fw-bold">25+</h2>
                        <p class="mb-0">Jenis Layanan</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <h2 class="display-4 fw-bold">15K+</h2>
                        <p class="mb-0">Pengajuan Diproses</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <h2 class="display-4 fw-bold">98%</h2>
                        <p class="mb-0">Kepuasan Pengguna</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section id="about" class="py-5 page-section" data-aos="fade-up">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <img src="https://batakita.com/wp-content/uploads/2022/10/image-22-1024x682.png"
                        alt="Tentang SILARA" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <h2 class="fw-bold text-primary section-title">Tentang SILARA</h2>
                    <p class="text-muted fs-5 mb-4" style="text-align: justify;">
                        <strong>SILARA (Sistem Informasi Layanan Rakyat Terpadu Kabupaten Samosir)</strong> adalah
                        platform digital resmi Pemerintah Kabupaten Samosir
                        yang memudahkan masyarakat mengakses layanan publik secara online. Sistem ini mengintegrasikan
                        berbagai layanan administrasi kependudukan,
                        surat keterangan, perizinan, dan pengajuan dokumen resmi lainnya dalam satu dashboard yang mudah
                        digunakan.
                    </p>
                    <p class="text-muted fs-5 mb-4" style="text-align: justify;">
                        Dengan SILARA, masyarakat dapat mengajukan berbagai keperluan administrasi tanpa harus datang ke
                        kantor pemerintahan,
                        menghemat waktu dan biaya transportasi. Sistem ini juga memastikan transparansi dan
                        akuntabilitas dalam setiap proses layanan.
                    </p>
                    <a href="./pages/about.php" class="btn btn-primary mt-3 px-4 py-2">
                        <i class="fa fa-info-circle me-1"></i> Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer py-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">
                        <i class="fa fa-layer-group me-2"></i> SILARA
                    </h5>
                    <p class="text-light mb-3">
                        Sistem Layanan Rakyat Terpadu Kabupaten Samosir - Portal pelayanan publik digital untuk
                        masyarakat Samosir.
                    </p>
                    <div class="d-flex">
                        <a href="#" class="social-icon me-2">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon me-2">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon me-2">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#hero">Beranda</a></li>
                        <li class="mb-2"><a href="#features">Fitur</a></li>
                        <li class="mb-2"><a href="#about">Tentang</a></li>
                        <li class="mb-2"><a href="auth/login.html">Login</a></li>
                        <li class="mb-2"><a href="auth/register.html">Register</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Kartu Tanda Penduduk</a></li>
                        <li class="mb-2"><a href="#">Kartu Keluarga</a></li>
                        <li class="mb-2"><a href="#">Akta Kelahiran</a></li>
                        <li class="mb-2"><a href="#">Surat Keterangan</a></li>
                        <li class="mb-2"><a href="#">Perizinan Usaha</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fa fa-map-marker-alt me-2"></i>
                            Jl. Pemuda No. 123, Pangururan, Samosir
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-phone me-2"></i>
                            (0625) 12345
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-envelope me-2"></i>
                            info@silara-samosir.go.id
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-clock me-2"></i>
                            Senin - Jumat: 08:00 - 16:00
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2023 SILARA - Kabupaten Samosir. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-light me-3">Kebijakan Privasi</a>
                    <a href="#" class="text-light">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Custom JS -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Update active nav link based on scroll position
            updateActiveNavLink();
        });

        // Update active nav link based on scroll position
        function updateActiveNavLink() {
            const sections = document.querySelectorAll('.page-section');
            const navLinks = document.querySelectorAll('.nav-link[data-section]');

            let currentSection = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.clientHeight;
                if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                    currentSection = section.id;
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-section') === currentSection) {
                    link.classList.add('active');
                }
            });
        }

        // Smooth scrolling for nav links
        document.querySelectorAll('.nav-link[data-section]').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const targetSection = this.getAttribute('data-section');
                const targetElement = document.getElementById(targetSection);

                if (targetElement) {
                    // Update active nav link
                    document.querySelectorAll('.nav-link[data-section]').forEach(navLink => {
                        navLink.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Smooth scroll to section
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const navbarCollapse = document.getElementById('navbarNav');
                    if (navbarCollapse.classList.contains('show')) {
                        const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                        bsCollapse.hide();
                    }
                }
            });
        });

        // Smooth scrolling for footer links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Initialize active section on page load
        updateActiveNavLink();
    </script>
</body>

</html>