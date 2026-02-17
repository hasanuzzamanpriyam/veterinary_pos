<?php

namespace App\Livewire\Bonus\Cashoffer;

use App\Models\CashOffer;
use App\Models\SupplierBonus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListAll extends Component
{
    use WithPagination;

    public $search_query;

    #[Url(as: 'perpage')]
    public $perPage = 10;

    public $startDate;
    public $endDate;
    public $all_months = [];
    public $all_data = [];
    public Collection $cashOffers;
    public $suppliers;
    public $bonuses = [];

    public function mount(){
        $this->cashOffers = new Collection();

        $this->startDate = session()->get('cashStartDate');
        $this->endDate = session()->get('cashEndDate');
        $this->getLatestOffers();
    }

    public function supplierOfferSearch()
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
        $this->startDate = null;
        $this->endDate = null;
        $this->perPage = 10;
        $this->resetPage();
    }

    public function delete($id)
    {
        if($id){
            $cashOffer = CashOffer::find($id);
            $supplier_id = $cashOffer->supplier_id;
            $cashOffer->delete();

            $bonus = SupplierBonus::where('supplier_id', $supplier_id)->first();

            if($bonus) {
                if ($bonus->monthly == false && $bonus->yearly == false) {
                    $bonus->delete();
                }else{
                    SupplierBonus::updateOrCreate(
                        ['supplier_id' => $supplier_id],
                        [
                            'cash_offer' => false,
                        ]
                    );
                }
            }
            $this->resetPage();
        }
    }

    // test

    protected function getLatestOffers()
    {

        $queryZ = CashOffer::with('supplier')
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('date', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ]);
            })
            ->when($this->search_query, function ($query) {
                $query->whereHas('supplier', function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search_query . '%')
                    ->orWhere('address', 'like', '%' . $this->search_query . '%')
                    ->orWhere('email', 'like', '%' . $this->search_query . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search_query . '%');
                });

                $query->orWhere('description', 'like', '%' . $this->search_query . '%');
                $query->orWhere('amount', 'like', '%' . $this->search_query . '%');
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($this->perPage === 'all') {
            $offers = $queryZ->get();
        } else {
            $offers = $queryZ->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        session()->put('cashStartDate', $this->startDate);
        session()->put('cashEndDate', $this->endDate);

        return $offers;
    }



    public function render()
    {
        $offers = $this->getLatestOffers();

        return view('livewire.bonus.cashoffer.list-all', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
