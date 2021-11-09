<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductModuleTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=SystemAdminSeeder');
    }

    public function test_get_all_products_with_pagination()
    {
        $response = $this->get('/api/products');
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'quantity',
                    'image'
                ]
            ],
            'links',
            'meta'
        ]);
    }

    public function test_get_product_details()
    {
        $product = Product::factory()->create();
        $response = $this->get('/api/products/' . $product->id);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'price',
                'quantity',
                'image'
            ],
            'status',
            'message'
        ]);
    }

    public function test_delete_product()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['delete-product']
        );
        $product = Product::factory()->create();
        $response = $this->delete('/api/products/' . $product->id);
        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'status',
            'message'
        ]);
    }

    public function test_update_product_stock()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['product-stock-update']
        );
        $product = Product::factory()->create();
        $response = $this->post('/api/update-stock', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);
        $response->assertOk();
    }
}
