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

        /* Hero section for about page */
        .about-hero {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 120px 0 80px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .about-hero::before {
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

        /* About Content Styles */
        .about-content {
            padding: 80px 0;
        }

        .vision-mission-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .vision-mission-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .vision-mission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .value-card {
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .value-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .value-card:hover::before {
            transform: scaleX(1);
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .value-icon {
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

        .value-card:hover .value-icon {
            background: var(--primary);
            transform: scale(1.1);
        }

        .value-card:hover .value-icon i {
            color: white !important;
        }

        /* Team Section */
        .team-section {
            background: var(--light);
            padding: 80px 0;
        }

        .team-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .team-img {
            height: 250px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .team-card:hover .team-img {
            transform: scale(1.05);
        }

        .team-info {
            padding: 1.5rem;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding: 2rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary);
            transform: translateX(-50%);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 3rem;
        }

        .timeline-content {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            position: relative;
            border-left: 4px solid var(--primary);
        }

        .timeline-content::before {
            content: '';
            position: absolute;
            top: 20px;
            width: 20px;
            height: 20px;
            background: var(--primary);
            border-radius: 50%;
        }

        .timeline-item:nth-child(odd) .timeline-content {
            margin-right: 50%;
            margin-left: 2rem;
        }

        .timeline-item:nth-child(odd) .timeline-content::before {
            right: -40px;
        }

        .timeline-item:nth-child(even) .timeline-content {
            margin-left: 50%;
            margin-right: 2rem;
        }

        .timeline-item:nth-child(even) .timeline-content::before {
            left: -40px;
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
                        <a class="nav-link" href="../index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/about.php">Tentang</a>
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
    <section class="about-hero">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="fw-bold display-4 mb-3" data-aos="fade-right">Tentang SILARA</h1>
                    <p class="lead mb-4" data-aos="fade-right" data-aos-delay="100">
                        Mengenal lebih dalam tentang Sistem Layanan Rakyat Terpadu Kabupaten Samosir 
                        dan komitmen kami dalam memberikan pelayanan terbaik untuk masyarakat.
                    </p>
                    <div class="d-flex flex-wrap gap-2" data-aos="fade-right" data-aos-delay="200">
                        <span class="badge bg-light text-primary fs-6 p-2">Transparan</span>
                        <span class="badge bg-light text-primary fs-6 p-2">Terintegrasi</span>
                        <span class="badge bg-light text-primary fs-6 p-2">Efisien</span>
                        <span class="badge bg-light text-primary fs-6 p-2">Akuntabel</span>
                    </div>
                </div>
                <div class="col-lg-4 text-center" data-aos="fade-left">
                    <img src="https://batakita.com/wp-content/uploads/2022/10/image-22-1024x682.png" 
                         alt="About SILARA" class="img-fluid" style="max-height: 300px;">
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT CONTENT -->
    <section class="about-content">
        <div class="container">
            <!-- Visi Misi -->
            <div class="row mb-5">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold text-primary section-title">Visi & Misi</h2>
                    <p class="text-muted fs-5">Menjadi pelopor layanan publik digital yang terdepan di Indonesia</p>
                </div>
                
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <div class="vision-mission-card">
                        <div class="text-center mb-4">
                            <i class="fa fa-eye fa-3x text-primary mb-3"></i>
                            <h3 class="fw-bold text-primary">Visi</h3>
                        </div>
                        <p class="fs-5 text-muted text-center">
                            "Menjadi sistem layanan publik terpadu yang modern, transparan, dan terpercaya 
                            dalam memberikan kemudahan akses pelayanan bagi masyarakat Kabupaten Samosir"
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4" data-aos="fade-left">
                    <div class="vision-mission-card">
                        <div class="text-center mb-4">
                            <i class="fa fa-bullseye fa-3x text-success mb-3"></i>
                            <h3 class="fw-bold text-success">Misi</h3>
                        </div>
                        <ul class="fs-6 text-muted">
                            <li class="mb-3">Mengintegrasikan seluruh layanan publik dalam satu platform digital</li>
                            <li class="mb-3">Meningkatkan transparansi dan akuntabilitas pelayanan publik</li>
                            <li class="mb-3">Mempermudah akses masyarakat terhadap layanan pemerintah</li>
                            <li class="mb-3">Mengoptimalkan penggunaan teknologi untuk efisiensi pelayanan</li>
                            <li>Membangun sistem yang responsif dan mudah digunakan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Nilai-nilai -->
            <div class="row mb-5">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold text-primary section-title">Nilai-nilai Kami</h2>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fa fa-shield-alt fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Transparansi</h5>
                        <p class="text-muted mb-0">
                            Setiap proses layanan dapat dipantau secara terbuka dan jelas oleh masyarakat
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fa fa-bolt fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold">Efisiensi</h5>
                        <p class="text-muted mb-0">
                            Proses layanan yang cepat dan tepat tanpa birokrasi yang berbelit
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fa fa-users fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold">Partisipatif</h5>
                        <p class="text-muted mb-0">
                            Melibatkan masyarakat dalam pengawasan dan evaluasi layanan
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fa fa-chart-line fa-2x text-info"></i>
                        </div>
                        <h5 class="fw-bold">Inovatif</h5>
                        <p class="text-muted mb-0">
                            Terus berinovasi mengikuti perkembangan teknologi dan kebutuhan masyarakat
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sejarah -->
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold text-primary section-title">Sejarah Perkembangan</h2>
                </div>
                
                <div class="col-12">
                    <div class="timeline">
                        <div class="timeline-item" data-aos="fade-up">
                            <div class="timeline-content">
                                <h5 class="fw-bold text-primary">2021</h5>
                                <h6 class="fw-bold">Inisiasi Proyek</h6>
                                <p class="text-muted mb-0">
                                    Dimulainya perencanaan sistem terintegrasi untuk meningkatkan kualitas layanan publik 
                                    di Kabupaten Samosir
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                            <div class="timeline-content">
                                <h5 class="fw-bold text-primary">2022</h5>
                                <h6 class="fw-bold">Pengembangan Sistem</h6>
                                <p class="text-muted mb-0">
                                    Proses pengembangan platform digital dengan melibatkan berbagai stakeholder 
                                    dan uji coba terbatas
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                            <div class="timeline-content">
                                <h5 class="fw-bold text-primary">2023</h5>
                                <h6 class="fw-bold">Peluncuran Resmi</h6>
                                <p class="text-muted mb-0">
                                    SILARA diluncurkan secara resmi dan mulai melayani masyarakat dengan 
                                    15 jenis layanan publik
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                            <div class="timeline-content">
                                <h5 class="fw-bold text-primary">2024</h5>
                                <h6 class="fw-bold">Ekspansi Layanan</h6>
                                <p class="text-muted mb-0">
                                    Penambahan 10 layanan baru dan integrasi dengan sistem pemerintah pusat
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TEAM SECTION -->
    <section class="team-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold text-primary section-title">Tim Pengembang</h2>
                    <p class="text-muted fs-5">Dedikasi dan komitmen untuk pelayanan terbaik</p>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="team-card">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                             alt="Team Member" class="team-img">
                        <div class="team-info">
                            <h5 class="fw-bold mb-1">Budi Santoso</h5>
                            <p class="text-muted mb-2">Project Manager</p>
                            <p class="small text-muted mb-0">
                                Memimpin pengembangan sistem dengan pengalaman 10 tahun di bidang IT pemerintah
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="team-card">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                             alt="Team Member" class="team-img">
                        <div class="team-info">
                            <h5 class="fw-bold mb-1">Sari Dewi</h5>
                            <p class="text-muted mb-2">System Analyst</p>
                            <p class="small text-muted mb-0">
                                Ahli dalam menganalisis kebutuhan sistem dan proses bisnis pemerintah
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="team-card">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                             alt="Team Member" class="team-img">
                        <div class="team-info">
                            <h5 class="fw-bold mb-1">Ahmad Rizki</h5>
                            <p class="text-muted mb-2">Lead Developer</p>
                            <p class="small text-muted mb-0">
                                Mengembangkan sistem dengan teknologi terkini untuk performa optimal
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="team-card">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
                             alt="Team Member" class="team-img">
                        <div class="team-info">
                            <h5 class="fw-bold mb-1">Maya Sari</h5>
                            <p class="text-muted mb-2">UI/UX Designer</p>
                            <p class="small text-muted mb-0">
                                Mendesain antarmuka yang user-friendly dan mudah digunakan oleh masyarakat
                            </p>
                        </div>
                    </div>
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