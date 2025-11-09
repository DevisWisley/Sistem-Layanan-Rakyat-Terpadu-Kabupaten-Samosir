<?php
include '../config/db.php';
session_start();

if (isset($_POST['register'])) {
    $nama = $conn->real_escape_string(trim($_POST['nama_admin']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    $jabatan = $conn->real_escape_string(trim($_POST['jabatan']));
    $role = $conn->real_escape_string(trim($_POST['role']));
    $password = $conn->real_escape_string(trim($_POST['password']));
    $confirm = $conn->real_escape_string(trim($_POST['confirm_password']));

    // Handle photo upload
    $photoName = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/' . $photoName);
    }

    if ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $cek = $conn->query("SELECT * FROM admin WHERE username='$username'");
        if ($cek && $cek->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $sql = "INSERT INTO admin (nama_admin, username, jabatan, password, role, photo)
                    VALUES ('$nama', '$username', '$jabatan', '$password', '$role', '$photoName')";
            if ($conn->query($sql)) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Terjadi kesalahan: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi | Sistem Layanan Rakyat Terpadu Samosir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Background Gradient Animation */
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #0f2027);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating Particles */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            animation: float 10s linear infinite;
            pointer-events: none;
        }

        @keyframes float {
            0% {
                transform: translateY(0) scale(1);
                opacity: 0.6;
            }

            50% {
                transform: translateY(-200px) scale(1.2);
                opacity: 0.3;
            }

            100% {
                transform: translateY(-400px) scale(1);
                opacity: 0;
            }
        }

        .register-container {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            display: flex;
            max-width: 1000px;
            width: 100%;
            overflow: hidden;
            position: relative;
            z-index: 2;
        }

        .register-image {
            flex: 1;
            background: url('https://wallpapercave.com/wp/wp6057209.jpg') center center/cover no-repeat;
            min-height: 500px;
        }

        .register-form {
            flex: 2;
            padding: 2.5rem;
            position: relative;
            z-index: 3;
        }

        h3 {
            font-weight: 700;
            color: #2c5364;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 10px 15px;
        }

        .btn-success {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            border: none;
            transition: all 0.3s;
        }

        .btn-success:hover {
            transform: scale(1.03);
            background: linear-gradient(45deg, #38ef7d, #11998e);
        }

        .text-muted a {
            color: #11998e;
            font-weight: 600;
            text-decoration: none;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        .img-preview {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 12px;
            margin-top: 10px;
        }

        @media(max-width:768px) {
            .register-container {
                flex-direction: column;
            }

            .register-image {
                min-height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-image d-none d-md-block"></div>

        <div class="register-form">
            <div class="text-center mb-4">
                <i class="fa-solid fa-users-gear fa-3x text-success mb-2"></i>
                <h3 class="text-success">Registrasi Akun Baru</h3>
                <p class="text-muted">Sistem Layanan Rakyat Terpadu Kabupaten Samosir</p>
            </div>

            <form method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="row g-3">
                    <!-- Kolom Kiri -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" name="nama_admin" class="form-control"
                                    placeholder="Masukkan nama lengkap..." required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-id-badge"></i></span>
                                <input type="text" name="username" class="form-control"
                                    placeholder="Masukkan username unik..." required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan / Instansi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-briefcase"></i></span>
                                <input type="text" name="jabatan" class="form-control"
                                    placeholder="Contoh: Operator Dinas Perizinan">
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Tengah -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user-shield"></i></span>
                                <select name="role" class="form-select" required>
                                    <option value="" disabled selected>Pilih peran akun</option>
                                    <option value="penduduk">Penduduk</option>
                                    <option value="layanan">Layanan</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-key"></i></span>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukkan password..." required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" name="confirm_password" class="form-control"
                                    placeholder="Ulangi password..." required>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Upload Foto Profil</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-image"></i></span>
                                <input type="file" name="photo" class="form-control" accept="image/*"
                                    onchange="previewImage(event)">
                            </div>
                        </div>
                        <img id="preview" class="img-preview" src="#" alt="Preview Foto" style="display:none;">
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-success w-100 py-2 mt-2">
                    <i class="fa fa-user-plus me-1"></i> Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-muted mt-3 mb-0">
                Sudah punya akun? <a href="login.php">Login</a>
            </p>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }

        // Floating particles
        for (let i = 0; i < 20; i++) {
            let particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.width = Math.random() * 20 + 10 + 'px';
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDuration = 5 + Math.random() * 10 + 's';
            document.body.appendChild(particle);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if (!empty($error)): ?>
            Swal.fire({ icon: 'error', title: 'Registrasi Gagal', text: '<?= $error ?>', confirmButtonColor: '#11998e' });
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= $_SESSION['success'] ?>', confirmButtonColor: '#11998e' });
            <?php unset($_SESSION['success']); endif; ?>
    </script>
</body>

</html>