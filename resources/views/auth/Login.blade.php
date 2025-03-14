<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 800px;
            display: flex;
        }

        .logo-section {
            background-color: #4266B9;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 50%;
        }

        .logo-section img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }

        .logo-text {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .form-section {
            padding: 40px;
            width: 50%;
        }

        .form-control {
            padding: 12px;
            margin-bottom: 15px;
        }

        .btn-login {
            background-color: #4266B9;
            border-color: #4266B9;
            padding: 12px;
            width: 100%;
        }

        .btn-login:hover {
            background-color: #365796;
            border-color: #365796;
        }

        .forgot-password {
            text-align: right;
            display: block;
            margin-bottom: 15px;
            color: #4266B9;
            text-decoration: none;
        }

        .forgot-password:hover {
            color: #365796;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-section">
            <img src="{{ asset('img/sma5.png') }}" alt="School Logo">
            <div class="logo-text">E-SCHOOL</div>
        </div>
        <div class="form-section">
            <h2 class="mb-4">Login</h2>
            <form>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Password" required>
                </div>
                <a href="#" class="forgot-password">Lupa Password?</a>
                <button type="submit" class="btn btn-primary btn-login">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
