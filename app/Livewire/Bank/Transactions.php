<?php

namespace App\Livewire\Bank;

use App\Models\Bank;
use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage;
    public $queryString;

    public ?string $end_date = null;
    public ?string $start_date = null;
    public ?int $get_bank_id = null;
    public ?string $payment_method = null;
    public ?string $transaction_type = null;

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

    public function search(){
        $this->resetPage();
    }

    public function searchReset()
    {
        $this->start_date = null;
        $this->end_date = null;
        $this->queryString = null;
        $this->get_bank_id = null;
        $this->payment_method = null;
        $this->transaction_type = null;
    }


    public function render()
    {
        $query = Transaction::query()
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC');

        if($this->start_date && $this->end_date){
            $query->whereBetween('updated_at', [$this->start_date, $this->end_date]);
        }

        if($this->queryString){
            $query->where(function ($query) {
                $query->where('bank_name', 'LIKE', "%$this->queryString%")
                    ->orWhere('bank_branch_name', 'LIKE', "%$this->queryString%")
                    ->orWhere('bank_account_no', 'LIKE', "%$this->queryString%")
                    ->orWhere('payment_by', 'LIKE', "%$this->queryString%")
                    ->orWhere('remarks', 'LIKE', "%$this->queryString%");
            });
        }

        if($this->payment_method){
            $query->where('payment_by', $this->payment_method);
        }
        if($this->transaction_type){
            $query->where('type', $this->transaction_type);
        }
        if($this->get_bank_id){
            $query->where('bank_id', $this->get_bank_id);
        }


        if ($this->perPage === 'all') {
            $transactions = $query->get(); // Fetch all records
        } else {
            $transactions = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        $banks = Bank::get();

        return view('livewire.bank.transactions', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}

