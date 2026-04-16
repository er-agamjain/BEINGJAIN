@extends('layouts.app')

@section('content')
@php
    $rowLabel = function ($rowNumber) {
        $label = '';
        $number = (int) $rowNumber;

        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $label = chr(65 + $remainder) . $label;
            $number = intdiv($number - 1, 26);
        }

        return $label ?: 'A';
    };
@endphp
<div class="space-y-6">
    <!-- Payment Header -->
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-4 flex items-center">
            <i class="fas fa-credit-card text-amber-400 mr-3"></i> Complete Your Payment
        </h1>
        <p class="text-slate-300">Please complete the payment using your selected method below</p>
    </div>

    <!-- Payment Details -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Left: Payment Method Details -->
        <div class="space-y-6">
            <!-- Razorpay Payment -->
            @if($paymentMethod === 'razorpay')
            <div class="rounded-3xl border border-blue-400/30 bg-blue-500/10 text-white shadow-2xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-wallet text-blue-400 mr-3"></i> Razorpay Checkout
                </h2>
                
                <div class="space-y-4">
                    <div class="bg-slate-900/60 border border-blue-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Amount to Pay</p>
                        <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>

                    <div class="bg-slate-900/60 border border-blue-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-3">Payment Methods Available:</p>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center text-slate-300">
                                <i class="fas fa-check text-emerald-400 mr-2"></i> Credit/Debit Cards
                            </li>
                            <li class="flex items-center text-slate-300">
                                <i class="fas fa-check text-emerald-400 mr-2"></i> UPI
                            </li>
                            <li class="flex items-center text-slate-300">
                                <i class="fas fa-check text-emerald-400 mr-2"></i> Digital Wallets (Paytm, Amazon Pay, PhonePe)
                            </li>
                            <li class="flex items-center text-slate-300">
                                <i class="fas fa-check text-emerald-400 mr-2"></i> NetBanking
                            </li>
                            <li class="flex items-center text-slate-300">
                                <i class="fas fa-check text-emerald-400 mr-2"></i> NEFT/RTGS
                            </li>
                        </ul>
                    </div>

                    <div class="bg-slate-900/60 border border-blue-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Booking Reference</p>
                        <p class="text-lg font-mono font-bold text-blue-300">{{ $booking->booking_reference }}</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-500/20 border border-blue-400/30 rounded-lg">
                    <p class="text-blue-200 text-sm">
                        <i class="fas fa-info-circle mr-2"></i> 
                        Click the button below to open Razorpay QR/UPI checkout. After successful payment, this page updates automatically.
                    </p>
                </div>
            </div>
            @endif

            <!-- UPI Payment -->
            @if($paymentMethod === 'upi')
            <div class="rounded-3xl border border-cyan-400/30 bg-cyan-500/10 text-white shadow-2xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-mobile-alt text-cyan-400 mr-3"></i> Pay via UPI
                </h2>
                
                <!-- QR Code -->
                <div class="bg-white rounded-xl p-6 mb-6">
                    <div id="upiQrCode" class="flex items-center justify-center">
                        <div class="w-64 h-64 bg-slate-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-qrcode text-6xl text-slate-400 mb-2"></i>
                                <p class="text-slate-600 font-medium text-sm">Scan to pay</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UPI Details -->
                <div class="space-y-4">
                    <div class="bg-slate-900/60 border border-cyan-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">UPI ID</p>
                        <div class="flex items-center justify-between">
                            <p class="text-xl font-mono font-bold text-cyan-300">{{ \App\Helpers\PaymentHelper::getUPIId() }}</p>
                            <button onclick="copyToClipboard('{{ \App\Helpers\PaymentHelper::getUPIId() }}')" class="px-3 py-1 bg-cyan-600 hover:bg-cyan-700 rounded text-sm transition-colors">
                                <i class="fas fa-copy mr-1"></i> Copy
                            </button>
                        </div>
                    </div>

                    <div class="bg-slate-900/60 border border-cyan-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Amount to Pay</p>
                        <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>

                    <div class="bg-slate-900/60 border border-cyan-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Payment Reference</p>
                        <p class="text-lg font-mono font-bold text-cyan-300">{{ $booking->booking_reference }}</p>
                        <p class="text-xs text-slate-400 mt-1">Use this as payment note/remark</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-cyan-500/20 border border-cyan-400/30 rounded-lg">
                    <p class="text-cyan-200 text-sm">
                        <i class="fas fa-info-circle mr-2"></i> 
                        Scan QR code with any UPI app (Google Pay, PhonePe, Paytm) or use the UPI ID above to complete payment.
                    </p>
                </div>
            </div>
            @endif

            <!-- NetBanking -->
            @if($paymentMethod === 'netbanking')
            <div class="rounded-3xl border border-amber-400/30 bg-amber-500/10 text-white shadow-2xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-university text-amber-400 mr-3"></i> NetBanking Payment
                </h2>
                
                <div class="space-y-4">
                    <div class="bg-slate-900/60 border border-amber-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Amount to Pay</p>
                        <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>

                    <div class="bg-slate-900/60 border border-amber-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Select Your Bank</p>
                        <select id="bankSelect" name="bank_code" form="confirmPaymentForm" required class="w-full px-4 py-3 bg-slate-800 border border-amber-400/30 rounded-lg text-white">
                            <option value="">Choose your bank...</option>
                            <option value="sbi" {{ old('bank_code') === 'sbi' ? 'selected' : '' }}>State Bank of India</option>
                            <option value="hdfc" {{ old('bank_code') === 'hdfc' ? 'selected' : '' }}>HDFC Bank</option>
                            <option value="icici" {{ old('bank_code') === 'icici' ? 'selected' : '' }}>ICICI Bank</option>
                            <option value="axis" {{ old('bank_code') === 'axis' ? 'selected' : '' }}>Axis Bank</option>
                            <option value="pnb" {{ old('bank_code') === 'pnb' ? 'selected' : '' }}>Punjab National Bank</option>
                            <option value="kotak" {{ old('bank_code') === 'kotak' ? 'selected' : '' }}>Kotak Mahindra Bank</option>
                            <option value="other" {{ old('bank_code') === 'other' ? 'selected' : '' }}>Other Banks</option>
                        </select>
                        @error('bank_code')
                            <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 p-4 bg-amber-500/20 border border-amber-400/30 rounded-lg">
                    <p class="text-amber-200 text-sm">
                        <i class="fas fa-info-circle mr-2"></i> 
                        Clicking continue opens your bank's secure NetBanking page in a new tab to complete the payment.
                    </p>
                </div>
            </div>
            @endif

            <!-- Wallet Payment -->
            @if($paymentMethod === 'wallet')
            <div class="rounded-3xl border border-purple-400/30 bg-purple-500/10 text-white shadow-2xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-wallet text-purple-400 mr-3"></i> Digital Wallet Payment
                </h2>
                
                <div class="space-y-4">
                    <div class="bg-slate-900/60 border border-purple-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Amount to Pay</p>
                        <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>

                    <div class="bg-slate-900/60 border border-purple-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-3">Select Wallet</p>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 bg-slate-800 border border-purple-400/20 rounded-lg cursor-pointer hover:bg-slate-700 transition">
                                <input type="radio" name="wallet_provider" form="confirmPaymentForm" value="paytm" class="mr-3" required>
                                <span>Paytm Wallet</span>
                            </label>
                            <label class="flex items-center p-3 bg-slate-800 border border-purple-400/20 rounded-lg cursor-pointer hover:bg-slate-700 transition">
                                <input type="radio" name="wallet_provider" form="confirmPaymentForm" value="phonepe" class="mr-3" required>
                                <span>PhonePe Wallet</span>
                            </label>
                            <label class="flex items-center p-3 bg-slate-800 border border-purple-400/20 rounded-lg cursor-pointer hover:bg-slate-700 transition">
                                <input type="radio" name="wallet_provider" form="confirmPaymentForm" value="amazonpay" class="mr-3" required>
                                <span>Amazon Pay</span>
                            </label>
                        </div>
                        @error('wallet_provider')
                            <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 p-4 bg-purple-500/20 border border-purple-400/30 rounded-lg">
                    <p class="text-purple-200 text-sm">
                        <i class="fas fa-info-circle mr-2"></i> 
                        First click opens your selected wallet in a new tab. After paying there, come back and click confirm.
                    </p>
                </div>
            </div>
            @endif

            <!-- Stripe/Card Payment -->
            @if($paymentMethod === 'stripe')
            <div class="rounded-3xl border border-blue-400/30 bg-blue-500/10 text-white shadow-2xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-credit-card text-blue-400 mr-3"></i> Card Payment
                </h2>
                
                <div class="space-y-4">
                    <div class="bg-slate-900/60 border border-blue-400/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Amount to Pay</p>
                        <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-slate-400 text-sm mb-2 block">Card Number</label>
                            <input type="text" placeholder="1234 5678 9012 3456" maxlength="19" class="w-full px-4 py-3 bg-slate-800 border border-blue-400/30 rounded-lg text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-slate-400 text-sm mb-2 block">Expiry (MM/YY)</label>
                                <input type="text" placeholder="12/28" maxlength="5" class="w-full px-4 py-3 bg-slate-800 border border-blue-400/30 rounded-lg text-white">
                            </div>
                            <div>
                                <label class="text-slate-400 text-sm mb-2 block">CVV</label>
                                <input type="password" placeholder="123" maxlength="3" class="w-full px-4 py-3 bg-slate-800 border border-blue-400/30 rounded-lg text-white">
                            </div>
                        </div>
                        <div>
                            <label class="text-slate-400 text-sm mb-2 block">Cardholder Name</label>
                            <input type="text" placeholder="JOHN DOE" class="w-full px-4 py-3 bg-slate-800 border border-blue-400/30 rounded-lg text-white">
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-500/20 border border-blue-400/30 rounded-lg">
                    <p class="text-blue-200 text-sm">
                        <i class="fas fa-lock mr-2"></i> 
                        Your card details are secure. We use 256-bit SSL encryption.
                    </p>
                </div>
            </div>
            @endif

            <!-- PayPal -->
            @if($paymentMethod === 'paypal')
            <div class="rounded-3xl border border-blue-600/30 bg-blue-600/10 text-white shadow-2xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fab fa-paypal text-blue-500 mr-3"></i> PayPal Payment
                </h2>
                
                <div class="space-y-4">
                    <div class="bg-slate-900/60 border border-blue-600/30 p-4 rounded-lg">
                        <p class="text-slate-400 text-sm mb-2">Amount to Pay</p>
                        <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>

                    <div class="bg-slate-900/60 border border-blue-600/30 p-6 rounded-lg text-center">
                        <i class="fab fa-paypal text-6xl text-blue-500 mb-4"></i>
                        <p class="text-slate-300 mb-4">Click the button below to continue with PayPal</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-600/20 border border-blue-600/30 rounded-lg">
                    <p class="text-blue-200 text-sm">
                        <i class="fas fa-info-circle mr-2"></i> 
                        You will be redirected to PayPal's secure checkout page.
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right: Booking Summary -->
        <div class="space-y-6">
            <!-- Booking Details -->
            <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8">
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-ticket-alt text-amber-400 mr-3"></i> Booking Summary
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-slate-400 text-sm">Event</p>
                        <p class="text-lg font-semibold">{{ $booking->event->title }}</p>
                    </div>
                    
                    <div>
                        <p class="text-slate-400 text-sm">Date & Time</p>
                        @if($booking->showTiming)
                            <p class="font-semibold">{{ $booking->showTiming->show_date_time->format('M d, Y · h:i A') }}</p>
                        @else
                            <p class="font-semibold">{{ $booking->event->event_date->format('M d, Y') }} · {{ \Carbon\Carbon::parse($booking->event->event_time)->format('h:i A') }}</p>
                        @endif
                    </div>

                    @if($booking->seats && $booking->seats->count() > 0)
                    <div>
                        <p class="text-slate-400 text-sm mb-2">Selected Seats</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->seats as $seat)
                            <div class="px-3 py-1 bg-amber-500/20 text-amber-300 rounded text-sm border border-amber-400/30">
                                Row {{ $rowLabel($seat->row_number) }}, Seat {{ $seat->column_number }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div>
                        <p class="text-slate-400 text-sm">Tickets</p>
                        <p class="font-semibold">{{ $booking->quantity }} × General Admission</p>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-white/10">
                        <p class="text-slate-400 text-sm mb-1">Total Amount</p>
                        <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6">
                <h3 class="text-lg font-bold mb-3 flex items-center">
                    <i class="fas fa-info-circle text-cyan-400 mr-2"></i> Payment Instructions
                </h3>
                <ul class="space-y-2 text-sm text-slate-300">
                    <li class="flex items-start">
                        <i class="fas fa-check text-emerald-400 mr-2 mt-1"></i>
                        <span>Complete payment within 10 minutes to confirm booking</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-emerald-400 mr-2 mt-1"></i>
                        <span>Use booking reference for any payment notes</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-emerald-400 mr-2 mt-1"></i>
                        <span>You'll receive e-ticket via email after payment</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <form id="confirmPaymentForm" method="POST" action="{{ route('user.payments.confirm', $booking) }}">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
                    @if($paymentMethod === 'wallet')
                    <input type="hidden" id="walletRedirectedInput" name="wallet_redirected" value="{{ old('wallet_redirected', '0') }}">
                    @endif
                    <button id="confirmPaymentBtn" type="submit" class="w-full px-6 py-4 btn-primary-luxury bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-900 hover:from-amber-300 hover:to-yellow-300 text-center text-lg">
                        <i class="fas fa-check-circle mr-2"></i> <span id="confirmPaymentBtnText">I Have Completed Payment</span>
                    </button>
                </form>
                <p id="razorpayStatus" class="hidden text-center text-sm text-cyan-200"></p>
                
                <a href="{{ route('user.payments.create', $booking) }}" class="block text-center px-6 py-3 btn-secondary-luxury bg-slate-700/70 border border-slate-500 text-gray-100 hover:bg-slate-600">
                    <i class="fas fa-arrow-left mr-2"></i> Cancel Payment Method
                </a>
            </div>

            <p class="text-center text-xs text-slate-400">
                By clicking "I Have Completed Payment", you confirm that you have successfully paid ₹{{ number_format($booking->total_price, 0) }} using {{ ucfirst($paymentMethod) }}.
            </p>
        </div>
    </div>
</div>

@if($paymentMethod === 'razorpay')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
<script>
// Generate UPI QR Code
@if($paymentMethod === 'upi')
document.addEventListener('DOMContentLoaded', function() {
    const upiId = '{{ \App\Helpers\PaymentHelper::getUPIId() }}';
    const amount = '{{ $booking->total_price }}';
    const name = '{{ \App\Helpers\PaymentHelper::getUPIMerchantName() }}';
    const ref = '{{ $booking->booking_reference }}';
    
    // UPI URL format: upi://pay?pa=UPI_ID&pn=NAME&am=AMOUNT&tn=NOTE
    const upiUrl = `upi://pay?pa=${upiId}&pn=${encodeURIComponent(name)}&am=${amount}&tn=${encodeURIComponent(ref)}`;
    
    // Generate QR using API
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(upiUrl)}`;
    
    document.getElementById('upiQrCode').innerHTML = `
        <img src="${qrUrl}" alt="UPI QR Code" class="w-64 h-64 rounded-lg">
    `;
});
@endif

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Copied to clipboard: ' + text);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

@if($paymentMethod === 'netbanking')
document.addEventListener('DOMContentLoaded', function() {
    const bankSelect = document.getElementById('bankSelect');
    const confirmButton = document.getElementById('confirmPaymentBtn');
    const confirmButtonText = document.getElementById('confirmPaymentBtnText');
    const confirmForm = document.getElementById('confirmPaymentForm');

    const bankUrls = {
        sbi: 'https://retail.onlinesbi.sbi/retail/login.htm',
        hdfc: 'https://netbanking.hdfcbank.com/netbanking/',
        icici: 'https://infinity.icicibank.com/corp/Login.jsp',
        axis: 'https://retail.axisbank.co.in/',
        pnb: 'https://netpnb.com/',
        kotak: 'https://netbanking.kotak.com/knb2/'
    };

    const updateNetBankingState = () => {
        if (!bankSelect || !confirmButton || !confirmButtonText) {
            return;
        }

        const selectedOption = bankSelect.options[bankSelect.selectedIndex];
        const hasBank = bankSelect.value !== '';

        confirmButton.disabled = !hasBank;
        confirmButton.classList.toggle('opacity-60', !hasBank);
        confirmButton.classList.toggle('cursor-not-allowed', !hasBank);

        if (hasBank) {
            confirmButtonText.textContent = `Continue with ${selectedOption.text}`;
        } else {
            confirmButtonText.textContent = 'Select Bank to Continue';
        }
    };

    bankSelect.addEventListener('change', updateNetBankingState);

    if (confirmForm) {
        confirmForm.addEventListener('submit', function() {
            const selectedBank = bankSelect ? bankSelect.value : '';
            const redirectUrl = bankUrls[selectedBank];

            if (redirectUrl) {
                window.open(redirectUrl, '_blank', 'noopener,noreferrer');
            }
        });
    }

    updateNetBankingState();
});
@endif

@if($paymentMethod === 'wallet')
document.addEventListener('DOMContentLoaded', function() {
    const walletOptions = document.querySelectorAll('input[name="wallet_provider"]');
    const confirmButton = document.getElementById('confirmPaymentBtn');
    const confirmButtonText = document.getElementById('confirmPaymentBtnText');
    const confirmForm = document.getElementById('confirmPaymentForm');
    const walletRedirectedInput = document.getElementById('walletRedirectedInput');

    const walletLabelMap = {
        paytm: 'Paytm Wallet',
        phonepe: 'PhonePe Wallet',
        amazonpay: 'Amazon Pay'
    };

    const walletUrls = {
        paytm: 'https://paytm.com/',
        phonepe: 'https://www.phonepe.com/',
        amazonpay: 'https://pay.amazon.com/'
    };

    const updateWalletState = () => {
        if (!confirmButton || !confirmButtonText) {
            return;
        }

        const selectedWallet = document.querySelector('input[name="wallet_provider"]:checked');
        const hasWallet = !!selectedWallet;

        confirmButton.disabled = !hasWallet;
        confirmButton.classList.toggle('opacity-60', !hasWallet);
        confirmButton.classList.toggle('cursor-not-allowed', !hasWallet);

        if (hasWallet) {
            const walletName = walletLabelMap[selectedWallet.value] ?? 'Selected Wallet';
            if (walletRedirectedInput && walletRedirectedInput.value === '1') {
                confirmButtonText.textContent = `I Have Completed Payment (${walletName})`;
            } else {
                confirmButtonText.textContent = `Open ${walletName}`;
            }
        } else {
            confirmButtonText.textContent = 'Select Wallet to Continue';
        }
    };

    walletOptions.forEach((option) => {
        option.addEventListener('change', () => {
            if (walletRedirectedInput) {
                walletRedirectedInput.value = '0';
            }
            updateWalletState();
        });
    });

    if (confirmForm) {
        confirmForm.addEventListener('submit', function(event) {
            const selectedWallet = document.querySelector('input[name="wallet_provider"]:checked');
            const needsRedirect = walletRedirectedInput && walletRedirectedInput.value !== '1';

            if (selectedWallet && needsRedirect) {
                event.preventDefault();

                const redirectUrl = walletUrls[selectedWallet.value];
                if (redirectUrl) {
                    window.open(redirectUrl, '_blank', 'noopener,noreferrer');
                }

                walletRedirectedInput.value = '1';
                updateWalletState();
            }
        });
    }

    updateWalletState();
});
@endif

@if($paymentMethod === 'razorpay')
document.addEventListener('DOMContentLoaded', function() {
    const confirmForm = document.getElementById('confirmPaymentForm');
    const confirmButton = document.getElementById('confirmPaymentBtn');
    const confirmButtonText = document.getElementById('confirmPaymentBtnText');
    const razorpayStatus = document.getElementById('razorpayStatus');

    if (!confirmForm || !confirmButton || !confirmButtonText) {
        return;
    }

    confirmButtonText.textContent = 'Pay with Razorpay';

    const showStatus = (message, isError = false) => {
        if (!razorpayStatus) {
            return;
        }
        razorpayStatus.classList.remove('hidden', 'text-cyan-200', 'text-red-300', 'text-emerald-300');
        razorpayStatus.classList.add(isError ? 'text-red-300' : 'text-cyan-200');
        razorpayStatus.textContent = message;
    };

    const setLoading = (loading, label = 'Pay with Razorpay') => {
        confirmButton.disabled = loading;
        confirmButton.classList.toggle('opacity-60', loading);
        confirmButton.classList.toggle('cursor-not-allowed', loading);
        confirmButtonText.textContent = label;
    };

    confirmForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        if (typeof Razorpay === 'undefined') {
            showStatus('Razorpay SDK failed to load. Please refresh and try again.', true);
            return;
        }

        try {
            setLoading(true, 'Opening Razorpay...');

            const orderResponse = await fetch('{{ route('user.payments.razorpay.order', $booking) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const orderData = await orderResponse.json();

            if (!orderResponse.ok || !orderData.success) {
                throw new Error(orderData.message || 'Unable to create Razorpay order.');
            }

            const options = {
                key: orderData.key,
                amount: orderData.amount,
                currency: orderData.currency,
                name: orderData.name,
                description: orderData.description,
                order_id: orderData.order_id,
                prefill: orderData.prefill,
                notes: orderData.notes,
                theme: { color: '#f59e0b' },
                config: {
                    display: {
                        preferences: {
                            show_default_blocks: true
                        }
                    }
                },
                handler: async function (response) {
                    try {
                        showStatus('Payment received. Verifying...', false);
                        setLoading(true, 'Verifying Payment...');

                        const verifyResponse = await fetch('{{ route('user.payments.razorpay.verify', $booking) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        });

                        const verifyData = await verifyResponse.json();

                        if (!verifyResponse.ok || !verifyData.success) {
                            throw new Error(verifyData.message || 'Payment verification failed.');
                        }

                        showStatus('Payment successful! Redirecting...', false);
                        if (verifyData.redirect_url) {
                            window.location.href = verifyData.redirect_url;
                            return;
                        }

                        window.location.reload();
                    } catch (verifyError) {
                        showStatus(verifyError.message || 'Unable to verify payment. Contact support if amount was deducted.', true);
                        setLoading(false, 'Pay with Razorpay');
                    }
                },
                modal: {
                    ondismiss: function () {
                        setLoading(false, 'Pay with Razorpay');
                        showStatus('Payment window closed. You can try again.', true);
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
            setLoading(false, 'Pay with Razorpay');
        } catch (error) {
            setLoading(false, 'Pay with Razorpay');
            showStatus(error.message || 'Unable to start Razorpay checkout.', true);
        }
    });
});
@endif
</script>
@endsection
