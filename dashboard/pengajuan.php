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

// ==========================
// Handle Tambah/Edit/Hapus
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function uploadFile($inputName, $oldFile = null)
    {
        $fileName = $oldFile ?? null;
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
            $fileName = time() . '_' . basename($_FILES[$inputName]['name']);
            move_uploaded_file($_FILES[$inputName]['tmp_name'], '../uploads/' . $fileName);
        }
        return $fileName;
    }

    if ($_POST['aksi'] === 'tambah') {
        $fileName = uploadFile('file_pendukung');
        $stmt = $conn->prepare("INSERT INTO pengajuan (nik, id_layanan, tanggal_pengajuan, status, keterangan, file_pendukung) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $_POST['nik'], $_POST['id_layanan'], $_POST['tanggal_pengajuan'], $_POST['status'], $_POST['keterangan'], $fileName);
        $stmt->execute();
        header("Location: pengajuan.php");
        exit;
    }

    if ($_POST['aksi'] === 'edit') {
        $fileName = uploadFile('file_pendukung', $_POST['file_lama'] ?? null);
        $stmt = $conn->prepare("UPDATE pengajuan SET nik=?, id_layanan=?, tanggal_pengajuan=?, status=?, keterangan=?, file_pendukung=? WHERE id_pengajuan=?");
        $stmt->bind_param("sissssi", $_POST['nik'], $_POST['id_layanan'], $_POST['tanggal_pengajuan'], $_POST['status'], $_POST['keterangan'], $fileName, $_POST['id_pengajuan']);
        $stmt->execute();
        header("Location: pengajuan.php");
        exit;
    }

    if ($_POST['aksi'] === 'hapus') {
        if (!empty($_POST['file_lama']) && file_exists('../uploads/' . $_POST['file_lama'])) {
            unlink('../uploads/' . $_POST['file_lama']);
        }
        $stmt = $conn->prepare("DELETE FROM pengajuan WHERE id_pengajuan=?");
        $stmt->bind_param("i", $_POST['id_pengajuan']);
        $stmt->execute();
        header("Location: pengajuan.php");
        exit;
    }
}

// Ambil data pengajuan
$query = "SELECT p.*, pd.nama_pemohon, l.nama_layanan 
          FROM pengajuan p
          JOIN penduduk pd ON p.nik = pd.nik
          JOIN layanan l ON p.id_layanan = l.id_layanan
          ORDER BY p.tanggal_pengajuan DESC";
$result = $conn->query($query);

