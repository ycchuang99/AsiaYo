<?php

namespace App\Services;

use App\Utilities\CurrencyPriceTransformerUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService
{
    public function __construct() {}

    public function validateFormat(array $data): array
    {
        $this->validateCurrency($data['currency']);

        if ($data['currency'] === 'USD') {
            $data['price'] = CurrencyPriceTransformerUtil::transform(floatval($data['price']), 'USD', 'TWD');
            $data['currency'] = 'TWD';
        }

        $this->validateName($data['name']);
        $this->validatePrice($data['price']);

        return $data;
    }

    private function validateCurrency(string $currency): void
    {
        if (! in_array($currency, ['TWD', 'USD'])) {
            throw new BadRequestHttpException(__('error.currency_invalid'));
        }
    }

    private function validateName(string $name): void
    {
        if (! preg_match('/^[a-zA-Z\s]+$/', $name)) {
            throw new BadRequestHttpException(__('error.name_contains_invalid_characters'));
        }

        foreach (explode(' ', $name) as $word) {
            if (! preg_match('/^[A-Z]/', $word)) {
                throw new BadRequestHttpException(__('error.name_not_capitalized'));
            }
        }
    }

    private function validatePrice(float $price): void
    {
        if ($price > 2000) {
            throw new BadRequestHttpException(__('error.price_invalid'));
        }
    }
}
