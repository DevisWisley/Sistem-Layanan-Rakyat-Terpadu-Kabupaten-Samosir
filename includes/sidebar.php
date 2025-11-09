<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="../dashboard/index.php"><i class="fa fa-home me-2"></i> Dashboard</a>
        </li>

        <?php if ($_SESSION['role'] == 'admin'): ?>
            <li class="nav-item"><a href="../dashboard/penduduk.php"><i class="fa fa-users me-2"></i> Data Penduduk</a></li>
            <li class="nav-item"><a href="../dashboard/layanan.php"><i class="fa fa-briefcase me-2"></i> Data Layanan</a>
            </li>
            <li class="nav-item"><a href="../dashboard/pengajuan.php"><i class="fa fa-folder-open me-2"></i> Data
                    Pengajuan</a></li>
            <li class="nav-item"><a href="../dashboard/admin.php"><i class="fa fa-user-shield me-2"></i> Data Admin</a></li>
        <?php elseif ($_SESSION['role'] == 'penduduk'): ?>
            <li class="nav-item"><a href="../dashboard/pengajuan.php"><i class="fa fa-file-alt me-2"></i> Pengajuan Saya</a>
            </li>
        <?php elseif ($_SESSION['role'] == 'layanan'): ?>
            <li class="nav-item"><a href="../dashboard/pengajuan.php"><i class="fa fa-tasks me-2"></i> Kelola Pengajuan</a>
            </li>
        <?php endif; ?>
    </ul>
</div>