<?php

namespace App\Http\Livewire;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Livewire\Component;

class CreateProduct extends Component
{
    public string $name = '';

    public string $description = '';

    public array $prices = [];

    public function rules(): array
    {
        return (new StoreProductRequest)->rules();
    }

    public function mount()
    {
        $this->addBlankPriceInput();
    }

    public function addBlankPriceInput()
    {
        $this->prices[] = ['value' => ''];
    }

    public function removePriceInput(int $index)
    {
        // we do not remove first price input
        if ($index === 0) {
            return;
        }

        unset($this->prices[$index]);
    }

    public function save()
    {
        $this->validate();

        $product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        foreach ($this->prices as $price) {
            $product->prices()->create($price);
        }

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.create-product');
    }
}
