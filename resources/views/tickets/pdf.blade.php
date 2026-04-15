<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .ticket { 
            width: 100%; 
            max-width: 600px; 
            margin: 20px auto;
            border: 2px solid #2563eb;
            border-radius: 10px;
            padding: 30px;
            background: #f8f9fa;
        }
        .ticket-header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .ticket-header h1 { 
            color: #2563eb; 
            margin: 0;
            font-size: 28px;
        }
        .ticket-header p { 
            color: #666; 
            margin: 5px 0;
            font-size: 14px;
        }
        .ticket-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .detail-item {
            padding: 15px;
            background: white;
            border-radius: 5px;
            border-left: 4px solid #2563eb;
        }
        .detail-label {
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #000;
            font-size: 16px;
            font-weight: bold;
        }
        .barcode {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: white;
            border-radius: 5px;
        }
        .barcode-text {
            font-family: monospace;
            font-size: 18px;
            letter-spacing: 2px;
            color: #000;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>{{ $booking->event->name }}</h1>
            <p>EventBook Ticket</p>
            <p>Reference: {{ $booking->booking_reference }}</p>
        </div>

        <div class="ticket-details">
            <div class="detail-item">
                <div class="detail-label">Ticket Type</div>
                <div class="detail-value">{{ $booking->ticket ? ucfirst($booking->ticket->ticket_type) : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Quantity</div>
                <div class="detail-value">{{ $booking->quantity }} x {{ $booking->ticket ? $booking->ticket->name : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Date</div>
                <div class="detail-value">{{ $booking->event->event_date->format('M d, Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Time</div>
                <div class="detail-value">{{ $booking->event->event_date->format('H:i') }}</div>
            </div>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <div class="detail-label">Location</div>
                <div class="detail-value">{{ $booking->event->location }}</div>
            </div>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <div class="detail-label">Attendee Name</div>
                <div class="detail-value">{{ $booking->user->name }}</div>
            </div>
        </div>

        <div class="barcode">
            <div class="barcode-text">{{ $booking->booking_reference }}</div>
        </div>

        <div class="footer">
            <p>This ticket was issued on {{ $booking->created_at->format('F d, Y H:i') }}</p>
            <p>Please present this ticket at the event entrance</p>
        </div>
    </div>
</body>
</html>
