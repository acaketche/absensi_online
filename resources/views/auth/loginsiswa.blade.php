<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Login Siswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --text-color: #2b2d42;
            --light-gray: #f8f9fa;
            --white: #ffffff;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            padding: 20px;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 40px;
            background-color: var(--white);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .login-logo i {
            font-size: 2.5rem;
            color: var(--white);
        }

        .login-logo h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .login-logo p {
            color: #6c757d;
            font-weight: 400;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-control {
            height: 50px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            height: 50px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .login-footer a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .school-info {
            text-align: center;
            margin-top: 40px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        .alert {
            border-radius: 8px;
            margin-top: 20px;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container {
            animation: fadeIn 0.6s ease-out;
        }

        /* Mobile Responsiveness */
        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-logo .logo-icon {
                width: 70px;
                height: 70px;
            }

            .login-logo i {
                font-size: 2rem;
            }

            .login-logo h2 {
                font-size: 1.5rem;
            }

            body {
                padding: 15px;
                background: var(--white);
            }

            .school-info {
                color: #6c757d;
                margin-top: 30px;
            }
        }

        /* Dark mode toggle */
        .dark-mode-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            color: var(--text-color);
            font-size: 1.2rem;
        }

        body.dark-mode {
            background: #121212;
            color: #f5f5f5;
        }

        body.dark-mode .login-container {
            background: #1e1e1e;
            color: #f5f5f5;
        }

        body.dark-mode .login-logo h2,
        body.dark-mode .form-floating label {
            color: #f5f5f5;
        }

        body.dark-mode .form-control {
            background-color: #2d2d2d;
            border-color: #444;
            color: #f5f5f5;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="dark-mode-toggle" id="darkModeToggle">
            <i class="fas fa-moon"></i>
        </div>

        <div class="login-logo">
            <div class="logo-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h2>E-School Portal</h2>
            <p>Masuk ke akun siswa Anda</p>
        </div>

        <form method="POST" action="{{ route('login.student.post') }}">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="id_student" name="id_student" placeholder="NIS / Username" required>
                <label for="id_student"><i class="fas fa-id-card me-2"></i>NIPD</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Ingat saya
                    </label>
                </div>
                <a href="#" class="text-decoration-none small">Lupa password?</a>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </div>
        </form>

        <div class="login-footer">
            <p>&copy; 2025 E-School Portal. All rights reserved.</p>
        </div>

        @error('login')
            <div class="alert alert-danger mt-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
            </div>
        @enderror
        </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // Check for saved user preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');

            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        });

        // Input focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
        });

        // Show password toggle (optional)
        const passwordInput = document.getElementById('password');
        const showPassword = document.createElement('span');
        showPassword.innerHTML = '<i class="far fa-eye"></i>';
        showPassword.style.position = 'absolute';
        showPassword.style.right = '15px';
        showPassword.style.top = '50%';
        showPassword.style.transform = 'translateY(-50%)';
        showPassword.style.cursor = 'pointer';
        showPassword.style.color = '#6c757d';

        passwordInput.parentElement.style.position = 'relative';
        passwordInput.parentElement.appendChild(showPassword);

        showPassword.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="far fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="far fa-eye"></i>';
            }
        });
    </script>
</body>
</html>
