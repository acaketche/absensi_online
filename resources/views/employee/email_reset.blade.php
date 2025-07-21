<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password E-School</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-block;
            background-color: #4266B9;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 15px 0;
        }
        .info-box {
            background-color: #F8FAFC;
            border-left: 4px solid #4266B9;
            padding: 12px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/sma5.png') }}" alt="E-School Logo" class="logo">
            <h2 style="color: #4266B9; margin-bottom: 5px;">E-SCHOOL</h2>
            <p style="margin-top: 0; color: #718096;">Sistem Informasi Akademik Terpadu</p>
        </div>

        <div class="content">
            <h3 style="color: #2D3748;">Reset Password Anda</h3>
            <p>Halo,</p>
            <p>Kami menerima permintaan untuk mereset password akun E-School Anda. Klik tombol di bawah ini untuk melanjutkan proses reset password:</p>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" class="btn">
                    Reset Password
                </a>
            </div>

            <div class="info-box">
                <strong>Perhatian:</strong> Link ini akan kadaluarsa dalam 60 menit. Jika Anda tidak meminta reset password, abaikan email ini atau hubungi administrator jika Anda merasa ada aktivitas mencurigakan.
            </div>

            <p>Jika tombol di atas tidak bekerja, salin dan tempel URL berikut di browser Anda:</p>
            <p style="word-break: break-all; font-size: 13px; background-color: #F8FAFC; padding: 10px; border-radius: 4px;">
                {{ route('password.reset', ['token' => $token, 'email' => $email]) }}
            </p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas.</p>
        </div>
    </div>
</body>
</html>
