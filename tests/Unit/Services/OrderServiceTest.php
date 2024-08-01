<?php

namespace Tests\Unit\Services;

use App\Services\OrderService;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderService $orderService;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderService = app(OrderService::class);
    }

    public function testCanValidateOrderFormat(): void
    {
        $data = [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ];

        $result = $this->orderService->validateFormat($data);

        $this->assertEquals($data, $result);
    }

    public function testValidationFailsIfCurrencyIsInvalid(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);

        $data = [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'JPY',
        ];

        $this->orderService->validateFormat($data);
    }

    public function testValidationFailsIfNameContainsInvalidCharacters(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);

        $data = [
            'id' => '1',
            'name' => 'John Doe123',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ];

        $this->orderService->validateFormat($data);
    }

    public function testValidationFailsIfNameIsNotCapitalized(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);

        $data = [
            'id' => '1',
            'name' => 'john Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ];

        $this->orderService->validateFormat($data);
    }

    public function testValidationFailsIfPriceIsInvalid(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);

        $data = [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 2001,
            'currency' => 'TWD',
        ];

        $this->orderService->validateFormat($data);
    }
}
