<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-School - Lupa Password</title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%234266b9' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .forgot-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 900px;
            max-width: 95%;
            margin: 20px;
            display: flex;
        }

        .logo-section {
            background-color: var(--primary);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            width: 50%;
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
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 50%;
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

        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 102, 185, 0.2);
        }

        .back-to-login {
            text-align: center;
            display: block;
            margin-top: 20px;
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-to-login:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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

        @media (max-width: 768px) {
            .forgot-card {
                flex-direction: column;
                width: 95%;
                max-width: 450px;
            }

            .logo-section, .form-section {
                width: 100%;
                padding: 30px;
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
    <div class="forgot-card">
        <div class="logo-section">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <img src="{{ asset('img/sma5.png') }}" alt="School Logo">
            <div class="logo-text">E-SCHOOL</div>
            <p class="logo-tagline">Sistem Informasi Akademik Terpadu</p>
        </div>
        <div class="form-section">
            <h2 class="login-title">Reset Password</h2>
            <p class="login-subtitle">Masukkan Email Anda dan kami akan mengirimkan link untuk mereset password Anda.</p>

            @if(session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-floating mb-4">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" placeholder="Email" value="{{ old('email') }}" required>
                    <label for="email">Email</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
                </button>
            </form>

            <a href="{{ route('login.employee') }}" class="back-to-login">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke halaman login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
