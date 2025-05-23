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
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo i {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 15px;
        }
        .login-logo h2 {
            font-size: 1.8rem;
            color: #212529;
            margin-bottom: 5px;
        }
        .login-logo p {
            color: #6c757d;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .school-info {
            position: absolute;
            bottom: 20px;
            text-align: center;
            width: 100%;
            color: #6c757d;
        }
        @media (max-width: 576px) {
            .login-container {
                margin: 20px;
                padding: 20px;
            }
            .school-info {
                position: relative;
                margin-top: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <i class="fas fa-school"></i>
            <h2>E-School Portal</h2>
            <p>Portal Informasi Siswa</p>
        </div>

        <form  method="POST" action="{{ route('login.student.post') }}">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="id_student" placeholder="NIS / Username" required>
                <label for="username">NIS / Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Ingat saya
                </label>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
            </div>
        </form>

        <div class="login-footer">
            <p>Lupa password? Hubungi administrator sekolah.</p>
        </div>

        @error('login')
            <div class="alert alert-danger mt-3" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="school-info">
        <p>&copy; 2025 E-School Portal. All rights reserved.</p>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
