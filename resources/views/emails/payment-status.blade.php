<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Update</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9; font-family: 'Segoe UI', Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f6f9; padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header - color changes by status --}}
                    @php
                        $headerStyles = [
                            'confirmed' => 'background: linear-gradient(135deg, #10b981 0%, #059669 100%);',
                            'rejected' => 'background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);',
                            'not_received' => 'background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);',
                        ];
                        $headerIcons = [
                            'confirmed' => '✅',
                            'rejected' => '❌',
                            'not_received' => '⏳',
                        ];
                        $headerTitles = [
                            'confirmed' => 'Payment Confirmed!',
                            'rejected' => 'Payment Rejected',
                            'not_received' => 'Payment Not Received',
                        ];
                        $headerSubtitles = [
                            'confirmed' => 'Your payment has been verified and booking is confirmed',
                            'rejected' => 'Unfortunately, your payment could not be verified',
                            'not_received' => 'We have not received your payment yet',
                        ];
                    @endphp

                    <tr>
                        <td style="{{ $headerStyles[$statusType] ?? $headerStyles['not_received'] }} padding:36px 40px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:26px; font-weight:700; letter-spacing:0.5px;">
                                {{ $headerIcons[$statusType] ?? '📋' }} {{ $headerTitles[$statusType] ?? 'Payment Update' }}
                            </h1>
                            <p style="margin:8px 0 0; color:rgba(255,255,255,0.85); font-size:14px;">
                                {{ $headerSubtitles[$statusType] ?? 'There is an update on your payment' }}
                            </p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding:32px 40px 0;">
                            <p style="margin:0; font-size:16px; color:#333;">
                                Hello <strong>{{ $user->name }}</strong>,
                            </p>

                            @if($statusType === 'confirmed')
                                <p style="margin:10px 0 0; font-size:15px; color:#555; line-height:1.6;">
                                    Great news! Your payment for <strong>{{ $event->title }}</strong> has been confirmed. Your booking is now active.
                                </p>
                            @elseif($statusType === 'rejected')
                                <p style="margin:10px 0 0; font-size:15px; color:#555; line-height:1.6;">
                                    We regret to inform you that your payment for <strong>{{ $event->title }}</strong> has been rejected. Your booking has been cancelled and the reserved seats have been released.
                                </p>
                            @else
                                <p style="margin:10px 0 0; font-size:15px; color:#555; line-height:1.6;">
                                    We have not yet received your payment for <strong>{{ $event->title }}</strong>. Your seats are still reserved — please complete the payment to confirm your booking.
                                </p>
                            @endif
                        </td>
                    </tr>

                    {{-- Reason (for rejection) --}}
                    @if($statusType === 'rejected' && $reason)
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fef2f2; border-radius:8px; border-left:4px solid #ef4444;">
                                <tr>
                                    <td style="padding:14px 18px; font-size:14px; color:#991b1b; line-height:1.5;">
                                        <strong>Reason:</strong> {{ $reason }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif

                    {{-- Booking Reference Badge --}}
                    <tr>
                        <td style="padding:20px 40px 0;" align="center">
                            <table role="presentation" cellspacing="0" cellpadding="0">
                                <tr>
                                    @php
                                        $badgeColors = [
                                            'confirmed' => 'background-color:#ecfdf5; border-color:#10b981;',
                                            'rejected' => 'background-color:#fef2f2; border-color:#ef4444;',
                                            'not_received' => 'background-color:#fffbeb; border-color:#f59e0b;',
                                        ];
                                        $badgeTextColors = [
                                            'confirmed' => 'color:#059669;',
                                            'rejected' => 'color:#dc2626;',
                                            'not_received' => 'color:#d97706;',
                                        ];
                                    @endphp
                                    <td style="{{ $badgeColors[$statusType] ?? '' }} border:2px dashed; border-radius:8px; padding:14px 28px; text-align:center;">
                                        <span style="font-size:12px; {{ $badgeTextColors[$statusType] ?? '' }} text-transform:uppercase; font-weight:600; letter-spacing:1px;">Booking Reference</span><br>
                                        <span style="font-size:22px; color:#333; font-weight:700; letter-spacing:2px;">{{ $booking->booking_reference }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Payment Details Card --}}
                    <tr>
                        <td style="padding:24px 40px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fafbfd; border-radius:10px; border:1px solid #e8ecf1;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <h3 style="margin:0 0 16px; font-size:17px; color:#333; border-bottom:1px solid #e8ecf1; padding-bottom:10px;">
                                            💳 Payment Details
                                        </h3>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777; width:140px;">Amount</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333; font-weight:600;">₹{{ number_format($payment->amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Payment Method</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                            </tr>
                                            @if($payment->transaction_id)
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Transaction ID</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333; font-family:monospace;">{{ $payment->transaction_id }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Payment Status</td>
                                                <td style="padding:6px 0; font-size:14px;">
                                                    @php
                                                        $statusLabels = [
                                                            'confirmed' => ['Confirmed', 'background-color:#dcfce7; color:#15803d;'],
                                                            'rejected' => ['Rejected', 'background-color:#fee2e2; color:#dc2626;'],
                                                            'not_received' => ['Pending', 'background-color:#fef3c7; color:#b45309;'],
                                                        ];
                                                        $label = $statusLabels[$statusType] ?? ['Unknown', 'background-color:#f3f4f6; color:#6b7280;'];
                                                    @endphp
                                                    <span style="display:inline-block; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600; {{ $label[1] }}">
                                                        {{ $label[0] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Event Details Card --}}
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fafbfd; border-radius:10px; border:1px solid #e8ecf1;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <h3 style="margin:0 0 16px; font-size:17px; color:#333; border-bottom:1px solid #e8ecf1; padding-bottom:10px;">
                                            📅 Event Details
                                        </h3>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777; width:140px;">Event</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333; font-weight:600;">{{ $event->title }}</td>
                                            </tr>
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
                                            @if($event->location)
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Location</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ $event->location }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding:6px 0; font-size:14px; color:#777;">Seats</td>
                                                <td style="padding:6px 0; font-size:14px; color:#333;">{{ $booking->quantity }} seat(s)</td>
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
                            @if($statusType === 'confirmed')
                                <a href="{{ route('user.bookings.show', $booking) }}" style="display:inline-block; padding:14px 36px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color:#ffffff; text-decoration:none; font-size:15px; font-weight:600; border-radius:8px; letter-spacing:0.5px;">
                                    View My Booking
                                </a>
                            @elseif($statusType === 'rejected')
                                <a href="{{ route('user.bookings.history') }}" style="display:inline-block; padding:14px 36px; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color:#ffffff; text-decoration:none; font-size:15px; font-weight:600; border-radius:8px; letter-spacing:0.5px;">
                                    View Booking History
                                </a>
                            @else
                                <a href="{{ route('user.bookings.show', $booking) }}" style="display:inline-block; padding:14px 36px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:#ffffff; text-decoration:none; font-size:15px; font-weight:600; border-radius:8px; letter-spacing:0.5px;">
                                    Complete Payment
                                </a>
                            @endif
                        </td>
                    </tr>

                    {{-- Info Note --}}
                    <tr>
                        <td style="padding:24px 40px 0;">
                            @if($statusType === 'confirmed')
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#ecfdf5; border-radius:8px; border-left:4px solid #10b981;">
                                <tr>
                                    <td style="padding:14px 18px; font-size:13px; color:#065f46; line-height:1.5;">
                                        <strong>🎉 You're all set!</strong> Please arrive at least 15 minutes before the event. Carry a valid ID and your booking reference for entry.
                                    </td>
                                </tr>
                            </table>
                            @elseif($statusType === 'rejected')
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fef2f2; border-radius:8px; border-left:4px solid #ef4444;">
                                <tr>
                                    <td style="padding:14px 18px; font-size:13px; color:#991b1b; line-height:1.5;">
                                        <strong>💡 What next?</strong> If you believe this was an error, please contact our support team or try booking again with a different payment method.
                                    </td>
                                </tr>
                            </table>
                            @else
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fffbeb; border-radius:8px; border-left:4px solid #f59e0b;">
                                <tr>
                                    <td style="padding:14px 18px; font-size:13px; color:#92400e; line-height:1.5;">
                                        <strong>⚠️ Action Required:</strong> Your seats are temporarily reserved. Please complete the payment soon to avoid losing your reservation.
                                    </td>
                                </tr>
                            </table>
                            @endif
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
