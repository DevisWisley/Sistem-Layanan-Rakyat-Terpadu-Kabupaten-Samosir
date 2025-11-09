<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-white" href="../dashboard/index.php">
            <i class="fa-solid fa-building-user"></i> Layanan Rakyat Samosir
        </a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">
                <i class="fa fa-user-circle"></i> <?= $_SESSION['user']['nama_admin'] ?? 'User'; ?>
                (<?= ucfirst($_SESSION['role'] ?? '-') ?>)
            </span>
            <a href="../auth/logout.php" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>