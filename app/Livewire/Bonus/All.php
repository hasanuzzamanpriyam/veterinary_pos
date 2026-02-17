<?php

namespace App\Livewire\Bonus;

use App\Models\CashOffer;
use App\Models\MonthlyBonusCount;
use App\Models\Supplier;
use App\Models\SupplierBonus;
use App\Models\SupplierTransactionDetails;
use App\Models\YearlyBonusCount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Concerns\ToArray;

class All extends Component
{
    public $startDate;
    public $endDate;
    public $all_months = [];
    public $all_data = [];
    public Collection $purchaseProducts;
    public $suppliers;
    public $bonuses = [];
    public Collection $cashOffers;

    public function mount(){
        $this->purchaseProducts = new Collection();
        $this->cashOffers = new Collection();

        $this->startDate = session()->get('totalBonusStartDate');
        $this->endDate = session()->get('totalBonusEndDate');
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
            $this->cashOffers = CashOffer::with('supplier')
                ->whereBetween('date', [
                    Carbon::parse($this->startDate)->format('Y-m-d'),
                    Carbon::parse($this->endDate)->format('Y-m-d')
                ])->get();

            session()->put('totalBonusStartDate', $this->startDate);
            session()->put('totalBonusEndDate', $this->endDate);
        }

    }

    public function searchReset()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->bonuses = [];
        session()->forget('totalBonusStartDate');
        session()->forget('totalBonusEndDate');
    }

    public function render()
    {
        if ($this->startDate && $this->endDate) {

            $all_suppliers = SupplierBonus::with('supplier')->get();

            // Now get the sum of the bonusAmount for each supplier for each month
            $monthly_bonus_data = $this->purchaseProducts
                ->groupBy(function($transaction) {
                    return Carbon::parse($transaction->date)->format('Y-m') . '-' . $transaction->supplier_id;
                })
                ->map(function ($group) {
                    $first = $group->first();
                    $month = Carbon::parse($first->date)->format('Y-m');
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
                    $bonusRate = (float) $bonusRate;

                    return (object)[
                        'supplier_id' => $supplierId,
                        'month' => $month,
                        'weight' => $totalWeight,
                        'rate' => $bonusRate,
                        'bonusAmount' => $totalWeight * $bonusRate,
                    ];
                });

            // Now get the sum of the bonusAmount for each supplier (monthly total)
            $total_bonus_per_supplier = $monthly_bonus_data
                ->groupBy('supplier_id')
                ->map(function ($group) {
                    return (object)[
                        'supplier_id' => $group->first()->supplier_id,
                        'bonusAmount' => $group->sum('bonusAmount'),
                        'monthlyBreakdown' => $group->toArray(), // Keep monthly details
                    ];
                });


            $yearly_bonus_data = $this->purchaseProducts
                ->groupBy(function($transaction) {
                    return $transaction->supplier_id; // ✅ প্রথমে সাপ্লাইয়ার অনুযায়ী গ্রুপিং
                })
                ->map(function ($group) {
                    $first = $group->first();
                    $supplierId = $first->supplier_id;

                    // total weight after calculating net quantity
                    $totalWeight = $group->sum(function ($transaction) {
                        $netQty = $transaction->quantity - $transaction->discount_qty - $transaction->return_qty;
                        return ($netQty * $transaction->weight) / 1000;
                    });


                    // fetch bonus rate from DB
                    $bonusRate = YearlyBonusCount::where('supplier_id', $supplierId)
                        ->where('start', '<', $totalWeight)
                        ->where('end', '>=', $totalWeight)
                        ->value('rate') ?? 0;
                    $bonusRate = (float) $bonusRate;

                    return (object)[
                        'supplier_id' => $supplierId,
                        'weight' => $totalWeight,
                        'rate' => $bonusRate,
                        'bonusAmount' => $totalWeight * $bonusRate,
                    ];
                });

            $cashOfferBonus = $this->cashOffers
                ->groupBy('supplier_id')
                ->map(function ($group) {
                    return (object)[
                        'supplier_id' => $group->first()->supplier_id,
                        'bonusAmount' => $group->sum('amount'),
                    ];
                });

            $this->bonuses = $all_suppliers
                ->groupBy('supplier_id')
                ->map(function ($group) use ($total_bonus_per_supplier, $yearly_bonus_data, $cashOfferBonus) {
                    return [
                        'monthly' => $total_bonus_per_supplier->where('supplier_id', $group->first()->supplier_id)->first(),
                        'yearly' => $yearly_bonus_data->where('supplier_id', $group->first()->supplier_id)->first(),
                        'cashOffer' => $cashOfferBonus->where('supplier_id', $group->first()->supplier_id)->first(),
                    ];
                })
                ->toArray();

        }
        return view('livewire.bonus.all', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');

    }
}
