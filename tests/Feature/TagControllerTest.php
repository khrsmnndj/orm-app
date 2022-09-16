<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Tag;

class TagControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase, WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_tags()
    {
        $tags = Tag::factory()->count(5)->create();
        $response = $this->get('/api/v0/tags');
        $response->assertOk();

        $this->assertEquals(5, $tags->count());
    }

    public function test_create_a_tag()
    {
        $tags = Tag::factory()->create();
        $products = Product::factory()->create();

        $data = [
            "product_name" => "Ibm",
            "tag_name" => "tests",
        ];

        $response = $this->postJson('/api/v0/tags', $data);
        $response->assertCreated();
    }

    public function test_failed_create_a_tag()
    {
        $tags = Tag::factory()->create();
        $products = Product::factory()->create();

        $data = [
            "product_name" => "Ibm",
            "tag_name" => "tests",
        ];

        $response = $this->postJson('/api/v0/tags', $data);
        $response->assertCreated();
    }

    public function test_update_a_tag()
    {
        $tag = Tag::factory()->create();

        $data = [
            'tag_name' => 'MIT',
        ];

        $response = $this->putJson("/api/v0/tags/{$tag->id}", $data);

        $response->assertStatus(201);
    }

    public function test_tags_instance_of_products()
    {
        $tags = Tag::factory()->create();
        $products = Product::factory()->create();

        $this->assertInstanceOf(Collection::class, $tags->products);
    }

    public function test_delete_a_tag()
    {
        $tag = Tag::factory()->create();
        $response = $this->deleteJson("/api/v0/tags/{$tag->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }
}
