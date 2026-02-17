<?php

namespace App\Livewire\BonusCount\Monthly;

use App\Models\MonthlyBonusCount;
use App\Models\Supplier;
use App\Models\SupplierBonus;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListAll extends Component
{
    use WithPagination;

    public $search_query;

    #[Url(as: 'perpage')]
    public $perPage = 10;

    public function filterData()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function resetData()
    {
        $this->search_query = null;
        $this->perPage = 10;
        $this->resetPage();
    }


    public function render()
    {
        $queryR = SupplierBonus::query()
        ->join('suppliers', 'supplier_bonuses.supplier_id', '=', 'suppliers.id')
        ->where('supplier_bonuses.monthly', true)
        ->when(!empty($this->search_query), function(Builder $query){
            $query->where(function ($q) {
                $q->where('suppliers.company_name', 'like', '%' . $this->search_query . '%')
                ->orWhere('suppliers.address', 'like', '%' . $this->search_query . '%')
                ->orWhere('suppliers.mobile', 'like', '%' . $this->search_query . '%');
            });
        })
        ->orderBy('suppliers.company_name', 'asc');

        if ($this->perPage === 'all') {
            $parties = $queryR->get();
            $bonus_list = MonthlyBonusCount::orderBy('id', 'asc')->get();
        } else {
            $parties = $queryR->paginate((int) $this->perPage); // Paginate based on the dropdown value
            $bonus_list = MonthlyBonusCount::whereIn('supplier_id', $parties->pluck('id')->toArray())->orderBy('id', 'asc')->get();
        }

        return view('livewire.bonus-count.monthly.list-all', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
