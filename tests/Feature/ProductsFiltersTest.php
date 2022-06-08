<?php

namespace Tests\Feature;

use App\Http\Livewire\ProductsIndex;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Livewire\Livewire;
use Tests\TestCase;

class ProductsFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function searching_works()
    {
        $p1 = Product::factory()->create([
            'name' => 'First product',
        ]);

        $p2 = Product::factory()->create([
            'name' => 'Second product',
        ]);

        $p3 = Product::factory()->create([
            'name' => 'Third product',
        ]);

        Livewire::test(ProductsIndex::class)
            ->set('search', 'Seco')
            ->assertViewHas('products', function ($products) {
                return $products->count() === 1
                    && $products->first()->name === 'Second product';
            });
    }

    /** @test */
    public function min_price_filter_works()
    {
        $p1 = Product::factory()->create([
            'name' => 'First product',
        ]);

        $p1->prices()->create(['value' => 100.00]);

        $p2 = Product::factory()->create([
            'name' => 'Second product',
        ]);

        $p2->prices()->create(['value' => 200.00]);

        $p3 = Product::factory()->create([
            'name' => 'Third product',
        ]);

        $p3->prices()->create(['value' => 300.00]);

        Livewire::test(ProductsIndex::class)
            ->set('minPrice', 201)
            ->assertViewHas('products', function ($products) {
                return $products->count() === 1
                    && $products->first()->name === 'Third product';
            });
    }
}
