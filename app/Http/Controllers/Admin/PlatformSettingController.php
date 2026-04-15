<?php

namespace App\Http\Controllers\Admin;

use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class PlatformSettingController extends Controller
{
    public function index()
    {
        $settings = PlatformSetting::all();
        $settingsByKey = $settings->keyBy('key');
        
        return view('admin.settings.index', compact('settingsByKey'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'platform_commission' => 'required|numeric|min:0|max:100',
            'organiser_default_commission' => 'required|numeric|min:0|max:100',
            'refund_policy' => 'nullable|string',
            'contact_email' => 'required|email',
            'support_phone' => 'nullable|string',
            'payment_methods.*' => 'nullable|string',
        ]);

        // Handle payment methods
        $paymentMethods = $request->input('payment_methods', []);
        
        // UPI settings
        PlatformSetting::setSetting('upi_enabled', isset($paymentMethods['upi_enabled']) ? '1' : '0');
        if (isset($paymentMethods['upi_id'])) {
            PlatformSetting::setSetting('upi_id', $paymentMethods['upi_id']);
        }
        if (isset($paymentMethods['upi_merchant_name'])) {
            PlatformSetting::setSetting('upi_merchant_name', $paymentMethods['upi_merchant_name']);
        }

        // Stripe settings
        PlatformSetting::setSetting('stripe_enabled', isset($paymentMethods['stripe_enabled']) ? '1' : '0');
        if (isset($paymentMethods['stripe_public_key'])) {
            PlatformSetting::setSetting('stripe_public_key', $paymentMethods['stripe_public_key']);
        }
        if (isset($paymentMethods['stripe_secret_key'])) {
            PlatformSetting::setSetting('stripe_secret_key', $paymentMethods['stripe_secret_key']);
        }

        // PayPal settings
        PlatformSetting::setSetting('paypal_enabled', isset($paymentMethods['paypal_enabled']) ? '1' : '0');
        if (isset($paymentMethods['paypal_client_id'])) {
            PlatformSetting::setSetting('paypal_client_id', $paymentMethods['paypal_client_id']);
        }
        if (isset($paymentMethods['paypal_secret'])) {
            PlatformSetting::setSetting('paypal_secret', $paymentMethods['paypal_secret']);
        }

        // Razorpay settings
        PlatformSetting::setSetting('razorpay_enabled', isset($paymentMethods['razorpay_enabled']) ? '1' : '0');
        if (isset($paymentMethods['razorpay_key_id'])) {
            PlatformSetting::setSetting('razorpay_key_id', $paymentMethods['razorpay_key_id']);
        }
        if (isset($paymentMethods['razorpay_secret_key'])) {
            PlatformSetting::setSetting('razorpay_secret_key', $paymentMethods['razorpay_secret_key']);
        }

        // NetBanking settings
        PlatformSetting::setSetting('netbanking_enabled', isset($paymentMethods['netbanking_enabled']) ? '1' : '0');
        if (isset($paymentMethods['netbanking_gateway'])) {
            PlatformSetting::setSetting('netbanking_gateway', $paymentMethods['netbanking_gateway']);
        }

        // Wallet settings
        PlatformSetting::setSetting('wallet_enabled', isset($paymentMethods['wallet_enabled']) ? '1' : '0');
        if (isset($paymentMethods['wallet_gateway'])) {
            PlatformSetting::setSetting('wallet_gateway', $paymentMethods['wallet_gateway']);
        }

        // Other settings
        foreach ($validated as $key => $value) {
            if ($key !== 'payment_methods' && !is_array($value)) {
                PlatformSetting::setSetting($key, $value);
            }
        }

        return redirect()->back()->with('success', 'Payment settings and platform configuration updated successfully!');
    }
}
