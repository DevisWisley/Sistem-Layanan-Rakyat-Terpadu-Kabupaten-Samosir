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

// Ambil ID user
$userId = $user['id'] ?? $user['id_admin'] ?? null;
if (!$userId) {
    die("ID user tidak ditemukan di session!");
}

// Handle update profile
if (isset($_POST['update_profile'])) {
    $nama = $conn->real_escape_string(trim($_POST['nama_admin']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    $current_password = trim($_POST['current_password']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    // Handle photo upload
    $photoName = $user['photo'] ?? '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/' . $photoName);
    }

    // Ambil password saat ini dari DB
    $query = $conn->query("SELECT password FROM admin WHERE id='$userId'");
    $dbPassword = $query->fetch_assoc()['password'] ?? '';

    if (!empty($current_password) && $current_password !== $dbPassword) {
        $error = "Password saat ini salah!";
    } elseif (!empty($password) && $password !== $confirm) {
        $error = "Password baru tidak cocok!";
    } else {
        $updateSql = "UPDATE admin SET 
            nama_admin='$nama',
            username='$username',
            photo='$photoName'" . (!empty($password) ? ", password='$password'" : "") . "
            WHERE id='$userId'";

        if ($conn->query($updateSql)) {
            // Update session
            $_SESSION['user']['nama_admin'] = $nama;
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['photo'] = $photoName;
            $success = "Profil berhasil diperbarui!";
        } else {
            $error = "Terjadi kesalahan: " . $conn->error;
        }
    }
}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Scrollbar untuk Webkit (Chrome, Edge, Safari) */
        ::-webkit-scrollbar {
            width: 10px;
            /* lebar scrollbar */
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* warna background track */
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #2a5298;
            /* warna thumb scrollbar */
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1e3c72;
            /* warna saat hover */
        }

        /* Scrollbar untuk Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #2a5298 #f1f1f1;
        }

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

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: #2a5298;
        }

        /* Konten */
        .content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 20px;
        }

        .img-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 25px;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            border-radius: 0 12px 12px 0;
        }

        .form-section {
            display: flex;
            gap: 20px;
        }

        .form-section .col {
            flex: 1;
        }

        @media(max-width: 768px) {
            .form-section {
                flex-direction: column;
            }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .breadcrumb {
            font-size: 14px;
            margin-bottom: 0;
        }

        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

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
            <a href="index.php"><i class="fa fa-home me-2"></i><span>Dashboard</span></a>

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
    <div class="topbar">
        <h5 class="fw-bold text-danger mb-0">Data Admin</h5>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars($photo) ?>" alt="User" class="rounded-circle me-2" width="35"
                    height="35">
                <span class="text-primary fw-medium"><?= htmlspecialchars($user['nama_admin']) ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item active" href="settings.php"><i class="fa fa-cog me-2"></i>Pengaturan</a></li>
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
        <div class="page-header">
            <div>
                <h4 class="fw-bold mb-1 text-danger"><i class="fa fa-cog me-2"></i>Pengaturan Akun</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"
                                class="text-decoration-none text-danger">Dashboard</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Pengaturan Akun</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-center">
                    <img id="preview" src="<?= htmlspecialchars($photo) ?>" alt="Foto Profil" class="img-preview">
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-section">
                        <!-- Kolom Kiri -->
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    <input type="text" name="nama_admin" class="form-control"
                                        value="<?= htmlspecialchars($user['nama_admin'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-id-badge"></i></span>
                                    <input type="text" name="username" class="form-control"
                                        value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" name="current_password" class="form-control"
                                        placeholder="Masukkan password saat ini">
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Kosongkan jika tidak ingin diubah">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                                    <input type="password" name="confirm_password" class="form-control"
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Profil</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-image"></i></span>
                                    <input type="file" name="photo" class="form-control" accept="image/*"
                                        onchange="previewImage(event)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="update_profile" class="btn btn-success w-100 py-2 mt-3">
                        <i class="fa fa-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        }

        <?php if (!empty($error)): ?>
            Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= $error ?>', confirmButtonColor: '#11998e' });
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= $success ?>', confirmButtonColor: '#11998e' });
        <?php endif; ?>

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