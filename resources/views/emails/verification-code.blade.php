<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 8px;
            margin: 20px 0;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 8px;
        }
        .expiry {
            color: #6b7280;
            font-size: 14px;
            margin-top: 15px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $verificationCode->type === 'login' ? 'Login Verification' : 'Password Reset' }}</h1>
        </div>
        <div class="content">
            <p>Your verification code is:</p>
            <div class="code">{{ $verificationCode->code }}</div>
            <p class="expiry">This code will expire in 10 minutes.</p>
            <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
                If you didn't request this code, please ignore this email.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Nathaniel Gyarteng Visa Interview Mentor. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
