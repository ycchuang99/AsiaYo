<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testCanValidateOrderFormat(): void
    {
        $response = $this->postJson(route('orders.transform'), [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => '1',
            'name' => 'John Doe',
            'address' => ['city' => 'Taipei', 'district' => 'Daan', 'street' => 'Ren Ai Road'],
            'price' => 1000,
            'currency' => 'TWD',
        ]);
    }

    public function testValidationFailsIfCurrencyIsInvalid(): void
    {
        $response = $this->postJson(route('orders.transform'), [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
        ]);

        $response->assertStatus(422);
    }

    public function testValidationFailsIfNameContainsInvalidCharacters(): void
    {
        $response = $this->postJson(route('orders.transform'), [
            'id' => '1',
            'name' => 'John Doe 123',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => __('error.name_contains_invalid_characters')]);
    }

    public function testValidationFailsIfNameIsNotCapitalized(): void
    {
        $response = $this->postJson(route('orders.transform'), [
            'id' => '1',
            'name' => 'john doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'TWD',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => __('error.name_not_capitalized')]);
    }

    public function testValidationFailsIfPriceIsOver2000(): void
    {
        $response = $this->postJson(route('orders.transform'), [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 2001,
            'currency' => 'TWD',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => __('error.price_invalid')]);
    }

    public function testValidationFailsIfCurrencyFormatIsWrong(): void
    {
        $response = $this->postJson(route('orders.transform'), [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'JPY',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => __('error.currency_invalid')]);
    }

    public function testValidationFailsIfPriceIsOver2000WithUSD(): void
    {
        $response = $this->postJson(route('orders.transform'), [
            'id' => '1',
            'name' => 'John Doe',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => 'Ren Ai Road',
            ],
            'price' => 1000,
            'currency' => 'USD',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => __('error.price_invalid')]);
    }
}
