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

// Ambil data penduduk dan layanan
$penduduk_list = [];
$res_penduduk = $conn->query("SELECT nik, nama_pemohon FROM penduduk ORDER BY nama_pemohon ASC");
while ($p = $res_penduduk->fetch_assoc()) {
    $penduduk_list[$p['nik']] = $p['nama_pemohon'];
}

$layanan_list = [];
$res_layanan = $conn->query("SELECT id_layanan, nama_layanan FROM layanan ORDER BY nama_layanan ASC");
while ($l = $res_layanan->fetch_assoc()) {
    $layanan_list[$l['id_layanan']] = $l['nama_layanan'];
}

// Folder upload
$upload_dir = "../uploads/";
if (!is_dir($upload_dir))
    mkdir($upload_dir, 0755, true);

$status_colors = ['Menunggu' => 'warning', 'Diproses' => 'info', 'Selesai' => 'success', 'Ditolak' => 'danger'];

// Tambah Pengajuan
if (isset($_POST['tambah'])) {
    $nik = $_POST['nik'];
    $id_layanan = $_POST['id_layanan'];
    $tanggal = $_POST['tanggal_pengajuan'];
    $status = $_POST['status'];
    $keterangan = $_POST['keterangan'];

    $file_pendukung = null;
    if (isset($_FILES['file_pendukung']) && $_FILES['file_pendukung']['error'] == 0) {
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($_FILES['file_pendukung']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_ext))
            die("Tipe file tidak diperbolehkan!");
        $file_pendukung = time() . "_" . basename($_FILES['file_pendukung']['name']);
        move_uploaded_file($_FILES['file_pendukung']['tmp_name'], $upload_dir . $file_pendukung);
    }

    $stmt = $conn->prepare("INSERT INTO pengajuan (nik, id_layanan, tanggal_pengajuan, status, keterangan, file_pendukung) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $nik, $id_layanan, $tanggal, $status, $keterangan, $file_pendukung);
    $stmt->execute();
    $stmt->close();
    header("Location: status.php");
    exit;
}

