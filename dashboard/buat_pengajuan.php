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

$layanan_res = $conn->query("SELECT * FROM layanan ORDER BY nama_layanan ASC");

if (isset($_POST['submit_pengajuan'])) {
    $nik = $_SESSION['user']['username'];
    $keterangan = $_POST['keterangan'];
    $id_layanan = $_POST['id_layanan'];
    $file_pendukung = '';

    if (isset($_FILES['file_pendukung']) && $_FILES['file_pendukung']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir))
            mkdir($target_dir, 0777, true);
        $file_name = time() . "_" . basename($_FILES['file_pendukung']['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['file_pendukung']['tmp_name'], $target_file)) {
            $file_pendukung = $file_name;
        } else {
            echo "<script>alert('Gagal upload file!');</script>";
        }
    }

    $stmt = $conn->prepare("INSERT INTO pengajuan (nik, id_layanan, keterangan, file_pendukung) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $nik, $id_layanan, $keterangan, $file_pendukung);
    if ($stmt->execute()) {
        echo "<script>alert('Pengajuan berhasil dibuat!'); window.location='status.php';</script>";
    } else {
        echo "<script>alert('Gagal membuat pengajuan: " . $conn->error . "');</script>";
    }
    $stmt->close();
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
                <a href="buat_pengajuan.php" class="active"><i class="fa fa-edit me-2"></i><span>Buat Pengajuan</span></a>
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
        <div class="page-header">
            <div>
                <h4 class="fw-bold mb-1 text-danger"><i class="fa fa-pen-to-square me-2"></i>Buat Pengajuan</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"
                                class="text-decoration-none text-danger">Dashboard</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Buat Pengajuan</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">

            <!-- Nama Pemohon -->
            <div class="mb-3">
                <label class="form-label">Nama Pemohon</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['nama_admin']) ?>"
                        disabled>
                </div>
            </div>

            <!-- Pilih Layanan -->
            <div class="mb-3">
                <label for="id_layanan" class="form-label">Pilih Layanan</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-clipboard-list"></i></span>
                    <select name="id_layanan" id="id_layanan" class="form-select" required>
                        <option value="">-- Pilih Layanan --</option>
                        <?php while ($layanan = $layanan_res->fetch_assoc()): ?>
                            <option value="<?= $layanan['id_layanan'] ?>"><?= htmlspecialchars($layanan['nama_layanan']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3"
                        placeholder="Isi keterangan pengajuan..." required></textarea>
                </div>
            </div>

            <!-- Upload File Pendukung -->
            <div class="mb-3">
                <label for="file_pendukung" class="form-label">Upload File Pendukung (opsional)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-upload"></i></span>
                    <input type="file" name="file_pendukung" id="file_pendukung" class="form-control">
                </div>
            </div>

            <button type="submit" name="submit_pengajuan" class="btn btn-primary"><i class="fa fa-paper-plane me-1"></i>
                Kirim Pengajuan</button>
            <a href="status.php" class="btn btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
        </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "pageLength": 10,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "order": [[1, "asc"]],
                "columnDefs": [{ "orderable": false, "targets": 4 }],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ baris",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "paginate": { "first": "Pertama", "last": "Terakhir", "next": "Berikutnya", "previous": "Sebelumnya" }
                }
            });
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