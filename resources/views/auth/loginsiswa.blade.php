<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa | E-School</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4266B9;
            --primary-light: #5a7fd1;
            --secondary-color: #3A5A9A;
            --accent-color: #4cc9f0;
            --text-color: #2b2d42;
            --text-light: #6c757d;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --border-color: #e0e0e0;
            --dark-blue: #1E3A8A;
            --error-color: #dc3545;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-blue));
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            padding: 1rem;
            margin: 0;
            line-height: 1.6;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            padding: 2.5rem;
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            position: relative;
            overflow: hidden;
            border: none;
        }

        .login-header {
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            margin: 1.5rem auto 0;
            border-radius: 2px;
        }

        .school-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin: 0 auto 1.25rem;
            display: block;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            padding: 8px;
            background-color: var(--white);
            box-shadow: 0 4px 12px rgba(66, 102, 185, 0.15);
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-light);
            font-weight: 400;
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(66, 102, 185, 0.15);
            outline: none;
        }

        .input-icon {
            position: absolute;
            right: 1rem;
            top: 70%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            font-size: 1rem;
            color: var(--white);
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 102, 185, 0.3);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 0.5rem;
            width: 1.1em;
            height: 1.1em;
        }

        .form-check-label {
            color: var(--text-light);
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
            color: var(--secondary-color);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }

        .error-message i {
            margin-right: 0.5rem;
        }

        /* Dark mode styles */
        body.dark-mode {
            background: linear-gradient(135deg, #1a2e5a, #0d1a3d);
        }

        body.dark-mode .login-container {
            background: #1e1e1e;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .form-control {
            background-color: #2a2a2a;
            border-color: #3a3a3a;
            color: #f0f0f0;
        }

        body.dark-mode .form-control:focus {
            background-color: #2a2a2a;
        }

        body.dark-mode .form-label,
        body.dark-mode .login-title {
            color: #f0f0f0;
        }

        body.dark-mode .login-subtitle,
        body.dark-mode .form-check-label,
        body.dark-mode .login-footer {
            color: #aaa;
        }

        body.dark-mode .login-footer {
            border-top-color: #3a3a3a;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-container {
                padding: 1.75rem;
            }

            .school-logo {
                width: 80px;
                height: 80px;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('img/sma5.png') }}" alt="Logo Sekolah" class="school-logo">
            <h1 class="login-title">E-School Portal</h1>
            <p class="login-subtitle">Masuk ke akun siswa Anda</p>
        </div>

        <form method="POST" action="{{ route('login.student') }}">
            @csrf

            <div class="form-group">
                <label for="id_student" class="form-label">NIPD</label>
                <input type="text" class="form-control" id="id_student" name="id_student" placeholder="Masukkan NIPD" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                <i class="fas fa-eye input-icon" id="togglePassword"></i>
            </div>

            <div class="form-options">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Ingat saya</label>
                </div>
                <a href="#" class="forgot-password">Lupa password?</a>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>MASUK
            </button>

            @error('login')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Add focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.querySelector('.form-label').style.color = 'var(--primary-color)';
            });

            input.addEventListener('blur', function() {
                this.parentNode.querySelector('.form-label').style.color = 'var(--text-color)';
            });
        });
    </script>
</body>
</html>