// Edit Pengajuan
if (isset($_POST['edit'])) {
    $id = $_POST['id_pengajuan'];
    $status = $_POST['status'];
    $keterangan = $_POST['keterangan'];

    $stmt = $conn->prepare("SELECT file_pendukung FROM pengajuan WHERE id_pengajuan=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row_file = $res->fetch_assoc();
    $stmt->close();

    $file_pendukung = $row_file['file_pendukung'];
    if (isset($_FILES['file_pendukung']) && $_FILES['file_pendukung']['error'] == 0) {
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($_FILES['file_pendukung']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_ext))
            die("Tipe file tidak diperbolehkan!");
        if (!empty($file_pendukung) && file_exists($upload_dir . $file_pendukung))
            unlink($upload_dir . $file_pendukung);
        $file_pendukung = time() . "_" . basename($_FILES['file_pendukung']['name']);
        move_uploaded_file($_FILES['file_pendukung']['tmp_name'], $upload_dir . $file_pendukung);
    }

    $stmt = $conn->prepare("UPDATE pengajuan SET status=?, keterangan=?, file_pendukung=? WHERE id_pengajuan=?");
    $stmt->bind_param("sssi", $status, $keterangan, $file_pendukung, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: status.php");
    exit;
}

// Hapus Pengajuan
if (isset($_POST['hapus'])) {
    $id = $_POST['id_pengajuan'];
    $stmt = $conn->prepare("SELECT file_pendukung FROM pengajuan WHERE id_pengajuan=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row_file = $res->fetch_assoc();
    $stmt->close();

    if (!empty($row_file['file_pendukung']) && file_exists($upload_dir . $row_file['file_pendukung']))
        unlink($upload_dir . $row_file['file_pendukung']);

    $stmt = $conn->prepare("DELETE FROM pengajuan WHERE id_pengajuan=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: status.php");
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
            border: 2px dashed #dc3545;
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
            <a href="index.php"> <i class="fa fa-home me-2"></i>Dashboard</a>
            <?php if ($role === 'admin'): ?>
                <div class="menu-divider"><span>Manajemen Data</span></div>
                <a href="penduduk.php"> <i class="fa fa-users me-2"></i>Data Penduduk</a>
                <a href="layanan.php"> <i class="fa fa-building me-2"></i>Data Layanan</a>
                <a href="pengajuan.php"> <i class="fa fa-file-alt me-2"></i>Data Pengajuan</a>
                <a href="admin.php"><i class="fa fa-user-shield me-2"></i><span>Data Admin</span></a>
            <?php elseif ($role === 'layanan'): ?>
                <div class="menu-divider"><span>Layanan</span></div>
                <a href="pengajuan.php"> <i class="fa fa-file-alt me-2"></i>Pengajuan Masuk</a>
            <?php elseif ($role === 'penduduk'): ?>
                <div class="menu-divider"><span>Penduduk</span></div>
                <a href="buat_pengajuan.php"> <i class="fa fa-pen-to-square me-2"></i>Buat Pengajuan</a>
                <a href="status.php" class="active"> <i class="fa fa-list me-2"></i>Status Pengajuan</a>
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
        <h5 class="fw-bold text-danger mb-0">Data Status Pengajuan</h5>
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
                <h4 class="fw-bold mb-1 text-danger"><i class="fa fa-list me-2"></i>Data Status Pengajuan</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"
                                class="text-decoration-none text-danger">Dashboard</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Data Status Pengajuan</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTambah"><i
                    class="fa fa-plus me-1"></i> Tambah Status Pengajuan</button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>NIK</th>
                            <th>Nama Pemohon</th>
                            <th>Layanan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>File Pendukung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM v_pengajuan_detail ORDER BY tanggal_pengajuan DESC");
                        while ($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= $row['id_pengajuan'] ?></td>
                                <td><?= $row['nik'] ?></td>
                                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                                <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                                <td><?= $row['tanggal_pengajuan'] ?></td>
                                <td><span
                                        class="badge bg-<?= $status_colors[$row['status']] ?? 'secondary' ?>"><?= $row['status'] ?></span>
                                </td>
                                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                <td>
                                    <?php if (!empty($row['file_pendukung'])): ?>
                                        <a href="../uploads/<?= $row['file_pendukung'] ?>"
                                            target="_blank"><?= $row['file_pendukung'] ?></a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit<?= $row['id_pengajuan'] ?>"><i
                                                class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalHapus<?= $row['id_pengajuan'] ?>"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id_pengajuan'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="id_pengajuan" value="<?= $row['id_pengajuan'] ?>">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Pengajuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mb-2">
                                                            <label><i class="fa fa-info-circle"></i> Status</label>
                                                            <select name="status" class="form-select" required>
                                                                <?php foreach (array_keys($status_colors) as $s): ?>
                                                                    <option value="<?= $s ?>" <?= $s == $row['status'] ? 'selected' : '' ?>>
                                                                        <?= $s ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label><i class="fa fa-align-left"></i> Keterangan</label>
                                                            <textarea name="keterangan"
                                                                class="form-control"><?= htmlspecialchars($row['keterangan']) ?></textarea>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label><i class="fa fa-file-upload"></i> File Pendukung</label>
                                                            <div class="dropzone"
                                                                id="dropzoneEdit<?= $row['id_pengajuan'] ?>">
                                                            </div>
                                                            <?php if (!empty($row['file_pendukung'])): ?>
                                                                <small>File saat ini: <a
                                                                        href="../uploads/<?= $row['file_pendukung'] ?>"
                                                                        target="_blank"><?= $row['file_pendukung'] ?></a></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="edit" class="btn btn-warning"><i class="fa fa-save me-1"></i>Simpan Perubahan</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i>Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="modalHapus<?= $row['id_pengajuan'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post">
                                            <input type="hidden" name="id_pengajuan" value="<?= $row['id_pengajuan'] ?>">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title"><i class="fa fa-trash"></i> Hapus Pengajuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus pengajuan
                                                <strong><?= htmlspecialchars($row['nama_pemohon']) ?></strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="hapus" class="btn btn-danger"><i class="fa fa-trash me-1"></i> Ya, Hapus</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i> Batal</button>
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
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fa fa-plus"></i> Tambah Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label><i class="fa fa-id-card"></i> Penduduk</label>
                                    <select name="nik" class="form-select" required>
                                        <?php foreach ($penduduk_list as $nik => $nama): ?>
                                            <option value="<?= $nik ?>"><?= $nik ?> - <?= $nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label><i class="fa fa-cogs"></i> Layanan</label>
                                    <select name="id_layanan" class="form-select" required>
                                        <?php foreach ($layanan_list as $id => $nama): ?>
                                            <option value="<?= $id ?>"><?= $nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label><i class="fa fa-calendar-alt"></i> Tanggal Pengajuan</label>
                                    <input type="date" name="tanggal_pengajuan" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label><i class="fa fa-info-circle"></i> Status</label>
                                    <select name="status" class="form-select" required>
                                        <?php foreach (array_keys($status_colors) as $s): ?>
                                            <option value="<?= $s ?>"><?= $s ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label><i class="fa fa-align-left"></i> Keterangan</label>
                                    <textarea name="keterangan" class="form-control"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label><i class="fa fa-file-upload"></i> File Pendukung</label>
                                    <div class="dropzone" id="dropzoneTambah"></div>
                                    <input type="hidden" name="file_pendukung" id="file_pendukung_tambah">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="tambah" class="btn btn-danger"><i class="fa fa-save me-1"></i> Simpan</button>
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

            // Dropzone AutoDiscover Off
            Dropzone.autoDiscover = false;

            // Tambah Dropzone
            var dzTambah = new Dropzone("#dropzoneTambah", {
                url: "#",
                autoProcessQueue: false,
                addRemoveLinks: true,
                maxFiles: 1,
                acceptedFiles: ".pdf,.jpg,.jpeg,.png",
                init: function () {
                    this.on("addedfile", function (file) { $('#file_pendukung_tambah').val(file.name); });
                    this.on("removedfile", function (file) { $('#file_pendukung_tambah').val(''); });
                }
            });

            // Edit Dropzones
            <?php
            $res = $conn->query("SELECT id_pengajuan FROM v_pengajuan_detail");
            while ($row = $res->fetch_assoc()):
                ?>
                var dzEdit<?= $row['id_pengajuan'] ?> = new Dropzone("#dropzoneEdit<?= $row['id_pengajuan'] ?>", {
                    url: "#",
                    autoProcessQueue: false,
                    addRemoveLinks: true,
                    maxFiles: 1,
                    acceptedFiles: ".pdf,.jpg,.jpeg,.png",
                    init: function () {
                        this.on("addedfile", function (file) { $('#file_pendukung_edit_<?= $row['id_pengajuan'] ?>').val(file.name); });
                        this.on("removedfile", function (file) { $('#file_pendukung_edit_<?= $row['id_pengajuan'] ?>').val(''); });
                    }
                });
            <?php endwhile; ?>
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