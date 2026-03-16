<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        .order-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
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
        <h1>🎉 Order Confirmed!</h1>
        <p>Thank you for your order</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        <div class="order-details">
            <h3 style="margin-top: 0;">Order Details</h3>
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y, g:i A') }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_method === 'momo' ? 'Mobile Money' : ($order->payment_method === 'bank' ? 'Bank Transfer' : 'Card Payment') }}</p>
            <p><strong>Payment Status:</strong> <span style="color: green;">{{ ucfirst($order->payment_status) }}</span></p>
        </div>
        
        <h3>Items Ordered</h3>
        <div class="order-details">
            @foreach($cartItems as $item)
            <div class="order-item">
                <div>
                    <strong>{{ $item->product_name }}</strong>
                    <br>
                    <small>Qty: {{ $item->quantity }} × ₵{{ number_format($item->product_price, 2) }}</small>
                </div>
                <div>₵{{ number_format($item->product_price * $item->quantity, 2) }}</div>
            </div>
            @endforeach
            
            <div class="order-item total">
                <span>Total Amount</span>
                <span>₵{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
        
        <h3>Delivery Details</h3>
        <div class="order-details">
            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Contact:</strong> {{ $order->contact }}</p>
            <p><strong>Nationality:</strong> {{ $order->nationality ?? 'N/A' }}</p>
            <p><strong>Delivery Address:</strong> {{ $order->residence }}</p>
        </div>
        
        <p>Your order is being processed and will be delivered soon. We'll send you another email with tracking details once your order is shipped.</p>
        
        <p>In the meantime, feel free to <a href="{{ route('home') }}">browse our collection</a> for your next great read!</p>
        
        <p>Happy reading! 📚</p>
        
    </div>
</body>
</html>
