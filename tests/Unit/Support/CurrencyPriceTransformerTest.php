<?php

namespace Tests\Unit\Support;

use App\Support\CurrencyPriceTransformer;
use PHPUnit\Framework\TestCase;

class CurrencyPriceTransformerTest extends TestCase
{
    private CurrencyPriceTransformer $currencyPriceTransformer;

    public function setUp(): void
    {
        parent::setUp();
        $this->currencyPriceTransformer = app(CurrencyPriceTransformer::class);
    }

    public function testCanTransformCurrencyPrice(): void
    {
        $price = 1000;
        $from = 'USD';
        $to = 'TWD';

        $result = $this->currencyPriceTransformer->transform($price, $from, $to);

        $this->assertEquals(31000, $result);
    }

    public function testThrowsExceptionIfUnsupportedCurrencyExchange(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $price = 1000;
        $from = 'USD';
        $to = 'JPY';

        $this->currencyPriceTransformer->transform($price, $from, $to);
    }
}
