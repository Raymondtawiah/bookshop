<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #111827;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            background: #4f46e5;
            color: #ffffff;
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
            background: #ffffff;
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
            color: #374151;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #4f46e5;
            color: #ffffff;
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
             <p><strong>Payment Status:</strong> <span style="color: green;">{{ ucfirst($order->payment_status) }}</span></p>
             <p><strong>Amount Paid:</strong> 
                 @if($order->currency === 'GHS')
                     ₵{{ number_format($order->total_amount, 2) }}
                 @else
                     ${{ number_format($order->total_amount_usd ?? $order->total_amount, 2) }}
                 @endif
             </p>
         </div>
        
         <h3>Order Summary</h3>
         <div class="order-details">
             <p><strong>Book:</strong> {{ $order->order_items->first()['product_name'] ?? 'Ordered Book' }}</p>
             
              <div class="order-item total">
                  <span>Total Amount ({{ $order->currency ?? 'USD' }})</span>
                  <div>
                      @if($order->currency === 'GHS')
                          <span>₵{{ number_format($order->total_amount, 2) }}</span>
                      @else
                          <span>${{ number_format($order->total_amount_usd ?? $order->total_amount, 2) }}</span>
                      @endif
                  </div>
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
