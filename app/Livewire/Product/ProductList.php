<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductStore;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 15;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->select('products.*')
            ->with(['brand', 'category', 'productGroup', 'size', 'productType'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('products.name', 'like', '%' . $this->search . '%')
                        ->orWhere('products.barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('brands.name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('category', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('productGroup', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('size', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy('brands.name', 'asc')
            ->orderBy('products.name', 'asc')
            ->paginate($this->perPage);

        $stocks = ProductStore::get();
        $stockList = $stocks->groupBy('product_id')->map(function ($items) {
            return ['qty' => $items->sum('product_quantity')];
        });

        return view('livewire.product.index', get_defined_vars());
    }
}
