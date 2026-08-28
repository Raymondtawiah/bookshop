<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Chat Message</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #6366f1, #22d3ee);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .header h1 {
            text-align: center;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            text-align: center;
            margin: 0;
            font-size: 16px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #6366f1;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #6366f1;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Chat Message</h1>
        <p>You have received a new message from a customer</p>
    </div>
    
    <div class="content">
        <p>Hello,</p>
        
        <p>You have received a new chat message from <strong>{{ $senderName }}</strong>.</p>
        
        <div class="message-box">
            <h3 style="margin-top: 0;">Message:</h3>
            <p>{{ $message }}</p>
        </div>
        
        <p>Click the button below to view and respond to the message:</p>
        
        <a href="{{ $chatUrl }}" class="btn">View Chat</a>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