// Ambil data penduduk & layanan untuk dropdown
$penduduk = $conn->query("SELECT nik, nama_pemohon FROM penduduk ORDER BY nama_pemohon ASC");
$layanan = $conn->query("SELECT id_layanan, nama_layanan FROM layanan ORDER BY nama_layanan ASC");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
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

        .dropzone {
            border: 2px dashed #2a5298;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .dropzone .dz-message {
            color: #2a5298;
            font-weight: 500;
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
                <a href="pengajuan.php" class="active"><i class="fa fa-file-alt me-2"></i><span>Data Pengajuan</span></a>
                <a href="admin.php"><i class="fa fa-user-shield me-2"></i><span>Data Admin</span></a>

            <?php elseif ($role === 'layanan'): ?>
                <div class="menu-divider"><span>Layanan</span></div>
                <a href="pengajuan.php" class="active"><i class="fa fa-file-alt me-2"></i><span>Pengajuan Masuk</span></a>

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
        <h5 class="fw-bold text-warning mb-0">Data Pengajuan</h5>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars($photo) ?>" alt="User" class="rounded-circle me-2" width="35"
                    height="35">
                <span class="text-warning fw-medium"><?= htmlspecialchars($user['nama_admin']) ?></span>
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
                <h4 class="fw-bold mb-1 text-warning"><i class="fa fa-file-alt me-2"></i>Data Pengajuan</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"
                                class="text-decoration-none text-warning">Dashboard</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Data Pengajuan</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambah"><i
                    class="fa fa-plus me-1"></i> Tambah Pengajuan</button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Penduduk</th>
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($r = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($r['nama_pemohon']) ?></td>
                                <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
                                <td><?= $r['tanggal_pengajuan'] ?></td>
                                <td><?= $r['status'] ?></td>
                                <td><?= htmlspecialchars($r['keterangan']) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($r['file_pendukung'])): ?>
                                        <a href="../uploads/<?= $r['file_pendukung'] ?>" target="_blank"
                                            class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fa fa-eye-slash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit<?= $r['id_pengajuan'] ?>"><i
                                                class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalHapus<?= $r['id_pengajuan'] ?>"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $r['id_pengajuan'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="aksi" value="edit">
                                            <input type="hidden" name="id_pengajuan" value="<?= $r['id_pengajuan'] ?>">
                                            <input type="hidden" name="file_lama" value="<?= $r['file_pendukung'] ?? '' ?>">
                                            <div class="modal-header bg-warning text-white">
                                                <h5>Edit Pengajuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <!-- Kiri -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label>Penduduk</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fa fa-user"></i></span>
                                                                <select name="nik" class="form-select" required>
                                                                    <?php $penduduk->data_seek(0);
                                                                    while ($p = $penduduk->fetch_assoc()): ?>
                                                                        <option value="<?= $p['nik'] ?>"
                                                                            <?= $p['nik'] == $r['nik'] ? 'selected' : '' ?>>
                                                                            <?= $p['nama_pemohon'] ?></option>
                                                                    <?php endwhile; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Layanan</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fa fa-file-alt"></i></span>
                                                                <select name="id_layanan" class="form-select" required>
                                                                    <?php $layanan->data_seek(0);
                                                                    while ($l = $layanan->fetch_assoc()): ?>
                                                                        <option value="<?= $l['id_layanan'] ?>"
                                                                            <?= $l['id_layanan'] == $r['id_layanan'] ? 'selected' : '' ?>><?= $l['nama_layanan'] ?></option>
                                                                    <?php endwhile; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Tanggal</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></span>
                                                                <input type="date" name="tanggal_pengajuan"
                                                                    class="form-control"
                                                                    value="<?= $r['tanggal_pengajuan'] ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Kanan -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label>Status</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fa fa-bolt"></i></span>
                                                                <select name="status" class="form-select">
                                                                    <?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Ditolak'] as $s): ?>
                                                                        <option value="<?= $s ?>"
                                                                            <?= $r['status'] == $s ? 'selected' : '' ?>><?= $s ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Keterangan</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fa fa-sticky-note"></i></span>
                                                                <textarea name="keterangan" class="form-control"
                                                                    rows="3"><?= $r['keterangan'] ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>File Pendukung</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fa fa-paperclip"></i></span>
                                                                <div id="dropzoneEdit<?= $r['id_pengajuan'] ?>"
                                                                    class="dropzone flex-fill"></div>
                                                                <input type="hidden" name="file_pendukung"
                                                                    id="file_pendukung_edit_<?= $r['id_pengajuan'] ?>">
                                                            </div>
                                                            <?php if (!empty($r['file_pendukung'])): ?>
                                                                <small>File saat ini: <a
                                                                        href="../uploads/<?= $r['file_pendukung'] ?>"
                                                                        target="_blank"><?= $r['file_pendukung'] ?></a></small>
                                                            <?php endif; ?>
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
                            <div class="modal fade" id="modalHapus<?= $r['id_pengajuan'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post">
                                            <input type="hidden" name="aksi" value="hapus">
                                            <input type="hidden" name="id_pengajuan" value="<?= $r['id_pengajuan'] ?>">
                                            <input type="hidden" name="file_lama" value="<?= $r['file_pendukung'] ?? '' ?>">
                                            <div class="modal-header bg-danger text-white">
                                                <h5>Hapus Pengajuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah yakin ingin menghapus pengajuan <?= $r['nama_pemohon'] ?> -
                                                <?= $r['nama_layanan'] ?>?
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
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="modal-header bg-primary text-white">
                        <h5>Tambah Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Penduduk</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <select name="nik" class="form-select" required>
                                            <?php $penduduk->data_seek(0);
                                            while ($p = $penduduk->fetch_assoc()): ?>
                                                <option value="<?= $p['nik'] ?>"><?= $p['nama_pemohon'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Layanan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                                        <select name="id_layanan" class="form-select" required>
                                            <?php $layanan->data_seek(0);
                                            while ($l = $layanan->fetch_assoc()): ?>
                                                <option value="<?= $l['id_layanan'] ?>"><?= $l['nama_layanan'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Tanggal</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        <input type="date" name="tanggal_pengajuan" class="form-control"
                                            value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-bolt"></i></span>
                                        <select name="status" class="form-select">
                                            <?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Ditolak'] as $s): ?>
                                                <option value="<?= $s ?>"><?= $s ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-sticky-note"></i></span>
                                        <textarea name="keterangan" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>File Pendukung</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-paperclip"></i></span>
                                        <div id="dropzoneTambah" class="dropzone flex-fill"></div>
                                        <input type="hidden" name="file_pendukung" id="file_pendukung_tambah">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

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

        Dropzone.autoDiscover = false;

        // Dropzone Tambah
        var dropTambah = new Dropzone("#dropzoneTambah", {
            url: "#",
            autoProcessQueue: false,
            maxFiles: 1,
            addRemoveLinks: true,
            init: function () {
                this.on("addedfile", function (file) {
                    document.getElementById('file_pendukung_tambah').files = file;
                });
                this.on("removedfile", function (file) {
                    document.getElementById('file_pendukung_tambah').value = '';
                });
            }
        });

        // Dropzone Edit
        <?php
        $result->data_seek(0);
        while ($r = $result->fetch_assoc()): ?>
            var dropEdit<?= $r['id_pengajuan'] ?> = new Dropzone("#dropzoneEdit<?= $r['id_pengajuan'] ?>", {
                url: "#",
                autoProcessQueue: false,
                maxFiles: 1,
                addRemoveLinks: true,
                init: function () {
                    this.on("addedfile", function (file) {
                        document.getElementById('file_pendukung_edit_<?= $r['id_pengajuan'] ?>').files = file;
                    });
                    this.on("removedfile", function (file) {
                        document.getElementById('file_pendukung_edit_<?= $r['id_pengajuan'] ?>').value = '';
                    });
                }
            });
        <?php endwhile; ?>

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