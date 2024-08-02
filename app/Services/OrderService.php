<?php

namespace App\Services;

use App\Support\CurrencyPriceTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService
{
    public function __construct(private CurrencyPriceTransformer $currencyPriceTransformer) {}

    public function transform(array $data): array
    {
        if ($data['currency'] === 'USD') {
            $data['price'] = $this->currencyPriceTransformer->transform(floatval($data['price']), 'USD', 'TWD');
            $data['currency'] = 'TWD';
        }

        $this->validateFormat($data);

        return $data;
    }

    private function validateFormat(array $data): void
    {
        if (! in_array($data['currency'], ['TWD', 'USD'])) {
            throw new BadRequestHttpException(__('error.currency_invalid'));
        }

        if (! preg_match('/^[a-zA-Z\s]+$/', $data['name'])) {
            throw new BadRequestHttpException(__('error.name_contains_invalid_characters'));
        }

        foreach (explode(' ', $data['name']) as $word) {
            if (! preg_match('/^[A-Z]/', $word)) {
                throw new BadRequestHttpException(__('error.name_not_capitalized'));
            }
        }

        if ($data['price'] > 2000) {
            throw new BadRequestHttpException(__('error.price_invalid'));
        }
    }
}
