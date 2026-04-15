@extends('layouts.app')

@section('title', 'Platform Settings')

@section('content')
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-transparent bg-gradient-to-r from-purple-400 via-pink-300 to-purple-400 bg-clip-text mb-2" style="font-family: 'Playfair Display', serif;">
        <i class="fas fa-cog text-purple-400"></i> Platform Settings
    </h1>
    <p class="text-gray-400">Configure platform-wide settings and commissions</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Commission Settings -->
    <div class="card-luxury">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-percentage text-purple-400 mr-3"></i> Commission Settings
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Platform Commission (%)</label>
                <div class="relative">
                    <input type="number" name="settings[platform_commission]" class="input-field" placeholder="10" 
                           value="{{ old('settings.platform_commission', $settings['platform_commission'] ?? 10) }}" 
                           min="0" max="100" step="0.01" required>
                    <span class="absolute right-4 top-3 text-gray-400">%</span>
                </div>
                <small class="text-gray-500 mt-1 block">Commission charged on all bookings</small>
            </div>

            <div>
                <label class="label">Organiser Default Commission (%)</label>
                <div class="relative">
                    <input type="number" name="settings[organiser_default_commission]" class="input-field" placeholder="5" 
                           value="{{ old('settings.organiser_default_commission', $settings['organiser_default_commission'] ?? 5) }}" 
                           min="0" max="100" step="0.01" required>
                    <span class="absolute right-4 top-3 text-gray-400">%</span>
                </div>
                <small class="text-gray-500 mt-1 block">Default share for organisers</small>
            </div>
        </div>

        <div class="mt-6 p-4 bg-purple-500/10 border border-purple-500/30 rounded-lg">
            <p class="text-purple-400 text-sm">
                <i class="fas fa-info-circle mr-2"></i> These percentages determine how revenue is split between the platform and organisers
            </p>
        </div>
    </div>

    <!-- Contact Settings -->
    <div class="card-luxury">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-phone text-purple-400 mr-3"></i> Contact Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Support Email</label>
                <input type="email" name="settings[contact_email]" class="input-field" placeholder="support@example.com" 
                       value="{{ old('settings.contact_email', $settings['contact_email'] ?? '') }}">
                <small class="text-gray-500 mt-1 block">Public support email address</small>
            </div>

            <div>
                <label class="label">Support Phone</label>
                <input type="tel" name="settings[support_phone]" class="input-field" placeholder="+91-9876543210" 
                       value="{{ old('settings.support_phone', $settings['support_phone'] ?? '') }}">
                <small class="text-gray-500 mt-1 block">Public support phone number</small>
            </div>
        </div>
    </div>

    <!-- Refund Policy -->
    <div class="card-luxury">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-undo text-purple-400 mr-3"></i> Refund Policy
        </h2>

        <div>
            <label class="label">Refund Policy Text</label>
            <textarea name="settings[refund_policy]" class="input-field" placeholder="Enter your refund policy..." rows="6">{{ old('settings.refund_policy', $settings['refund_policy'] ?? '') }}</textarea>
            <small class="text-gray-500 mt-1 block">This will be displayed to users during checkout</small>
        </div>

        <div class="mt-4 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
            <p class="text-blue-400 text-sm">
                <i class="fas fa-lightbulb mr-2"></i> Clear and transparent refund policies help build customer trust
            </p>
        </div>
    </div>

    <!-- Payment Method Configuration -->
    <div class="card-luxury">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-credit-card text-emerald-400 mr-3"></i> Payment Methods Configuration
        </h2>

        <div class="space-y-6">
            <!-- UPI Payment -->
            <div class="bg-slate-900/30 p-6 rounded-lg border border-slate-700/50">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="payment_methods[upi_enabled]" class="w-5 h-5 rounded" value="1"
                           {{ (old('payment_methods.upi_enabled', $settings['upi_enabled'] ?? '1') == '1') ? 'checked' : '' }}>
                    <h3 class="text-lg font-semibold text-white ml-3 flex items-center">
                        <i class="fas fa-mobile-alt text-cyan-400 mr-2"></i> UPI Payment
                    </h3>
                </div>
                <div class="space-y-4 ml-8">
                    <div>
                        <label class="label">UPI ID</label>
                        <input type="text" name="payment_methods[upi_id]" class="input-field" placeholder="eventbooking@upi" 
                               value="{{ old('payment_methods.upi_id', $settings['upi_id'] ?? 'eventbooking@upi') }}">
                        <small class="text-gray-500 mt-1 block">UPI ID for receiving payments</small>
                    </div>
                    <div>
                        <label class="label">Merchant Name</label>
                        <input type="text" name="payment_methods[upi_merchant_name]" class="input-field" placeholder="Event Booking Platform" 
                               value="{{ old('payment_methods.upi_merchant_name', $settings['upi_merchant_name'] ?? 'Event Booking') }}">
                        <small class="text-gray-500 mt-1 block">Name displayed in UPI apps</small>
                    </div>
                </div>
            </div>

            <!-- Stripe Payment -->
            <div class="bg-slate-900/30 p-6 rounded-lg border border-slate-700/50">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="payment_methods[stripe_enabled]" class="w-5 h-5 rounded" value="1"
                           {{ (old('payment_methods.stripe_enabled', $settings['stripe_enabled'] ?? '') == '1') ? 'checked' : '' }}>
                    <h3 class="text-lg font-semibold text-white ml-3 flex items-center">
                        <i class="fas fa-cc-stripe text-blue-400 mr-2"></i> Stripe (Credit/Debit Cards)
                    </h3>
                </div>
                <div class="space-y-4 ml-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Stripe Public Key</label>
                            <input type="password" name="payment_methods[stripe_public_key]" class="input-field" placeholder="pk_live_..." 
                                   value="{{ old('payment_methods.stripe_public_key', $settings['stripe_public_key'] ?? '') }}">
                            <small class="text-gray-500 mt-1 block">Get from Stripe Dashboard</small>
                        </div>
                        <div>
                            <label class="label">Stripe Secret Key</label>
                            <input type="password" name="payment_methods[stripe_secret_key]" class="input-field" placeholder="sk_live_..." 
                                   value="{{ old('payment_methods.stripe_secret_key', $settings['stripe_secret_key'] ?? '') }}">
                            <small class="text-gray-500 mt-1 block">Keep this secret</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PayPal Payment -->
            <div class="bg-slate-900/30 p-6 rounded-lg border border-slate-700/50">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="payment_methods[paypal_enabled]" class="w-5 h-5 rounded" value="1"
                           {{ (old('payment_methods.paypal_enabled', $settings['paypal_enabled'] ?? '') == '1') ? 'checked' : '' }}>
                    <h3 class="text-lg font-semibold text-white ml-3 flex items-center">
                        <i class="fas fa-cc-paypal text-blue-600 mr-2"></i> PayPal
                    </h3>
                </div>
                <div class="space-y-4 ml-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">PayPal Client ID</label>
                            <input type="password" name="payment_methods[paypal_client_id]" class="input-field" placeholder="AQ_..." 
                                   value="{{ old('payment_methods.paypal_client_id', $settings['paypal_client_id'] ?? '') }}">
                            <small class="text-gray-500 mt-1 block">Get from PayPal Developer Dashboard</small>
                        </div>
                        <div>
                            <label class="label">PayPal Secret</label>
                            <input type="password" name="payment_methods[paypal_secret]" class="input-field" placeholder="EG_..." 
                                   value="{{ old('payment_methods.paypal_secret', $settings['paypal_secret'] ?? '') }}">
                            <small class="text-gray-500 mt-1 block">Keep this secret</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NetBanking Payment -->
            <div class="bg-slate-900/30 p-6 rounded-lg border border-slate-700/50">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="payment_methods[netbanking_enabled]" class="w-5 h-5 rounded" value="1"
                           {{ (old('payment_methods.netbanking_enabled', $settings['netbanking_enabled'] ?? '1') == '1') ? 'checked' : '' }}>
                    <h3 class="text-lg font-semibold text-white ml-3 flex items-center">
                        <i class="fas fa-university text-amber-400 mr-2"></i> NetBanking
                    </h3>
                </div>
                <div class="space-y-4 ml-8">
                    <div>
                        <label class="label">Gateway Name</label>
                        <select name="payment_methods[netbanking_gateway]" class="input-field">
                            <option value="razorpay" {{ (old('payment_methods.netbanking_gateway', $settings['netbanking_gateway'] ?? 'razorpay') == 'razorpay') ? 'selected' : '' }}>Razorpay</option>
                            <option value="instamojo" {{ (old('payment_methods.netbanking_gateway', $settings['netbanking_gateway'] ?? '') == 'instamojo') ? 'selected' : '' }}>Instamojo</option>
                            <option value="payu" {{ (old('payment_methods.netbanking_gateway', $settings['netbanking_gateway'] ?? '') == 'payu') ? 'selected' : '' }}>PayU</option>
                        </select>
                        <small class="text-gray-500 mt-1 block">Select your NetBanking payment gateway</small>
                    </div>
                </div>
            </div>

            <!-- Razorpay Payment -->
            <div class="bg-slate-900/30 p-6 rounded-lg border border-slate-700/50">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="payment_methods[razorpay_enabled]" class="w-5 h-5 rounded" value="1"
                           {{ (old('payment_methods.razorpay_enabled', $settings['razorpay_enabled'] ?? '1') == '1') ? 'checked' : '' }}>
                    <h3 class="text-lg font-semibold text-white ml-3 flex items-center">
                        <i class="fas fa-wallet text-blue-400 mr-2"></i> Razorpay
                    </h3>
                </div>
                <div class="space-y-4 ml-8">
                    <p class="text-gray-400 text-sm">Supports: Cards, UPI, Wallets (Paytm, Amazon Pay, PhonePe), NetBanking, NEFT/RTGS</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Razorpay Key ID</label>
                            <input type="password" name="payment_methods[razorpay_key_id]" class="input-field" placeholder="rzp_live_..." 
                                   value="{{ old('payment_methods.razorpay_key_id', $settings['razorpay_key_id'] ?? '') }}">
                            <small class="text-gray-500 mt-1 block">Get from Razorpay Dashboard</small>
                        </div>
                        <div>
                            <label class="label">Razorpay Secret Key</label>
                            <input type="password" name="payment_methods[razorpay_secret_key]" class="input-field" placeholder="..." 
                                   value="{{ old('payment_methods.razorpay_secret_key', $settings['razorpay_secret_key'] ?? '') }}">
                            <small class="text-gray-500 mt-1 block">Keep this secret</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Digital Wallets -->
            <div class="bg-slate-900/30 p-6 rounded-lg border border-slate-700/50">
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="payment_methods[wallet_enabled]" class="w-5 h-5 rounded" value="1"
                           {{ (old('payment_methods.wallet_enabled', $settings['wallet_enabled'] ?? '1') == '1') ? 'checked' : '' }}>
                    <h3 class="text-lg font-semibold text-white ml-3 flex items-center">
                        <i class="fas fa-wallet text-purple-400 mr-2"></i> Digital Wallets
                    </h3>
                </div>
                <div class="space-y-4 ml-8">
                    <p class="text-gray-400 text-sm">Supported: Paytm, Amazon Pay, PhonePe Wallet</p>
                    <div>
                        <label class="label">Gateway Name</label>
                        <select name="payment_methods[wallet_gateway]" class="input-field">
                            <option value="razorpay" {{ (old('payment_methods.wallet_gateway', $settings['wallet_gateway'] ?? 'razorpay') == 'razorpay') ? 'selected' : '' }}>Razorpay</option>
                            <option value="instamojo" {{ (old('payment_methods.wallet_gateway', $settings['wallet_gateway'] ?? '') == 'instamojo') ? 'selected' : '' }}>Instamojo</option>
                            <option value="payu" {{ (old('payment_methods.wallet_gateway', $settings['wallet_gateway'] ?? '') == 'payu') ? 'selected' : '' }}>PayU</option>
                        </select>
                        <small class="text-gray-500 mt-1 block">Select your wallet payment gateway</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 p-4 bg-cyan-500/10 border border-cyan-500/30 rounded-lg">
            <p class="text-cyan-400 text-sm">
                <i class="fas fa-shield-alt mr-2"></i> All payment credentials are securely stored. Enable payment methods available to your users.
            </p>
        </div>
    </div>

    <!-- Platform Status -->
    <div class="card-luxury">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-server text-purple-400 mr-3"></i> Platform Status
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-4 bg-slate-700/30 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400">System Status</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">
                        <i class="fas fa-check-circle mr-1"></i> Operational
                    </span>
                </div>
                <p class="text-gray-500 text-xs">Platform is running normally</p>
            </div>

            <div class="p-4 bg-slate-700/30 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400">Database Status</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">
                        <i class="fas fa-database mr-1"></i> Connected
                    </span>
                </div>
                <p class="text-gray-500 text-xs">All systems operational</p>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex gap-4 justify-end">
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>
</form>

@if($errors->any())
    <div class="mt-6 card-luxury border-l-4 border-red-500 bg-red-500/10">
        <h3 class="text-red-400 font-bold mb-2">Validation Errors:</h3>
        <ul class="text-red-400 text-sm">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="mt-6 card-luxury border-l-4 border-green-500 bg-green-500/10">
        <p class="text-green-400 flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </p>
    </div>
@endif

<style>
    .btn-secondary {
        @apply px-4 py-3 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition-colors flex items-center gap-2;
    }
</style>
@endsection
