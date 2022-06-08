<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\CreateProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_create_product()
    {
        $this->get(route('products.create'))->assertRedirect();
    }

    /** @test */
    public function create_product_form_validation_works()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateProduct::class)
            ->set('name', '')
            ->set('description', '')
            ->set('prices', [])
            ->call('save')
            ->assertHasErrors(['name', 'description', 'prices']);
    }

    /** @test */
    public function user_can_create_a_product()
    {
        $user = User::factory()->create();

        Livewire::actingAs(User::factory()->create())
            ->test(CreateProduct::class)
            ->set('name', 'First project')
            ->set('description', 'First project descr')
            ->set('prices', [
                ['value' => '1234.56'],
                ['value' => '3333'],
                ['value' => '0.01']
            ])
            ->call('save')
            ->assertRedirect(route('products.index'));

        $response = $this->actingAs($user)->get(route('products.index'));
        $response->assertSuccessful();
        $response->assertSee('First project');
        $response->assertSee('0.01');

        $this->assertDatabaseHas('products', [
            'name' => 'First project'
        ]);

        $this->assertDatabaseHas('prices', [
            'product_id' => 1,
            'value' => 123456,
        ]);

        $this->assertDatabaseHas('prices', [
            'product_id' => 1,
            'value' => 333300,
        ]);

        $this->assertDatabaseHas('prices', [
            'product_id' => 1,
            'value' => 1,
        ]);
    }
}
