<?php

namespace App\Livewire\Bank;

use App\Models\Bank;
use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BankList extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage;
    public $queryString;

    public function mount() {

        $this->perPage = $this->perPage ?? 10;
        $this->queryString = '';
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }
    public function updateQueryString()
    {
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function render()
    {
        $query = Bank::where('name', 'LIKE', "%$this->queryString%")
            ->orWhere('account_no', 'LIKE', "%$this->queryString%")
            ->orWhere('title', 'LIKE', "%$this->queryString%")
            ->orWhere('branch', 'LIKE', "%$this->queryString%")
            ->orWhere('ac_mode', 'LIKE', "%$this->queryString%")
            ->orWhere('code', 'LIKE', "%$this->queryString%")
            ->orderBy('name', 'asc');

        if ($this->perPage === 'all') {
            $banks = $query->get(); // Fetch all records
        } else {
            $banks = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        $ids = $banks->pluck('id')->toArray();
        $transactions = Transaction::select('bank_id')
        ->selectRaw("SUM(CASE WHEN type = 'deposit' OR type = 'opening' THEN amount ELSE 0 END) as total_deposit")
        ->selectRaw("SUM(CASE WHEN type = 'withdraw' THEN amount ELSE 0 END) as total_withdraw")
        ->whereIn('bank_id', $ids)
        ->groupBy('bank_id')->get();

        // dump($banks, $transactions);

        return view('livewire.bank.bank-list', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
