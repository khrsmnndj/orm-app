<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Models\License;
use App\Models\Product;
use App\Models\Tag;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_products()
    {
        $products = Product::factory()->count(5)->create();
        $response = $this->get('/api/v0/licenses');
        $response->assertOk();

        $this->assertEquals(5, $products->count());
    }

    public function test_create_a_products()
    {
        $products = Product::factory()->create();
        $tags = Tag::factory()->create();
        $license = License::factory()->create([
            'product_id' => $products->id
        ]);

        $data = [
            "product_name" => "test",
            "license_name" => "Ibm",
            "tag_name" => "tests",
        ];

        $response = $this->postJson('/api/v0/products', $data);
        $response->assertCreated();
    }

    public function test_failed_create_a_products()
    {
        $products = Product::factory()->create();
        $tags = Tag::factory()->create();
        $license = License::factory()->create([
            'product_id' => $products->id
        ]);
        $data = [
            "product_name" => "test",
            "license_name" => "Ibm",
            "tag_name" => 55,        
        ];

        $response=$this->postJson('/api/v0/licenses', $data);
        $response->assertStatus(422);
    }

    public function test_update_a_product()
    {
        $product = Product::factory()->create();

        $data = [
            'product_name' => 'MIT',
        ];

        $response = $this->putJson("/api/v0/products/{$product->id}", $data);

        $response->assertStatus(201);
    }

    public function test_products_instance_of_tags()
    {
        $products = Product::factory()->create();
        $tags = Tag::factory()->create();

        $this->assertInstanceOf(Collection::class, $products->tags);
    }

    public function test_delete_a_product()
    {
        $product = Product::factory()->create();
        $license = License::factory()->create([
            'product_id' => $product->id,
        ]);
        $response = $this->deleteJson("/api/v0/products/{$product->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
        $this->assertDatabaseMissing('licenses', [
            'id' => $license->id,
        ]);
    }
}
