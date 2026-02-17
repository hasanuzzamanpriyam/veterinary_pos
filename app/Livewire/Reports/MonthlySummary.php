<?php

namespace App\Livewire\Reports;

use App\Models\CustomerLedger;
use App\Models\Expense;
use App\Models\SupplierLedger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class MonthlySummary extends Component
{
    public $selectedDate;
    public $all_dates = [];
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

    #[On('monthUpdated')]
    public function updateDate($date)
    {
        $this->selectedDate = $date;

        $startDate = Carbon::create(date('Y', strtotime($this->selectedDate)), date('m', strtotime($this->selectedDate)), 1);  // First day of the month
        $endDate = $startDate->copy()->endOfMonth();
        $this->all_dates = [];
        while ($startDate <= $endDate) {
            $this->all_dates[] = $startDate->format('Y-m-d'); // Add date to array
            $startDate->addDay();  // Move to the next day
        }
    }

    public function render()
    {
        if ($this->selectedDate) {
            $this->customerLedgers = CustomerLedger::with([
                    'transactions.product',
                ])
                ->whereMonth('date', date('m', strtotime($this->selectedDate))) // $this->selectedMonth = মাসের সংখ্যা (1-12)
                ->whereYear('date', date('Y', strtotime($this->selectedDate)))   // $this->selectedYear = বছরের সংখ্যা (যেমন: 2025)
                ->where(function ($query) {
                    $query->whereIn('type', ['collection', 'sale']);
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $this->supplierLedgers = SupplierLedger::with([
                'transactions.product',
                ])
                ->whereMonth('date', date('m', strtotime($this->selectedDate))) // $this->selectedMonth = মাসের সংখ্যা (1-12)
                ->whereYear('date', date('Y', strtotime($this->selectedDate)))   // $this->selectedYear = বছরের সংখ্যা (যেমন: 2025)
                ->where(function ($query) {
                    $query->whereIn('type', ['payment', 'purchase']);
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $this->expenses = Expense::whereMonth('created_at', date('m', strtotime($this->selectedDate))) // $this->selectedMonth = মাসের সংখ্যা (1-12)
                ->whereYear('created_at', date('Y', strtotime($this->selectedDate)))   // $this->selectedYear = বছরের সংখ্যা (যেমন: 2025)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();




            $this->saleProducts = new Collection($this->customerLedgers->flatMap->transactions);
            $this->purchaseProducts = new Collection($this->supplierLedgers->flatMap->transactions);

            $totalSale = $this->saleProducts
                ->groupBy(function($transaction) {
                    return \Carbon\Carbon::parse($transaction->date)->format('Y-m-d'); // ✅ প্রথমে তারিখ অনুযায়ী গ্রুপিং
                })
                ->map(function ($dateGroup) {
                    return [
                        'date' => $dateGroup->first()->date,
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
                    return \Carbon\Carbon::parse($ledger->date)->format('Y-m-d');
                })->map(function ($dateGroup) {
                    return [
                        'date' => $dateGroup->first()->date,
                        'collection' => $dateGroup->sum('payment')
                    ];
                })->toArray();

            $totalPurchase = $this->purchaseProducts
                ->groupBy(function($transaction) {
                    return \Carbon\Carbon::parse($transaction->date)->format('Y-m-d'); // ✅ প্রথমে তারিখ অনুযায়ী গ্রুপিং
                })
                ->map(function ($dateGroup) {
                    return [
                        'date' => $dateGroup->first()->date,
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
                    return \Carbon\Carbon::parse($ledger->date)->format('Y-m-d');
                })->map(function ($dateGroup) {
                    return [
                        'date' => $dateGroup->first()->date,
                        'payment' => $dateGroup->sum('payment')
                    ];
                });

            $totalExpense = $this->expenses
                ->groupBy(function($expense) {
                    return \Carbon\Carbon::parse($expense->created_at)->format('Y-m-d');
                })->map(function ($dateGroup) {
                    return [
                        'date' => date('Y-m-d', strtotime($dateGroup->first()->created_at)),
                        'expense' => $dateGroup->sum(fn($expense) => $expense->amount + $expense->other_charge)
                    ];
                });

            $this->all_data = array_map(function ($item) use ($totalSale, $totalCollection, $totalPurchase, $totalPayment, $totalExpense) {
                return array_merge($totalSale[$item] ?? [], $totalCollection[$item] ?? [], $totalPurchase[$item] ?? [], $totalPayment[$item] ?? [], $totalExpense[$item] ?? []);
            }, $this->all_dates);
        }
        return view('livewire.reports.monthly-summary', [
            'summary' => $this->all_data
        ])
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
