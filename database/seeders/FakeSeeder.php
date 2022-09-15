<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\License;
use App\Models\Product;
use App\Models\Tag;
use App\Models\ProductTag;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 20; $i++)
        {
            Product::factory()
            ->has(License::factory()->count(1))
            ->create();
        }

        for($i = 0; $i < 20; $i++)
        {
            Tag::factory()->create();
        }

        for($i = 0; $i < 20; $i++)
        {
            ProductTag::factory()->create();
        }

    }
}
