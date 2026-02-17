<?php

namespace App\Livewire\Reports;

use App\Models\CustomerLedger;
use App\Models\Expense;
use App\Models\SupplierLedger;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class DailySummary extends Component
{

    public $selectedDate;

    public Collection $customerLedgers;
    public Collection $saleProducts;
    public Collection $supplierLedgers;
    public Collection $purchaseProducts;
    public Collection $expenses;
    public $overviewSummary = [];

    public function __construct()
    {
        $this->customerLedgers = new Collection();
        $this->saleProducts = new Collection();
        $this->supplierLedgers = new Collection();
        $this->purchaseProducts = new Collection();
        $this->expenses = new Collection();
    }

    #[On('dateUpdated')]
    public function updateDate($date)
    {
        $this->selectedDate = $date;
    }

    public function render()
    {
        if ($this->selectedDate) {
            $this->customerLedgers = CustomerLedger::with([
                'transactions.product',
                'customer'
                ])
                ->whereDate('date', $this->selectedDate)
                ->where(function ($query) {
                    $query->whereIn('type', ['collection', 'sale']);
                })

                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $this->supplierLedgers = SupplierLedger::with([
                'transactions.product',
                'supplier'
                ])
                ->whereDate('date', $this->selectedDate)
                ->where(function ($query) {
                    $query->whereIn('type', ['payment', 'purchase']);
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $this->expenses = Expense::with([
                'employee'
                ])
                ->whereDate('created_at', $this->selectedDate)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $this->saleProducts = new Collection($this->customerLedgers->flatMap->transactions);
            $this->purchaseProducts = new Collection($this->supplierLedgers->flatMap->transactions);
            $totalSaleQuantities = $this->saleProducts
                ->groupBy(fn($transaction) => $transaction->product->type) // টাইপ অনুযায়ী গ্রুপিং
                ->mapWithKeys(function ($group, $type) {
                    $totalQuantity = $group->sum(fn($transaction) => $transaction->quantity - $transaction->discount_qty);
                    return [$type => $totalQuantity];
                });
            $totalPurchaseQuantities = $this->purchaseProducts
                ->groupBy(fn($transaction) => $transaction->product->type) // টাইপ অনুযায়ী গ্রুপিং
                ->mapWithKeys(function ($group, $type) {
                    $totalQuantity = $group->sum(fn($transaction) => $transaction->quantity - $transaction->discount_qty);
                    return [$type => $totalQuantity];
                });

            $totalCollection = $this->customerLedgers->sum('payment');
            $totalSale = $this->customerLedgers->sum('total_price');
            $totalPayment = $this->supplierLedgers->sum('payment');
            $totalPurchase = $this->supplierLedgers->sum('total_price');
            $totalExpense = $this->expenses->sum(fn($expense) => $expense->amount + $expense->other_charge);
            $this->overviewSummary = [
                'totalCollection' => $totalCollection,
                'totalSale' => [
                    'total' => $totalSale,
                    'quantity' => $totalSaleQuantities
                ],
                'totalPayment' => $totalPayment,
                'totalPurchase' => [
                    'total' => $totalPurchase,
                    'quantity' => $totalPurchaseQuantities
                ],
                'totalExpense' => $totalExpense
            ];
        }

        return view('livewire.reports.daily-summary', [
            'customerLedgers' => $this->customerLedgers,
            'saleProducts' => $this->saleProducts,
            'supplierLedgers' => $this->supplierLedgers,
            'purchaseProducts' => $this->purchaseProducts,
            'expenses' => $this->expenses,
            'overviewSummary' => $this->overviewSummary
        ])
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
