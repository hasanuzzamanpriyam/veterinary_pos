<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Stock extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage = 10;
    public $queryString = '';
    public $store_id;
    public $product_id;

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }

    public function productSearch()
    {
        $this->resetPage();
    }

    public function resetData()
    {
        $this->queryString = null;
        $this->perPage = 10;
        $this->store_id = null;
        $this->product_id = null;
        $this->resetPage();
        redirect(route('product.stock'));
    }

    public function render()
    {
        $all_products = Product::latest()
            ->orderBy('code', 'asc')
            ->get();
        $products_query = Product::query()
            ->when(!empty($this->queryString), function (Builder $query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->queryString}%")
                             ->orWhere('code', 'like', "%{$this->queryString}%");
                });
            })
            ->when(!empty($this->product_id) && $this->product_id != 'all', function (Builder $query) {
                $query->where('id', $this->product_id);
            })
            ->orderby('code', 'asc');

        if(isset($this->perPage) && $this->perPage == 'all'){
            $products = $products_query->get();
        }else{
            $products = $products_query->paginate((int) $this->perPage);
        }

        $stocks = ProductStore::query()
            ->when(!empty($this->queryString), function (Builder $query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('product_name', 'like', "%{$this->queryString}%")
                             ->orWhere('product_code', 'like', "%{$this->queryString}%");
                });
            })
            ->when(!empty($this->product_id) && $this->product_id != 'all', function (Builder $query) {
                $query->where('product_id', $this->product_id);
            })
            ->when(!empty($this->store_id) && $this->store_id != 'all', function (Builder $query) {
                $query->where('product_store_id', $this->store_id);
            })->with('product')->get();

        $grouped = $stocks->groupBy('product_id')->map(function ($items) {

            return [
                'product_id' => $items->first()->product_id,
                'code' => $items->first()->product_code,
                'product_name' => $items->first()->product_name, // add more fields as needed
                'qty' => $items->sum('product_quantity'),
                'purchase_price' => $items->first()->product->purchase_rate,
                    'sale_price' => $items->first()->product->price_rate,
                    'offer' => $items->first()->product->activeOffer(),
                    'sale_price_with_offer' => $items->first()->product->priceWithOffer($items->first()->product->price_rate)['price'],
                'category' => $items->first()->product->category->name ?? 'null',
                'type' => $items->first()->product->type,
                'size' => $items->first()->product->size->name,
                'brand' => $items->first()->product->brand->name,
                'group' => $items->first()->product->productGroup->name
            ];
        })->sortBy('code')->values();

        // filter by stock
        $grouped = $grouped->where('qty', '>', 0);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->perPage === 'all' ? $grouped->count() : (int) $this->perPage;
        $pagedItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $mergedProducts = new LengthAwarePaginator(
            $pagedItems,
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        // dd($mergedProducts);

        $stock_list = $mergedProducts;
        $stores = Store::latest()->get();

        return view('livewire.product.stock', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
