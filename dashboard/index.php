<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$role = $_SESSION['role'];

// Foto profil
$photo = !empty($user['photo']) ? '../uploads/' . $user['photo'] : '../uploads/profile.png';

// Ambil data jumlah dari database
$penduduk = $conn->query("SELECT COUNT(*) AS total FROM penduduk")->fetch_assoc()['total'];
$layanan = $conn->query("SELECT COUNT(*) AS total FROM layanan")->fetch_assoc()['total'];
$pengajuan = $conn->query("SELECT COUNT(*) AS total FROM pengajuan")->fetch_assoc()['total'];
$admin = $conn->query("SELECT COUNT(*) AS total FROM admin")->fetch_assoc()['total'];

if ($role === 'admin') {
    $status_data = $conn->query("
        SELECT 
            SUM(CASE WHEN status='Menunggu' THEN 1 ELSE 0 END) AS menunggu,
            SUM(CASE WHEN status='Diproses' THEN 1 ELSE 0 END) AS diproses,
            SUM(CASE WHEN status='Selesai' THEN 1 ELSE 0 END) AS selesai
        FROM pengajuan
    ")->fetch_assoc();
} elseif ($role === 'layanan') {
    $status_data = $conn->query("
        SELECT 
            SUM(CASE WHEN status='Menunggu' THEN 1 ELSE 0 END) AS menunggu,
            SUM(CASE WHEN status='Diproses' THEN 1 ELSE 0 END) AS diproses,
            SUM(CASE WHEN status='Selesai' THEN 1 ELSE 0 END) AS selesai
        FROM pengajuan
    ")->fetch_assoc();
} elseif ($role === 'penduduk') {
    $status_data = $conn->query("
        SELECT 
            SUM(CASE WHEN status='Menunggu' THEN 1 ELSE 0 END) AS menunggu,
            SUM(CASE WHEN status='Diproses' THEN 1 ELSE 0 END) AS diproses,
            SUM(CASE WHEN status='Selesai' THEN 1 ELSE 0 END) AS selesai
        FROM pengajuan
    ")->fetch_assoc();
} else {
    $status_data = ['menunggu' => 0, 'diproses' => 0, 'selesai' => 0];
}

$menunggu = $status_data['menunggu'] ?? 0;
$diproses = $status_data['diproses'] ?? 0;
$selesai = $status_data['selesai'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard | Layanan Rakyat Terpadu Samosir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Poppins", sans-serif;
        }

        /* Sidebar */
        .drawer {
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #1e3c72, #2a5298);
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
        }

        .drawer-header {
            background: rgba(255, 255, 255, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            height: 60px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px;
        }

        .drawer-header .app-icon {
            color: #ffffff;
        }

        .drawer-header .app-name {
            color: #d1e8ff;
            font-weight: 600;
            font-size: 16px;
        }

        /* MENU */
        .menu-section {
            flex: 1;
            overflow-y: auto;
            margin-top: 8px;
        }

        .menu-section a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 3px 10px;
            transition: 0.3s;
            font-size: 14px;
        }

        .menu-section a:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .menu-section a.active {
            background-color: rgba(255, 255, 255, 0.25);
            font-weight: 600;
        }

        .menu-divider {
            display: flex;
            align-items: center;
            margin: 10px 15px 5px 15px;
            opacity: 0.8;
        }

        .menu-divider::before,
        .menu-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .menu-divider span {
            font-size: 12px;
            color: #d1e8ff;
            padding: 0 8px;
            white-space: nowrap;
        }

        /* User Info (Bottom Sidebar) */
        .user-card {
            background-color: rgba(255, 255, 255, 0.1);
            margin: 10px;
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.3s;
        }

        .user-card img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-details .name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .user-details .role {
            font-size: 12px;
            color: #dcdcdc;
        }

        /* Topbar */
        .topbar {
            height: 60px;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        /* Content */
        .content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 20px;
        }

        .card {
            border-radius: 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .card h5 {
            font-weight: 600;
        }

        canvas {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
        }

        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        /* Dropdown Hover */
        .dropdown-menu a.dropdown-item:hover {
            background-color: #2a5298;
            color: #ffffff;
            transition: background-color 0.3s, color 0.3s;
        }

        .dropdown-menu .dropdown-item.active,
        .dropdown-menu .dropdown-item:active {
            background-color: #2a5298 !important;
            color: #fff !important;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="drawer">
        <div class="drawer-header">
            <i class="fa-solid fa-layer-group app-icon fs-3"></i>
            <h6 class="app-name mb-0">SILARA</h6>
        </div>

        <div class="menu-section mt-1">
            <div class="menu-divider"><span>Menu Utama</span></div>
            <a href="index.php" class="active"><i class="fa fa-home me-2"></i><span>Dashboard</span></a>

            <?php if ($role === 'admin'): ?>
                <div class="menu-divider"><span>Manajemen Data</span></div>
                <a href="penduduk.php"><i class="fa fa-users me-2"></i><span>Data Penduduk</span></a>
                <a href="layanan.php"><i class="fa fa-building me-2"></i><span>Data Layanan</span></a>
                <a href="pengajuan.php"><i class="fa fa-file-alt me-2"></i><span>Data Pengajuan</span></a>
                <a href="admin.php"><i class="fa fa-user-shield me-2"></i><span>Data Admin</span></a>

            <?php elseif ($role === 'layanan'): ?>
                <div class="menu-divider"><span>Layanan</span></div>
                <a href="pengajuan.php"><i class="fa fa-file-alt me-2"></i><span>Pengajuan Masuk</span></a>

            <?php elseif ($role === 'penduduk'): ?>
                <div class="menu-divider"><span>Layanan Warga</span></div>
                <a href="buat_pengajuan.php"><i class="fa fa-edit me-2"></i><span>Buat Pengajuan</span></a>
                <a href="status.php"><i class="fa fa-list me-2"></i><span>Status Pengajuan</span></a>
            <?php endif; ?>
        </div>

        <!-- User Card -->
        <div class="user-card">
            <div class="d-flex align-items-center">
                <img id="userPhoto" src="<?= htmlspecialchars($photo) ?>" alt="User">
                <div class="user-details ms-2">
                    <div class="name"><?= htmlspecialchars($user['nama_admin']) ?></div>
                    <div class="role text-capitalize"><?= htmlspecialchars($role) ?></div>
                </div>
            </div>

            <button id="logoutBtn" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center"
                style="border-radius: 8px; width: 35px; height: 35px;">
                <i class="fa fa-sign-out-alt"></i>
            </button>
        </div>
    </div>

    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex flex-column">
            <h5 class="mb-0 fw-bold text-primary">Dashboard</h5>
            <small class="text-muted">Sistem Informasi Layanan Rakyat Terpadu Kabupaten Samosir</small>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars($photo) ?>" alt="User" class="rounded-circle me-2" width="35"
                    height="35">
                <span class="text-primary fw-medium"><?= htmlspecialchars($user['nama_admin']) ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="settings.php"><i class="fa fa-cog me-2"></i>Pengaturan</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#" id="logoutBtnDropdown"><i
                            class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <h2 class="fw-bold mb-4 text-secondary">📊 Dashboard Utama</h2>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-primary text-center p-3">
                    <i class="fa-solid fa-users fa-2x text-primary mb-2"></i>
                    <h5 class="text-primary mb-1">Penduduk</h5>
                    <h3><?= $penduduk ?></h3>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-success text-center p-3">
                    <i class="fa-solid fa-building fa-2x text-success mb-2"></i>
                    <h5 class="text-success mb-1">Layanan</h5>
                    <h3><?= $layanan ?></h3>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-warning text-center p-3">
                    <i class="fa-solid fa-file-lines fa-2x text-warning mb-2"></i>
                    <h5 class="text-warning mb-1">Pengajuan</h5>
                    <h3><?= $pengajuan ?></h3>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-danger text-center p-3">
                    <i class="fa-solid fa-user-shield fa-2x text-danger mb-2"></i>
                    <h5 class="text-danger mb-1">Admin</h5>
                    <h3><?= $admin ?></h3>
                </div>
            </div>
        </div>

        <div class="card shadow-sm p-3">
            <h5 class="text-center text-secondary mb-3">Statistik Pengajuan</h5>
            <canvas id="chartStatus" height="100"></canvas>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Chart.js
        new Chart(document.getElementById('chartStatus'), {
            type: 'bar',
            data: {
                labels: ['Menunggu', 'Diproses', 'Selesai'],
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: [<?= $menunggu ?>, <?= $diproses ?>, <?= $selesai ?>],
                    backgroundColor: ['#ffc107', '#17a2b8', '#28a745']
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        // SweetAlert2 Logout Confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar dari sistem?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../auth/logout.php';
                }
            });
        }

        // Tambahkan event listener ke kedua tombol
        document.getElementById('logoutBtn').addEventListener('click', confirmLogout);
        document.getElementById('logoutBtnDropdown').addEventListener('click', confirmLogout);
    </script>

</body>

</html>