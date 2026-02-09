<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Log;

class ExchangeRateService
{
    /**
     * Update exchange rates from external API and cache them in system_settings.
     * Only updates if the existing data is older than 24 hours.
     */
    public static function updateRates()
    {
        try {
            // Check if we need to update
            // Update if: 
            // 1. kurs_usd is missing or > 24 hours old
            // 2. kurs_sar or kurs_myr are missing (even if usd is fresh)
            $usd = SystemSetting::where('key', 'kurs_usd')->first();
            $sar = SystemSetting::where('key', 'kurs_sar')->first();
            $myr = SystemSetting::where('key', 'kurs_myr')->first();
            
            $needsUpdate = false;
            if (!$usd || !$sar || !$myr) {
                $needsUpdate = true;
            } elseif ($usd->updated_at->lt(now()->subHours(24))) {
                $needsUpdate = true;
            }

            if (!$needsUpdate) {
                return; // Rates are fresh and present
            }

            // Fetch from API (USD base)
            $response = Http::timeout(10)->get('https://api.exchangerate-api.com/v4/latest/USD');

            if ($response->successful()) {
                $data = $response->json();
                $rates = $data['rates'] ?? [];

                if (isset($rates['IDR'])) {
                    $usdRate = $rates['IDR'] * 100; // Store as integer (x100)
                    
                    // Update USD
                    SystemSetting::updateOrCreate(
                        ['key' => 'kurs_usd'],
                        ['value' => (int) $usdRate]
                    );

                    // Update SAR (Cross rate: USD/SAR * SAR/IDR)
                    // Actually API gives USD as base, so SAR is USD for $rates['SAR'] SAR
                    // 1 USD = $rates['IDR'] IDR
                    // 1 USD = $rates['SAR'] SAR
                    // 1 SAR = ($rates['IDR'] / $rates['SAR']) IDR
                    if (isset($rates['SAR']) && $rates['SAR'] > 0) {
                        $sarToIdr = ($rates['IDR'] / $rates['SAR']) * 100;
                        SystemSetting::updateOrCreate(
                            ['key' => 'kurs_sar'],
                            ['value' => (int) $sarToIdr]
                        );
                    }

                    // Update MYR (RM)
                    if (isset($rates['MYR']) && $rates['MYR'] > 0) {
                        $myrToIdr = ($rates['IDR'] / $rates['MYR']) * 100;
                        SystemSetting::updateOrCreate(
                            ['key' => 'kurs_myr'],
                            ['value' => (int) $myrToIdr]
                        );
                        // Also sync kurs_rm for compatibility if needed
                        SystemSetting::updateOrCreate(
                            ['key' => 'kurs_rm'],
                            ['value' => (int) $myrToIdr]
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('ExchangeRateService Error: ' . $e->getMessage());
        }
    }

    public function getRate($currency)
    {
        if ($currency === 'IDR') {
            return 1;
        }

        self::updateRates();

        $key = 'kurs_' . strtolower($currency);
        
        // Handle RM/MYR aliasing
        if ($currency === 'RM') {
            $key = 'kurs_myr';
        }

        $setting = SystemSetting::where('key', $key)->first();
        
        // Return value divided by 100 as stored
        return $setting ? $setting->value / 100 : 0;
    }
}
