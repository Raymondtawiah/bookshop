<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder</title>
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
        <h1>Payment Pending</h1>
        <p>Complete your order payment</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $order->customer_name }},</p>
        
        <p>We noticed that your payment for order <strong>#{{ $order->order_number }}</strong> is still pending.</p>
        
        <p>Please complete your payment to confirm your order and receive your book(s).</p>
        
        <div class="order-details">
            <h3 style="margin-top: 0;">Order Summary</h3>
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y, g:i A') }}</p>
            <p><strong>Payment Method:</strong> 
                @if($order->payment_method === 'paystack')
                    Paystack
                @elseif($order->payment_method === 'momo')
                    Mobile Money
                @elseif($order->payment_method === 'bank')
                    Bank Transfer
                @else
                    Card Payment
                @endif
            </p>
            
            @php
                $items = $order->order_items;
            @endphp
            @if(!empty($items) && count($items) > 0)
                @foreach($items as $item)
                    @php
                        $name = is_array($item) ? ($item['product_name'] ?? 'Book') : ($item->product_name ?? 'Book');
                    @endphp
                    <div class="order-item">
                        <span>{{ $name }}</span>
                    </div>
                @endforeach
            @endif
            
            <div class="total">
                <span>Total Amount ({{ $order->currency ?? 'USD' }})</span>
                <div>
                    <span>{{ $order->currency === 'GHS' ? '₵' : '$' }}{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
        
        <p>Click the button below to complete your payment:</p>
        
        <a href="{{ $paymentLink }}" class="btn">Complete Payment</a>
        
        <p>If the button doesn't work, copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #4f46e5;">{{ $paymentLink }}</p>
        
        <p>If you have already made payment, please disregard this message. If you have any questions, feel free to contact us.</p>
        
        <p>Thank you for your order!</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
