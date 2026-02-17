<?php

namespace App\Livewire\Bonus\Monthly;

use App\Models\MonthlyBonusCount;
use App\Models\Supplier;
use App\Models\SupplierTransactionDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class All extends Component
{
    public $startDate;
    public $endDate;
    public $all_months = [];
    public $all_data = [];
    public Collection $purchaseProducts;
    public $suppliers;
    public $bonuses = [];

    public function mount(){
        $this->purchaseProducts = new Collection();

        $this->startDate = session()->get('startDate');
        $this->endDate = session()->get('endDate');
    }

    public function supplierDueSearch()
    {

        if ($this->startDate && $this->endDate) {

            $this->purchaseProducts = SupplierTransactionDetails::
                whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            session()->put('startDate', $this->startDate);
            session()->put('endDate', $this->endDate);
        }

    }

    public function searchReset()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->bonuses = [];
        session()->forget('startDate');
        session()->forget('endDate');
    }

    public function render()
    {
        if ($this->startDate && $this->endDate) {

        $raw_bonus_data = $this->purchaseProducts
            ->groupBy(function($transaction) {
                return \Carbon\Carbon::parse($transaction->date)->format('Y-m') . '-' . $transaction->supplier_id; // ✅ প্রথমে তারিখ এবং সাপ্লাইয়ার অনুযায়ী গ্রুপিং
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
                    'supplier_id' => $supplierId,
                    'month' => $month,
                    'weight' => $totalWeight,
                    'rate' => $bonusRate,
                    'bonusAmount' => $totalWeight * $bonusRate,
                ];
            });

            $supplier_ids = $raw_bonus_data->pluck('supplier_id')->unique()->toArray();


            $this->bonuses = $raw_bonus_data
                ->groupBy('supplier_id')
                ->map(function ($group) {
                    return [
                        'weight' => $group->sum('weight'),
                        'bonusAmount' => $group->sum('bonusAmount')
                    ];
                })
                ->toArray();
            $this->suppliers = Supplier::whereIn('id', $supplier_ids)->get();

            // dd($monthlyTotalBonus);
        }
        return view('livewire.bonus.monthly.all', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
