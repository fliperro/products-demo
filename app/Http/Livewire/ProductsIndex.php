<?php

namespace App\Http\Livewire;

use App\Models\Price;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class ProductsIndex extends Component
{
    use WithPagination;

    public $allowedSort = [
        'name' => [
            'field' => 'name',
            'dir' => 'asc',
            'label' => 'Nazwa A-Z'
        ],
        '-name' => [
            'field' => 'name',
            'dir' => 'desc',
            'label' => 'Nazwa Z-A'
        ],
        'prices_min_value' => [
            'field' => 'prices_min_value',
            'dir' => 'asc',
            'label' => 'Cena min. - rosnąco'
        ],
        '-prices_min_value' => [
            'field' => 'prices_min_value',
            'dir' => 'desc',
            'label' => 'Cena min. - malejąco'
        ],
        'prices_max_value' => [
            'field' => 'prices_max_value',
            'dir' => 'asc',
            'label' => 'Cena maks. - rosnąco'
        ],
        '-prices_max_value' => [
            'field' => 'prices_max_value',
            'dir' => 'desc',
            'label' => 'Cena maks. -  malejąco'
        ],
        'created_at' => [
            'field' => 'created_at',
            'dir' => 'asc',
            'label' => 'Data dodania - rosnąco'
        ],
        '-created_at' => [
            'field' => 'created_at',
            'dir' => 'desc',
            'label' => 'Data dodania - malejąco'
        ],

    ];

    public $showDeleteModal = false;

    public $productIdForDelete = null;

    public $search;

    public $minPrice;

    public $maxPrice;

    public $sort = '-created_at';

    protected $queryString = [
        'search' => ['except' => ''],
        'minPrice',
        'maxPrice',
        'sort'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getProductsProperty()
    {
        $query = Product::query()
            ->withMax('prices', 'value') // for sorting
            ->withMin('prices', 'value') // for sorting
            ->with('lowestPrice', 'highestPrice')
            ->withCount('prices')
            ->when(strlen($this->search) >= 3, function ($query) {
                $query->where('name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('description', 'LIKE', '%'.$this->search.'%');
            })
            ->when(is_numeric($this->minPrice), function ($query) {
                $query->whereHas('lowestPrice', function ($query) {
                    $query->where('value', '>=', $this->minPrice * 100);
                });
            })
            ->when(is_numeric($this->maxPrice), function ($query) {
                $query->whereHas('highestPrice', function ($query) {
                    $query->where('value', '<=', $this->maxPrice * 100);
                });
            });

        return $this->applySorting($query)
            ->paginate(10)
            ->withQueryString();
    }

    public function applySorting($query)
    {
        if (array_key_exists($this->sort, $this->allowedSort)) {
            $query->orderBy($this->allowedSort[$this->sort]['field'], $this->allowedSort[$this->sort]['dir']);
        }

        return $query;
    }

    public function showDeleteModal($id)
    {
        $this->productIdForDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteSelectedProduct()
    {
        Product::destroy($this->productIdForDelete);

        $this->productIdForDelete = null;
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.products-index', [
            'products' => $this->products
        ]);
    }
}
