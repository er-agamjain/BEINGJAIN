<?php

namespace App\Helpers;

use App\Models\PlatformSetting;

class PaymentHelper
{
    /**
     * Get all enabled payment methods
     */
    public static function getEnabledMethods()
    {
        return [
            'upi' => [
                'enabled' => self::isUPIEnabled(),
                'name' => 'UPI',
                'icon' => 'fas fa-mobile-alt',
                'color' => 'cyan',
                'description' => 'Pay via Google Pay, PhonePe, Paytm'
            ],
            'razorpay' => [
                'enabled' => self::isRazorpayEnabled(),
                'name' => 'Razorpay',
                'icon' => 'fas fa-wallet',
                'color' => 'blue',
                'description' => 'Cards, Wallets, NetBanking, UPI'
            ],
            'stripe' => [
                'enabled' => self::isStripeEnabled(),
                'name' => 'Credit/Debit Cards',
                'icon' => 'fas fa-cc-stripe',
                'color' => 'blue',
                'description' => 'Visa, Mastercard with 3D Secure'
            ],
            'paypal' => [
                'enabled' => self::isPayPalEnabled(),
                'name' => 'PayPal',
                'icon' => 'fas fa-cc-paypal',
                'color' => 'blue-600',
                'description' => 'International cards and PayPal balance'
            ],
            'netbanking' => [
                'enabled' => self::isNetbankingEnabled(),
                'name' => 'NetBanking',
                'icon' => 'fas fa-university',
                'color' => 'amber',
                'description' => 'All major banks supported'
            ],
            'wallet' => [
                'enabled' => self::isWalletEnabled(),
                'name' => 'Digital Wallets',
                'icon' => 'fas fa-wallet',
                'color' => 'purple',
                'description' => 'Paytm, Amazon Pay, PhonePe Wallet'
            ]
        ];
    }

    /**
     * Get only enabled payment methods
     */
    public static function getActivePaymentMethods()
    {
        $methods = self::getEnabledMethods();
        return array_filter($methods, fn($method) => $method['enabled']);
    }

    /**
     * Check if UPI is enabled
     */
    public static function isUPIEnabled()
    {
        return PlatformSetting::getSetting('upi_enabled', '1') == '1';
    }

    /**
     * Check if Razorpay is enabled
     */
    public static function isRazorpayEnabled()
    {
        return PlatformSetting::getSetting('razorpay_enabled', '1') == '1';
    }

    /**
     * Check if Stripe is enabled
     */
    public static function isStripeEnabled()
    {
        return PlatformSetting::getSetting('stripe_enabled', '0') == '1';
    }

    /**
     * Check if PayPal is enabled
     */
    public static function isPayPalEnabled()
    {
        return PlatformSetting::getSetting('paypal_enabled', '0') == '1';
    }

    /**
     * Check if NetBanking is enabled
     */
    public static function isNetbankingEnabled()
    {
        return PlatformSetting::getSetting('netbanking_enabled', '1') == '1';
    }

    /**
     * Check if Wallet is enabled
     */
    public static function isWalletEnabled()
    {
        return PlatformSetting::getSetting('wallet_enabled', '1') == '1';
    }

    /**
     * Get UPI ID
     */
    public static function getUPIId()
    {
        return PlatformSetting::getSetting('upi_id', 'eventbooking@upi');
    }

    /**
     * Get UPI Merchant Name
     */
    public static function getUPIMerchantName()
    {
        return PlatformSetting::getSetting('upi_merchant_name', 'Event Booking');
    }

    /**
     * Get Stripe Public Key
     */
    public static function getStripePublicKey()
    {
        return PlatformSetting::getSetting('stripe_public_key', '');
    }

    /**
     * Get Stripe Secret Key
     */
    public static function getStripeSecretKey()
    {
        return PlatformSetting::getSetting('stripe_secret_key', '');
    }

    /**
     * Get PayPal Client ID
     */
    public static function getPayPalClientId()
    {
        return PlatformSetting::getSetting('paypal_client_id', '');
    }

    /**
     * Get PayPal Secret
     */
    public static function getPayPalSecret()
    {
        return PlatformSetting::getSetting('paypal_secret', '');
    }

    /**
     * Check if Razorpay key is configured
     */
    public static function getRazorpayKeyId()
    {
        $settingValue = PlatformSetting::getSetting('razorpay_key_id', '');

        if (!empty($settingValue)) {
            return $settingValue;
        }

        return config('services.razorpay.key_id', '');
    }

    /**
     * Get Razorpay Secret Key
     */
    public static function getRazorpaySecretKey()
    {
        $settingValue = PlatformSetting::getSetting('razorpay_secret_key', '');

        if (!empty($settingValue)) {
            return $settingValue;
        }

        return config('services.razorpay.secret_key', '');
    }

    /**
     * Get NetBanking Gateway
     */
    public static function getNetbankingGateway()
    {
        return PlatformSetting::getSetting('netbanking_gateway', 'razorpay');
    }

    /**
     * Get Wallet Gateway
     */
    public static function getWalletGateway()
    {
        return PlatformSetting::getSetting('wallet_gateway', 'razorpay');
    }

    /**
     * Check if any payment method is enabled
     */
    public static function hasAnyPaymentMethod()
    {
        return !empty(self::getActivePaymentMethods());
    }

    /**
     * Get payment method details
     */
    public static function getPaymentMethodDetails($method)
    {
        $methods = self::getEnabledMethods();
        return $methods[$method] ?? null;
    }
}
