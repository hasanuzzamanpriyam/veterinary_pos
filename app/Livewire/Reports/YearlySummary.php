<?php

namespace App\Livewire\Reports;

use App\Models\CustomerLedger;
use App\Models\Expense;
use App\Models\SupplierLedger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class YearlySummary extends Component
{
    public $startDate;
    public $endDate;
    public $all_months = [];
    public $all_data = [];

    public Collection $customerLedgers;
    public Collection $saleProducts;
    public Collection $supplierLedgers;
    public Collection $purchaseProducts;
    public Collection $expenses;

    public function __construct()
    {
        $this->customerLedgers = new Collection();
        $this->saleProducts = new Collection();
        $this->supplierLedgers = new Collection();
        $this->purchaseProducts = new Collection();
        $this->expenses = new Collection();
    }

    #[On('yearUpdated')]
    public function updateDate($date)
    {
        $this->startDate = Carbon::create(date('Y-m-d', strtotime($date['startdate'])));// First day of the month
        $this->endDate = Carbon::create(date('Y-m-d', strtotime($date['enddate'])));// First day of the month
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
            $q = CustomerLedger::with([
                    'transactions.product',
                ])
                ->whereBetween('date', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->where(function ($query) {
                    $query->whereIn('type', ['collection', 'sale']);
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc');
                $this->customerLedgers = $q
                ->get();

            $this->supplierLedgers = SupplierLedger::with([
                'transactions.product',
                ])
                ->whereBetween('date', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->where(function ($query) {
                    $query->whereIn('type', ['payment', 'purchase']);
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $this->expenses = Expense::
                whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ])
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $this->saleProducts = new Collection($this->customerLedgers->flatMap->transactions);
            $this->purchaseProducts = new Collection($this->supplierLedgers->flatMap->transactions);


            $totalSale = $this->saleProducts
                ->groupBy(function($transaction) {
                    return \Carbon\Carbon::parse($transaction->date)->format('Y-m'); // ✅ প্রথমে তারিখ অনুযায়ী গ্রুপিং
                })
                ->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->date)),
                        'sale_quantity' => $dateGroup->groupBy(function ($transaction) {
                            return $transaction->product->type;
                            })->mapWithKeys(function ($group, $type) {
                                $totalQuantity = $group->sum(fn($transaction) => $transaction->quantity - $transaction->discount_qty);
                                return [$type => $totalQuantity];
                            }),
                        'sale_amount' => $dateGroup->sum('total_price')
                    ];
                })->toArray();

            $totalCollection = $this->customerLedgers
                ->groupBy(function($ledger) {
                    return \Carbon\Carbon::parse($ledger->date)->format('Y-m');
                })->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->date)),
                        'collection' => $dateGroup->sum('payment')
                    ];
                })->toArray();

            $totalPurchase = $this->purchaseProducts
                ->groupBy(function($transaction) {
                    return \Carbon\Carbon::parse($transaction->date)->format('Y-m'); // ✅ প্রথমে তারিখ অনুযায়ী গ্রুপিং
                })
                ->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->date)),
                        'purchase_quantity' => $dateGroup->groupBy(function ($transaction) {
                            return $transaction->product->type;
                            })->mapWithKeys(function ($group, $type) {
                                $totalQuantity = $group->sum(fn($transaction) => $transaction->quantity - $transaction->discount_qty);
                                return [$type => $totalQuantity];
                            }),
                        'purchase_amount' => $dateGroup->sum('total_price')
                    ];
                })->toArray();

            $totalPayment = $this->supplierLedgers
                ->groupBy(function($ledger) {
                    return \Carbon\Carbon::parse($ledger->date)->format('Y-m');
                })->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->date)),
                        'payment' => $dateGroup->sum('payment')
                    ];
                });

            $totalExpense = $this->expenses
                ->groupBy(function($expense) {
                    return \Carbon\Carbon::parse($expense->created_at)->format('Y-m');
                })->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m', strtotime($dateGroup->first()->created_at)),
                        'expense' => $dateGroup->sum(fn($expense) => $expense->amount + $expense->other_charge)
                    ];
                });

            $this->all_data = array_map(function ($item) use ($totalSale, $totalCollection, $totalPurchase, $totalPayment, $totalExpense) {
                    return array_merge($totalSale[$item] ?? [], $totalCollection[$item] ?? [], $totalPurchase[$item] ?? [], $totalPayment[$item] ?? [], $totalExpense[$item] ?? []);
                }, $this->all_months);
        }


        return view('livewire.reports.yearly-summary', [
            'summary' => $this->all_data
        ])
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
