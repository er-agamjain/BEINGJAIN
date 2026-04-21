<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9; font-family: 'Segoe UI', Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f6f9; padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding:36px 40px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:26px; font-weight:700; letter-spacing:0.5px;">
                                🎫 Booking Confirmed!
                            </h1>
                            <p style="margin:8px 0 0; color:rgba(255,255,255,0.85); font-size:14px;">
                                Your seats have been reserved successfully
                            </p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding:32px 40px 0;">
                            <p style="margin:0; font-size:16px; color:#333;">
                                Hello <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin:10px 0 0; font-size:15px; color:#555; line-height:1.6;">
                                Thank you for your booking! Here are your booking details:
                            </p>
                        </td>
                    </tr>

                    {{-- Booking Reference Badge --}}
                    <tr>
                        <td style="padding:20px 40px 0;" align="center">
                            <table role="presentation" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="background-color:#f0f4ff; border:2px dashed #667eea; border-radius:8px; padding:14px 28px; text-align:center;">
                                        <span style="font-size:12px; color:#667eea; text-transform:uppercase; font-weight:600; letter-spacing:1px;">Booking Reference</span><br>
                                        <span style="font-size:22px; color:#333; font-weight:700; letter-spacing:2px;">{{ $booking->booking_reference }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Event Details Card --}}
                    <tr>
                        <td style="padding:24px 40px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fafbfd; border-radius:10px; border:1px solid #e8ecf1;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <h3 style="margin:0 0 16px; font-size:17px; color:#333; border-bottom:1px solid #e8ecf1; padding-bottom:10px;">
                                            📅 Event Details
                                        </h3>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777; width:120px;">Event</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333; font-weight:600;">{{ $event->title }}</td>
                                            </tr>
                                            @if($showTiming && $showTiming->venue)
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Venue</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333; font-weight:600;">{{ $showTiming->venue->name }}</td>
                                            </tr>
                                            @endif
                                            @if($showTiming && $showTiming->show_date_time)
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Date</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ $showTiming->show_date_time->format('D, M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Show Time</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">
                                                    {{ $showTiming->show_date_time->format('h:i A') }}
                                                    @if($showTiming->duration_minutes)
                                                        ({{ $showTiming->duration_minutes }} mins)
                                                    @endif
                                                </td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Date</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ \Carbon\Carbon::parse($event->event_date)->format('D, M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Time</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">
                                                    {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                                                    @if($event->end_time)
                                                        – {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @if($event->location)
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Location</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ $event->location }}</td>
                                            </tr>
                                            @endif
                                            @if($event->address)
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Address</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ $event->address }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Seat & Price Details --}}
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fafbfd; border-radius:10px; border:1px solid #e8ecf1;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <h3 style="margin:0 0 16px; font-size:17px; color:#333; border-bottom:1px solid #e8ecf1; padding-bottom:10px;">
                                            💺 Booking Summary
                                        </h3>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777; width:120px;">Total Seats</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ $booking->quantity }} seat(s)</td>
                                            </tr>
                                        </table>

                                        {{-- Individual Seat Details --}}
                                        @if($seats && $seats->count())
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:12px; border-top:1px solid #e8ecf1; padding-top:12px;">
                                            <tr>
                                                <td colspan="2" style="padding:0 0 8px; font-size:13px; color:#777; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Your Seats</td>
                                            </tr>
                                            @foreach($seats as $seat)
                                            <tr>
                                                <td style="padding:5px 0; font-size:14px; color:#333;">
                                                    <span style="display:inline-block; padding:4px 10px; border-radius:6px; background-color:#f0f4ff; color:#667eea; font-weight:600; font-size:13px;">
                                                        {{ $seat->seatCategory->name ?? 'General' }}
                                                    </span>
                                                    &nbsp;—&nbsp; Row {{ chr(64 + (int)$seat->row_number) }}, Seat {{ $seat->column_number }}
                                                </td>
                                                <td style="padding:5px 0; font-size:14px; color:#333; text-align:right; font-weight:600;">
                                                    ₹{{ number_format($seat->current_price ?? 0, 2) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        @endif
                                        
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:12px;">
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777; width:120px;">Status</td>
                                                <td style="padding:6px 0; font-size:14px;">
                                                    <span style="display:inline-block; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600;
                                                        {{ $booking->status === 'confirmed' ? 'background-color:#dcfce7; color:#15803d;' : 'background-color:#fef3c7; color:#b45309;' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Total --}}
                                <tr>
                                    <td style="padding:0 24px 20px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:8px;">
                                            <tr>
                                                <td style="padding:14px 20px; color:#fff; font-size:15px;">Total Amount</td>
                                                <td style="padding:14px 20px; color:#fff; font-size:22px; font-weight:700; text-align:right;">₹{{ number_format($booking->total_price, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA Button --}}
                    <tr>
                        <td style="padding:28px 40px 0;" align="center">
                            <a href="{{ route('user.bookings.show', $booking) }}" style="display:inline-block; padding:14px 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#ffffff; text-decoration:none; font-size:15px; font-weight:600; border-radius:8px; letter-spacing:0.5px;">
                                View My Booking
                            </a>
                        </td>
                    </tr>

                    {{-- Info Note --}}
                    <tr>
                        <td style="padding:24px 40px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fff8e1; border-radius:8px; border-left:4px solid #f59e0b;">
                                <tr>
                                    <td style="padding:14px 18px; font-size:13px; color:#92400e; line-height:1.5;">
                                        <strong>⏰ Reminder:</strong> Please arrive at least 15 minutes before the event starts. Carry a valid ID and this booking reference for entry.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:32px 40px; text-align:center; border-top:1px solid #e8ecf1; margin-top:24px;">
                            <p style="margin:0; font-size:13px; color:#999; line-height:1.6;">
                                This is an automated email from <strong>{{ config('app.name') }}</strong>.<br>
                                If you have any questions, please contact our support team.
                            </p>
                            <p style="margin:12px 0 0; font-size:12px; color:#bbb;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
