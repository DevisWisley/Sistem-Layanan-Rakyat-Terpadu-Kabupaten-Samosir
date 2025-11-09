<?php
session_start();
include '../config/db.php';

if (isset($_POST['login'])) {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);
    $role = $conn->real_escape_string(trim($_POST['role']));

    $query = $conn->query("SELECT * FROM admin WHERE username = '$username' AND role = '$role'");

    if ($query && $query->num_rows > 0) {
        $user = $query->fetch_assoc();

        if ($password === $user['password']) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            $_SESSION['role'] = $user['role'];

            header("Location: ../dashboard/index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username atau Role tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sistem Layanan Rakyat Terpadu Samosir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #1e3c72, #2a5298, #1e3c72, #2a5298);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            position: relative;
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

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            animation: float 10s linear infinite;
            pointer-events: none;
        }

        @keyframes float {
            0% {
                transform: translateY(0) scale(1);
                opacity: 0.7;
            }

            50% {
                transform: translateY(-200px) scale(1.2);
                opacity: 0.4;
            }

            100% {
                transform: translateY(-400px) scale(1);
                opacity: 0;
            }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            max-width: 900px;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        .login-image {
            flex: 1;
            background: url('https://wallpapercave.com/wp/wp5842033.jpg') center center/cover no-repeat;
            min-height: 400px;
        }

        .login-form {
            flex: 1;
            padding: 3rem;
            position: relative;
            z-index: 3;
        }

        .login-form h3 {
            font-weight: 700;
            color: #2a5298;
        }

        .btn-primary {
            background: linear-gradient(45deg, #2a5298, #1e3c72);
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: scale(1.03);
            background: linear-gradient(45deg, #1e3c72, #2a5298);
        }

        .form-control,
        .input-group-text,
        .form-select {
            border-radius: 12px;
            padding: 10px 15px;
        }

        @media(max-width:768px) {
            .login-container {
                flex-direction: column;
            }

            .login-image {
                min-height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-image d-none d-md-block"></div>

        <div class="login-form">
            <div class="text-center mb-4">
                <i class="fa-solid fa-building-columns fa-3x text-primary mb-2"></i>
                <h3>Login Akun</h3>
                <p class="text-muted">Sistem Layanan Rakyat Terpadu Kabupaten Samosir</p>
            </div>

            <form method="post" autocomplete="off">
                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username..."
                            required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password..."
                            required>
                    </div>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user-shield"></i></span>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Pilih role</option>
                            <option value="admin">Admin</option>
                            <option value="layanan">Layanan</option>
                            <option value="penduduk">Penduduk</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 py-2">
                    <i class="fa fa-right-to-bracket me-1"></i> Masuk Sekarang
                </button>
            </form>

            <p class="text-center mt-3 mb-0">
                Belum punya akun? <a href="register.php"
                    class="text-decoration-none text-primary fw-semibold">Daftar</a>
            </p>
        </div>
    </div>

    <!-- Floating Particles -->
    <script>
        for (let i = 0; i < 15; i++) {
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (!empty($error)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: '<?= $error ?>',
                confirmButtonColor: '#2a5298'
            });
        <?php endif; ?>
    </script>
</body>

</html>