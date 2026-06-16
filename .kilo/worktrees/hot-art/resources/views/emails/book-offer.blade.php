<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Offer</title>
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
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .book-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .book-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .book-item:last-child {
            border-bottom: none;
        }
        .btn {
            display: inline-block;
            background: #4f46e5;
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
        <h1>📚 Book Offer</h1>
        <p>We're offering you a book from your order</p>
    </div>

    <div class="content">
        <p>Dear {{ $order->customer_name ?? 'Customer' }},</p>

        <p>We're pleased to offer you the following book from your order #{{ $order->order_number }}:</p>

        <div class="book-details">
            @foreach($order->order_items as $item)
            <div class="book-item">
                <div>
                    <strong>{{ is_array($item) ? $item['product_name'] : $item->product_name }}</strong>
                </div>
            </div>
            @endforeach
        </div>

        <p>Your book PDF are attached to this email.</p>

        <a href="{{ route('home') }}" class="btn">View Your Account</a>

        <p style="margin-top: 20px;">Happy reading! 📚</p>
    </div>
</body>
</html>