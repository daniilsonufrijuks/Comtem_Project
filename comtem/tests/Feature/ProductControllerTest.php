<?php

namespace Tests\Feature;

use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
//    public function test_example(): void
//    {
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//    }

    public function test_get_components_products_returns_correct_products()
    {
        // Arrange: Create test products

        // Act: Make a GET request to the endpoint
        $response = $this->getJson('/products/components');

        // Assert: Check if the response contains only "Component" category products
        $response->assertStatus(200)
            ->assertJsonCount(34); // Only 2 "Component" products should be returned

    }

    public function test_get_laptops_products_returns_correct_products()
    {
        // Arrange: Create test products

        // Act: Make a GET request to the endpoint
        $response = $this->getJson('/products/laptops');

        // Assert: Check if the response contains only "Component" category products
        $response->assertStatus(200)
            ->assertJsonCount(7); // Only 2 "Component" products should be returned

    }

    public function test_get_pcs_products_returns_correct_products()
    {
        // Arrange: Create test products

        // Act: Make a GET request to the endpoint
        $response = $this->getJson('/products/pcs');

        // Assert: Check if the response contains only "Component" category products
        $response->assertStatus(200)
            ->assertJsonCount(4); // Only 2 "Component" products should be returned

    }
}
