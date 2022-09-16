<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\License;
use App\Models\Product;

class LicenseControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_license()
    {
        $licenses = License::factory()->count(5)->create();
        $response = $this->get('/api/v0/licenses');
        $response->assertOk();

        $this->assertEquals(5, $licenses->count());
    }

    public function test_create_a_license()
    {
        $products = Product::factory()->create([
            'id' => 995, 
        ]);
        $data = [
            'license_name' => 'iBM',
            'product_id' => 995,
        ];

        $response=$this->postJson('/api/v0/licenses', $data);

        $response->assertCreated();
        $response->assertJsonPath('data.license_name', $data['license_name']);
        $response->assertJsonPath('data.product_id', $data['product_id']);
        $this->assertDatabaseHas('licenses', $data);
    }

    public function test_failed_create_a_license()
    {
        $products = Product::factory()->create([
            'id' => 995, 
        ]);
        $data = [
            'license_name' => 'iBM',
            'product_id' => 996,
        ];

        $response=$this->postJson('/api/v0/licenses', $data);
        $response->assertStatus(422);
    }

    public function test_update_a_license()
    {
        $product = Product::factory()->create([
            'id' => 23,
        ]);
        $license = License::factory()->create([
            'license_name' => 'BRIDGE',
            'product_id' => 23,
        ]);

        $data = [
            'license_name' => 'MIT',
        ];

        $response = $this->putJson("/api/v0/licenses/{$license->id}", $data);

        $response->assertStatus(201);
    }

    public function test_delete_a_license()
    {
        $license = License::factory()->create();
        $response = $this->deleteJson("/api/v0/licenses/{$license->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('licenses', [
            'id' => $license->id,
        ]);
    }
}
