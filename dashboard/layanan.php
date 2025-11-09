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

// Tambah Layanan
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, kategori, deskripsi, syarat_dokumen) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['nama_layanan'], $_POST['kategori'], $_POST['deskripsi'], $_POST['syarat_dokumen']);
    $stmt->execute();
    header("Location: layanan.php?msg=added");
    exit;
}

// Edit Layanan
if (isset($_POST['aksi']) && $_POST['aksi'] === 'edit') {
    $stmt = $conn->prepare("UPDATE layanan SET nama_layanan=?, kategori=?, deskripsi=?, syarat_dokumen=? WHERE id_layanan=?");
    $stmt->bind_param("ssssi", $_POST['nama_layanan'], $_POST['kategori'], $_POST['deskripsi'], $_POST['syarat_dokumen'], $_POST['id_layanan']);
    $stmt->execute();
    header("Location: layanan.php?msg=updated");
    exit;
}

// Hapus Layanan
if (isset($_POST['aksi']) && $_POST['aksi'] === 'hapus') {
    $stmt = $conn->prepare("DELETE FROM layanan WHERE id_layanan=?");
    $stmt->bind_param("i", $_POST['id_layanan']);
    $stmt->execute();
    header("Location: layanan.php?msg=deleted");
    exit;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <a href="layanan.php" class="active"><i class="fa fa-building me-2"></i><span>Data Layanan</span></a>
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
        <h5 class="fw-bold text-success mb-0">Data Layanan</h5>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars($photo) ?>" alt="User" class="rounded-circle me-2" width="35"
                    height="35">
                <span class="text-success fw-medium"><?= htmlspecialchars($user['nama_admin']) ?></span>
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
                <h4 class="fw-bold mb-1 text-success"><i class="fa fa-building me-2"></i>Data Layanan</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"
                                class="text-decoration-none text-success">Dashboard</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Data Layanan</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah"><i
                    class="fa fa-plus me-1"></i> Tambah Layanan</button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Layanan</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Syarat Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $res = $conn->query("SELECT * FROM layanan");
                        while ($r = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $r['nama_layanan'] ?></td>
                                <td><?= $r['kategori'] ?></td>
                                <td><?= $r['deskripsi'] ?></td>
                                <td><?= $r['syarat_dokumen'] ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit<?= $r['id_layanan'] ?>"><i
                                                class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalHapus<?= $r['id_layanan'] ?>"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $r['id_layanan'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="post">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title">Edit Layanan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="aksi" value="edit">
                                                <input type="hidden" name="id_layanan" value="<?= $r['id_layanan'] ?>">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label>Nama Layanan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fa fa-building"></i></span>
                                                            <input type="text" name="nama_layanan" class="form-control"
                                                                value="<?= $r['nama_layanan'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Kategori</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fa fa-list"></i></span>
                                                            <input type="text" name="kategori" class="form-control"
                                                                value="<?= $r['kategori'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Deskripsi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fa fa-align-left"></i></span>
                                                            <textarea name="deskripsi" class="form-control"
                                                                required><?= $r['deskripsi'] ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Syarat Dokumen</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fa fa-file-alt"></i></span>
                                                            <textarea name="syarat_dokumen" class="form-control"
                                                                required><?= $r['syarat_dokumen'] ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i> Simpan Perubahan</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal"><i class="fa fa-times me-1"></i> Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="modalHapus<?= $r['id_layanan'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Hapus Layanan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="aksi" value="hapus">
                                                <input type="hidden" name="id_layanan" value="<?= $r['id_layanan'] ?>">
                                                <p>Apakah Anda yakin ingin menghapus layanan
                                                    <strong><?= $r['nama_layanan'] ?></strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash me-1"></i> Ya, Hapus</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal"><i class="fa fa-times me-1"></i> Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Tambah Layanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="aksi" value="tambah">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Nama Layanan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-building"></i></span>
                                    <input type="text" name="nama_layanan" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Kategori</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-list"></i></span>
                                    <input type="text" name="kategori" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Deskripsi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-align-left"></i></span>
                                    <textarea name="deskripsi" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Syarat Dokumen</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                                    <textarea name="syarat_dokumen" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save me-1"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i> Batal</button>
                    </div>
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

            $('#logoutBtn').click(function () {
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
            });
        });
    </script>

</body>

</html>