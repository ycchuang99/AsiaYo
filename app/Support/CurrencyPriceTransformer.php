<?php

namespace App\Support;

class CurrencyPriceTransformer
{
    const EXCHANGE_RATE = [
        'USD' => [
            'TWD' => 31,
        ],
    ];

    /**
     * Transform the price from one currency to another
     *
     * @param  string  $from  Currency to transform from e.g. USD
     * @param  string  $to  Currency to transform to e.g. TWD
     *
     * @throws \InvalidArgumentException
     */
    public function transform(float $price, string $from, string $to): float
    {
        $exchangeRate = self::EXCHANGE_RATE[$from][$to] ?? -1;

        if ($exchangeRate === -1) {
            throw new \InvalidArgumentException('Unsupported currency exchange');
        }

        return round($price * $exchangeRate, 4);
    }
}
