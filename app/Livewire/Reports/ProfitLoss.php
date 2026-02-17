<?php

namespace App\Livewire\Reports;

use App\Models\CustomerTransactionDetails;
use App\Models\Expense;
use App\Models\MonthlyBonusCount;
use App\Models\SupplierTransactionDetails;
use App\Models\ProductStore;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ProfitLoss extends Component
{
    public $startDate;
    public $endDate;
    public $all_months = [];
    public $all_data = [];

    public Collection $saleProducts;
    public Collection $purchaseProducts;
    public Collection $expenses;

    public function __construct()
    {
        $this->saleProducts = new Collection();
        $this->purchaseProducts = new Collection();
        $this->expenses = new Collection();
    }

    #[On('yearUpdated')]
    public function updateDate($date)
    {
        $this->startDate = Carbon::create(date('Y-m-d', strtotime($date['startdate']))); // First day of the month
        $this->endDate = Carbon::create(date('Y-m-d', strtotime($date['enddate']))); // First day of the month
        $this->all_months = [];

        $temp_start_date = $this->startDate->copy();
        while ($temp_start_date <= $this->endDate) {
            $this->all_months[] = $temp_start_date->format('Y-m'); // Add date to array
            $temp_start_date->addMonth();  // Move to the next day
        }
    }

    public function render()
    {
        if ($this->startDate && $this->endDate) {
            $this->saleProducts = CustomerTransactionDetails::whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
            $this->purchaseProducts = SupplierTransactionDetails::whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
            $this->expenses = Expense::whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $totalSale = $this->saleProducts
                ->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->date)->format('Y-m'); // ✅ প্রথমে তারিখ অনুযায়ী গ্রুপিং
                })
                ->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->date)),
                        'sale' => $dateGroup->sum('total_price')
                    ];
                })->toArray();

            $totalPurchase = $this->purchaseProducts
                ->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->date)->format('Y-m'); // ✅ প্রথমে তারিখ অনুযায়ী গ্রুপিং
                })
                ->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->date)),
                        'purchase' => $dateGroup->sum('total_price')
                    ];
                })->toArray();

            // প্রথমে তারিখ এবং সাপ্লাইয়ার অনুযায়ী গ্রুপ তৈরী করা হয়েছে
            $monthlySupplierBonus = $this->purchaseProducts
                ->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->date)->format('Y-m') . '-' . $transaction->supplier_id; // ✅ প্রথমে তারিখ এবং সাপ্লাইয়ার অনুযায়ী গ্রুপিং
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
                        'month' => $month,
                        'bonusAmount' => $totalWeight * $bonusRate,
                    ];
                });

            // তারপর মাস অনুযায়ী গ্রুপ তৈরী করে মোট বোনাস বের করা হয়েছে
            $monthlyTotalBonus = $monthlySupplierBonus
                ->groupBy('month')
                ->map(function ($group) {
                    return [
                        'date' => $group->first()->month,
                        'bonusAmount' => $group->sum('bonusAmount')
                    ];
                })
                ->toArray();

            $totalExpense = $this->expenses
                ->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->created_at)->format('Y-m'); // ✅ প্রথমে তারিখ অনুযায়ী গ্রুপিং
                })
                ->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->created_at)),
                        'expense' => $dateGroup->sum('amount')
                    ];
                })->toArray();

            $this->all_data = array_map(function ($item) use ($totalSale, $totalPurchase, $totalExpense, $monthlyTotalBonus) {
                return array_merge($totalSale[$item] ?? [], $totalPurchase[$item] ?? [], $totalExpense[$item] ?? [], $monthlyTotalBonus[$item] ?? []);
            }, $this->all_months);
        }

        // Calculate stocked product purchase price from ProductStore (uses purchase_price field)
        $stockedPurchase = ProductStore::selectRaw('SUM(COALESCE(product_quantity, 0) * COALESCE(purchase_price, 0)) as total')
            ->where('product_quantity', '>', 0)
            ->first()?->total ?? 0;

        // Only calculate today's purchased-rate total when the report end date is today.
        // This adds the value of all stocked products to the net profit/loss.
        $isEndDateToday = isset($this->endDate) && Carbon::parse($this->endDate)->isSameDay(Carbon::today());

        if ($isEndDateToday) {
            // Calculate purchased-rate total using `products.purchase_rate` field multiplied by current stock quantity.
            // Only includes products with stock (product_quantity > 0).
            // This represents the total value of all products currently in stock at their purchase rate.
            if (Schema::hasTable('products') && Schema::hasColumn('products', 'purchase_rate')) {
                $purchasedRateTotal = ProductStore::join('products', 'product_stores.product_id', '=', 'products.id')
                    ->where('product_quantity', '>', 0)
                    ->selectRaw('SUM(COALESCE(product_quantity, 0) * COALESCE(products.purchase_rate, 0)) as total')
                    ->first()?->total ?? 0;
            } else {
                // Fallback: sum purchase_price from ProductStore multiplied by quantity
                $purchasedRateTotal = ProductStore::selectRaw('SUM(COALESCE(product_quantity, 0) * COALESCE(purchase_price, 0)) as total')
                    ->where('product_quantity', '>', 0)
                    ->first()?->total ?? 0;
            }
        } else {
            // For historical dates, we don't add stocked product value to the calculation
            $purchasedRateTotal = 0;
        }

        // determine if there are any stocked products
        $hasStockedProducts = $stockedPurchase > 0;

        return view('livewire.reports.profit-loss', [
            'summary' => $this->all_data,
            'stockedPurchase' => $stockedPurchase,
            'endDate' => $this->endDate,
            'hasStockedProducts' => $hasStockedProducts,
            'purchased_rate_total' => $purchasedRateTotal,
        ])
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
