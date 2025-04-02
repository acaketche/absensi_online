<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4266B9;
            --primary-dark: #365796;
            --primary-light: #5A7BC7;
            --accent: #FF9F43;
            --text-dark: #2D3748;
            --text-light: #718096;
            --bg-light: #F8FAFC;
            --white: #FFFFFF;
            --success: #10B981;
            --danger: #EF4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%234266b9' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .login-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 900px;
            display: flex;
            max-width: 95%;
            margin: 20px;
            position: relative;
        }

        .logo-section {
            background-color: var(--primary);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 50%;
            position: relative;
            overflow: hidden;
        }

        .logo-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: 1;
        }

        .logo-section img {
            width: 140px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            position: relative;
            z-index: 2;
            transition: transform 0.3s ease;
        }

        .logo-section img:hover {
            transform: scale(1.05);
        }

        .logo-text {
            color: var(--white);
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .logo-tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 300;
            text-align: center;
            max-width: 80%;
            position: relative;
            z-index: 2;
        }

        .form-section {
            padding: 50px 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 30px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating label {
            color: var(--text-light);
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            padding: 12px 16px;
            height: 56px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(66, 102, 185, 0.15);
        }

        .btn-login {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.6s ease;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 102, 185, 0.2);
        }

        .btn-login:hover::after {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: right;
            display: block;
            margin-bottom: 20px;
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: var(--text-light);
            font-size: 14px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #E2E8F0;
        }

        .divider::before {
            margin-right: 15px;
        }

        .divider::after {
            margin-left: 15px;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            background-color: var(--white);
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background-color: #F8FAFC;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            border: none;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #FEE2E2;
            color: #DC2626;
        }

        .alert-success {
            background-color: #DCFCE7;
            color: #16A34A;
        }

        .input-group-text {
            background-color: transparent;
            border-left: none;
            cursor: pointer;
            color: var(--text-light);
        }

        .password-toggle {
            border-left: none;
            background-color: transparent;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .circle-1 {
            width: 150px;
            height: 150px;
            bottom: -50px;
            right: -50px;
        }

        .circle-2 {
            width: 100px;
            height: 100px;
            top: 50px;
            left: -30px;
        }

        /* Modal styles */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #E2E8F0;
            padding: 20px 30px;
        }

        .modal-title {
            font-weight: 600;
            color: var(--text-dark);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            border-top: 1px solid #E2E8F0;
            padding: 20px 30px;
        }

        .btn-secondary {
            background-color: #E2E8F0;
            color: var(--text-dark);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #CBD5E1;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 102, 185, 0.2);
        }

        .password-requirements {
            margin-top: 15px;
            padding: 15px;
            background-color: #F8FAFC;
            border-radius: 8px;
            font-size: 13px;
        }

        .password-requirements ul {
            padding-left: 20px;
            margin-bottom: 0;
        }

        .password-requirements li {
            margin-bottom: 5px;
        }

        .password-requirements li:last-child {
            margin-bottom: 0;
        }

        .password-strength {
            height: 5px;
            border-radius: 5px;
            margin-top: 10px;
            background-color: #E2E8F0;
            overflow: hidden;
        }

        .password-strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .strength-weak {
            background-color: var(--danger);
            width: 33%;
        }

        .strength-medium {
            background-color: var(--accent);
            width: 66%;
        }

        .strength-strong {
            background-color: var(--success);
            width: 100%;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                width: 95%;
                max-width: 450px;
            }

            .logo-section, .form-section {
                width: 100%;
                padding: 30px;
            }

            .logo-section {
                padding-top: 40px;
                padding-bottom: 40px;
            }

            .logo-section img {
                width: 100px;
            }

            .logo-text {
                font-size: 24px;
            }

            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-section">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <img src="{{ asset('img/sma5.png') }}" alt="School Logo">
            <div class="logo-text">E-SCHOOL</div>
            <p class="logo-tagline">Sistem Informasi Akademik Terpadu</p>
        </div>
        <div class="form-section">
            <h2 class="login-title">Selamat Datang</h2>
            <p class="login-subtitle">Silakan masuk untuk mengakses sistem</p>

            @if(session('loginError'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('loginError') }}
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('login.employee') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" name="id_employee" class="form-control @error('id_employee') is-invalid @enderror"
                        id="id_employee" placeholder="NIP" value="{{ old('id_employee') }}" required>
                    <label for="id_employee">NIP</label>
                    @error('id_employee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <span class="password-toggle" onclick="togglePassword()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i id="toggleIcon" class="fas fa-eye"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember" style="font-size: 14px; color: var(--text-light);">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="forgot-password" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Lupa Password -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Lupa Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-4">Masukkan Email Anda untuk menerima kode verifikasi reset password.</p>

                    <div id="forgotPasswordAlert" class="alert alert-success d-none">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="forgotPasswordAlertMessage"></span>
                    </div>

                    <form id="forgotPasswordForm" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="forgot_id_employee" class="form-label">Email</label>
                            <input type="text" class="form-control" id="forgot_id_employee" name="id_employee" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="sendResetLinkBtn">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-4">Silakan buat password baru untuk akun Anda.</p>

                    <form id="resetPasswordForm" action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" id="reset_token">
                        <input type="hidden" name="id_employee" id="reset_id_employee">

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="password" required minlength="8" onkeyup="checkPasswordStrength()">
                                <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('new_password', 'newPasswordToggleIcon')">
                                    <i id="newPasswordToggleIcon" class="fas fa-eye"></i>
                                </button>
                            </div>

                            <div class="password-strength mt-2">
                                <div id="passwordStrengthMeter" class="password-strength-meter"></div>
                            </div>
                            <small id="passwordStrengthText" class="form-text text-muted mt-1">Kekuatan password: Belum diisi</small>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8" onkeyup="checkPasswordMatch()">
                                <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('password_confirmation', 'confirmPasswordToggleIcon')">
                                    <i id="confirmPasswordToggleIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small id="passwordMatchText" class="form-text text-muted mt-1">Password belum dicocokkan</small>
                        </div>

                        <div class="password-requirements">
                            <p class="mb-2 fw-medium">Password harus memenuhi kriteria berikut:</p>
                            <ul>
                                <li>Minimal 8 karakter</li>
                                <li>Mengandung setidaknya 1 huruf besar</li>
                                <li>Mengandung setidaknya 1 huruf kecil</li>
                                <li>Mengandung setidaknya 1 angka</li>
                                <li>Mengandung setidaknya 1 karakter khusus (!@#$%^&*)</li>
                            </ul>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary" id="resetPasswordBtn">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            let passwordInput = document.getElementById("password");
            let toggleIcon = document.getElementById("toggleIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        function togglePasswordVisibility(inputId, iconId) {
            let passwordInput = document.getElementById(inputId);
            let toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        // Check password strength
        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            const meter = document.getElementById('passwordStrengthMeter');
            const strengthText = document.getElementById('passwordStrengthText');

            // Reset
            meter.className = 'password-strength-meter';

            if (password.length === 0) {
                strengthText.textContent = 'Kekuatan password: Belum diisi';
                return;
            }

            // Calculate strength
            let strength = 0;

            // Length check
            if (password.length >= 8) strength += 1;

            // Character type checks
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            // Update UI based on strength
            if (strength <= 2) {
                meter.classList.add('strength-weak');
                strengthText.textContent = 'Kekuatan password: Lemah';
                strengthText.style.color = '#EF4444';
            } else if (strength <= 3) {
                meter.classList.add('strength-medium');
                strengthText.textContent = 'Kekuatan password: Sedang';
                strengthText.style.color = '#FF9F43';
            } else {
                meter.classList.add('strength-strong');
                strengthText.textContent = 'Kekuatan password: Kuat';
                strengthText.style.color = '#10B981';
            }
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('passwordMatchText');

            if (confirmPassword.length === 0) {
                matchText.textContent = 'Password belum dicocokkan';
                matchText.style.color = '';
                return;
            }

            if (password === confirmPassword) {
                matchText.textContent = 'Password cocok';
                matchText.style.color = '#10B981';
            } else {
                matchText.textContent = 'Password tidak cocok';
                matchText.style.color = '#EF4444';
            }
        }

        // Validate password strength
        function isStrongPassword(password) {
            const minLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /[0-9]/.test(password);
            const hasSpecialChar = /[!@#$%^&*]/.test(password);

            return minLength && hasUpperCase && hasLowerCase && hasNumbers && hasSpecialChar;
        }

        // Handle forgot password form submission
        document.addEventListener('DOMContentLoaded', function() {
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');
            if (forgotPasswordForm) {
                forgotPasswordForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const idEmployee = document.getElementById('forgot_id_employee').value;

                    // Show loading state
                    const submitBtn = document.getElementById('sendResetLinkBtn');
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';
                    submitBtn.disabled = true;

                    // Simulate API call to send reset link
                    // In a real application, this would be an actual form submission
                    setTimeout(function() {
                        // Show success message
                        const alertBox = document.getElementById('forgotPasswordAlert');
                        const alertMessage = document.getElementById('forgotPasswordAlertMessage');
                        alertBox.classList.remove('d-none');
                        alertBox.classList.remove('alert-danger');
                        alertBox.classList.add('alert-success');
                        alertMessage.textContent = 'Link reset password telah dikirim ke email Anda.';

                        // Reset button state
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Link Reset';
                        submitBtn.disabled = false;

                        // Open reset password modal after delay
                        setTimeout(function() {
                            // Close the forgot password modal
                            const forgotPasswordModal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
                            forgotPasswordModal.hide();

                            // Open the reset password modal
                            const resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));

                            // Set the token and employee ID
                            document.getElementById('reset_token').value = 'simulated-token-' + Date.now();
                            document.getElementById('reset_id_employee').value = idEmployee;

                            resetPasswordModal.show();
                        }, 2000);
                    }, 1500);
                });
            }

            // Handle reset password form submission
            const resetPasswordForm = document.getElementById('resetPasswordForm');
            if (resetPasswordForm) {
                resetPasswordForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const newPassword = document.getElementById('new_password').value;
                    const confirmPassword = document.getElementById('password_confirmation').value;

                    // Validate password
                    if (newPassword !== confirmPassword) {
                        alert('Password dan konfirmasi password tidak cocok.');
                        return;
                    }

                    if (!isStrongPassword(newPassword)) {
                        alert('Password tidak memenuhi persyaratan keamanan.');
                        return;
                    }

                    // Show loading state
                    const resetBtn = document.getElementById('resetPasswordBtn');
                    resetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                    resetBtn.disabled = true;

                    // Simulate password reset
                    // In a real application, this would be an actual form submission
                    setTimeout(function() {
                        // Close the reset password modal
                        const resetPasswordModal = bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal'));
                        resetPasswordModal.hide();

                        // Show success message on the login page
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success';
                        successAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i>Password berhasil direset. Silakan login dengan password baru Anda.';

                        const formSection = document.querySelector('.form-section');
                        const loginSubtitle = document.querySelector('.login-subtitle');

                        formSection.insertBefore(successAlert, loginSubtitle.nextSibling);

                        // Auto-fill the employee ID
                        document.getElementById('id_employee').value = document.getElementById('reset_id_employee').value;
                        document.getElementById('password').focus();
                    }, 1500);
                });
            }
        });
    </script>
</body>
</html>
