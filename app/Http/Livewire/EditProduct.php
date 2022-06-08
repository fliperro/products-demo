<?php

namespace App\Http\Livewire;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Livewire\Component;

class EditProduct extends Component
{
    public Product $product;

    public string $name = '';

    public string $description = '';

    public array $prices = [];

    public function rules(): array
    {
        return (new StoreProductRequest)->rules();
    }

    public function mount(Product $product)
    {
        $this->product = $product;

        $this->name = $product->name;
        $this->description = $product->description;

        $this->prices = $product->prices()->select('value')->get()->toArray();
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

        $this->product->update([
            'name' => $this->name,
            'description' => $this->description
        ]);

        $this->product->prices()->delete();

        foreach ($this->prices as $price) {
            $this->product->prices()->create($price);
        }

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.edit-product');
    }
}
