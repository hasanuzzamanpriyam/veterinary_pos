<?php

namespace App\Livewire\Bonus\Monthly;

use App\Models\MonthlyBonusCount;
use App\Models\Supplier;
use App\Models\SupplierTransactionDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Livewire\Component;

class Index extends Component
{


    public $startDate;
    public $endDate;
    public $all_months = [];
    public $all_data = [];
    public Collection $purchaseProducts;
    public $suppliers;
    public $get_supplier_id;

    public function mount(Request $request) {
        $this->purchaseProducts = new Collection();
        $this->suppliers = Supplier::get();
        $params = $request->all();
        if(isset($params['startdate']) && isset($params['enddate']) && isset($params['supplier_id'])) {
            $this->startDate = $params['startdate'];
            $this->endDate = $params['enddate'];
            $this->get_supplier_id = $params['supplier_id'];
            $this->supplierDueSearch();
        }
    }

    public function resetSupplier()
    {
        $this->get_supplier_id = null;
        $this->startDate = $this->endDate = '';
        $this->purchaseProducts = new Collection();
    }



    public function supplierDueSearch()
    {

        if ($this->get_supplier_id && ($this->startDate && $this->endDate)) {
            $this->purchaseProducts = SupplierTransactionDetails::
                where('supplier_id', $this->get_supplier_id)
                ->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

    }


    public function render()
    {
        $bonuses = [];
        $party = [];

        if ($this->startDate && $this->endDate && $this->get_supplier_id) {

        $party = Supplier::where('id', $this->get_supplier_id)->first();

        $bonuses = $this->purchaseProducts
            ->groupBy(function($transaction) {
                return \Carbon\Carbon::parse($transaction->date)->format('Y-m'); // ✅ প্রথমে তারিখ এবং সাপ্লাইয়ার অনুযায়ী গ্রুপিং
            })
            ->map(function ($group) {
                $first = $group->first();
                $month = \Carbon\Carbon::parse($first->date)->format('Y-m');
                $supplierId = $first->supplier_id;

                // total weight after calculating net quantity
                $totalWeight = $group->sum(function ($transaction) {
                    $netQty = $transaction->quantity - $transaction->discount_qty - $transaction->return_qty;
                    return ($netQty * $transaction->weight) / 1000;
                });


                // fetch bonus rate from DB
                $bonusRate = MonthlyBonusCount::where('supplier_id', $supplierId)
                    ->where('start', '<', $totalWeight)
                    ->where('end', '>=', $totalWeight)
                    ->value('rate') ?? 0;

                return (object)[
                    'month' => $month,
                    'weight' => $totalWeight,
                    'rate' => $bonusRate,
                    'bonusAmount' => $totalWeight * $bonusRate,
                ];
            });
        }

        return view('livewire.bonus.monthly.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
