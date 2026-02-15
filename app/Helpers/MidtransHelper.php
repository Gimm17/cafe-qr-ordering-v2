<?php

namespace App\Helpers;

class MidtransHelper
{
    /**
     * Map Midtrans notification payload to a human-readable payment method label.
     */
    public static function getPaymentLabel(?array $payload): string
    {
        if (!$payload) {
            return 'Midtrans';
        }

        $type = $payload['payment_type'] ?? null;

        return match ($type) {
            'bank_transfer' => static::bankTransferLabel($payload),
            'permata_va'    => 'VA Permata',
            'echannel'      => 'Mandiri Bill',
            'cstore'        => static::cstoreLabel($payload),
            'qris'          => 'QRIS',
            'gopay'         => 'GoPay',
            'shopeepay'     => 'ShopeePay',
            'credit_card'   => 'Kartu Kredit',
            'akulaku'       => 'Akulaku',
            'kredivo'       => 'Kredivo',
            default         => $type ? ucfirst(str_replace('_', ' ', $type)) : 'Midtrans',
        };
    }

    private static function bankTransferLabel(array $payload): string
    {
        // Check va_numbers array (BCA, BNI, BRI, etc.)
        if (!empty($payload['va_numbers'][0]['bank'])) {
            $bank = strtoupper($payload['va_numbers'][0]['bank']);
            return "VA {$bank}";
        }

        // Permata VA comes as permata_va_number
        if (!empty($payload['permata_va_number'])) {
            return 'VA Permata';
        }

        return 'Transfer Bank';
    }

    private static function cstoreLabel(array $payload): string
    {
        $store = $payload['store'] ?? null;
        return match (strtolower($store ?? '')) {
            'alfamart'  => 'Alfamart',
            'indomaret' => 'Indomaret',
            default     => $store ? ucfirst($store) : 'Convenience Store',
        };
    }
}
