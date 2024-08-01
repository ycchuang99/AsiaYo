<?php

namespace Tests\Unit\Utilities;

use App\Utilities\CurrencyPriceTransformerUtil;
use PHPUnit\Framework\TestCase;

class CurrencyPriceTransformerUtilTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanTransformCurrencyPrice(): void
    {
        $price = 1000;
        $from = 'USD';
        $to = 'TWD';

        $result = CurrencyPriceTransformerUtil::transform($price, $from, $to);

        $this->assertEquals(31000, $result);
    }

    public function testThrowsExceptionIfUnsupportedCurrencyExchange(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $price = 1000;
        $from = 'USD';
        $to = 'JPY';

        CurrencyPriceTransformerUtil::transform($price, $from, $to);
    }
}
