<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait KeyGenerator
{
    /**
     * Generate a new unique product key.
     */
    public static function generateKey(): string
    {
        return strtoupper(Str::random(4) . '-' . Str::random(6) . '-' . Str::random(6).'-'.Str::random(4));
    }

    /**
     * Generate a unique serial number based on user type.
     *
     * @param string $type
     * @return string
     */
    public static function generateSerialNo($type = 'product')
    {
        $prefixes = [
            'platform' => 'PLT',
            'product'  => 'PRD',
        ];
        $prefix = $prefixes[$type];
        do {
            // Generate a unique alphanumeric part (4-6 format)
            $serialNo = $prefix . '-' . strtoupper(Str::random(4) . '-' . Str::random(6));
        } while (DB::table('users')->where('serial_no', $serialNo)->exists());

        return $serialNo;
    }

    public static function generateProductKey()
    {
        $prefix = 'DINTEK';
        do {
            // Generate a unique alphanumeric part (4-6 format)
            $key = $prefix . '-' . strtoupper(Str::random(4) . '-' . Str::random(6));
        } while (DB::table('product_keys')->where('key', $key)->exists());

        return $key;
    }

     /**
     * Masks a product key so that only the last $visibleChars are visible.
     *
     * @param string|null $key The product key to mask.
     * @param int $visibleChars The number of characters to leave visible at the end of the key.
     * @return string The masked product key.
     */
    public static function maskProductKey(?string $key, int $visibleChars = 4): string
    {
        if (!$key) {
            return 'N/A';
        }

        $maskedPart = str_repeat('*', max(0, strlen($key) - $visibleChars));
        $visiblePart = substr($key, -$visibleChars);

        return $maskedPart . $visiblePart;
    }
}
