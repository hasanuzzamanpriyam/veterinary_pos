<?php

namespace App\Livewire\Bank;

use App\Models\Bank;
use App\Models\Transaction;
use Illuminate\Http\Request;
// use Illuminate\Http\Client\Request;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Statement extends Component
{
    use WithPagination;
    #[Url(as: 'perpage')]
    public $perPage;

    public $id;
    public $end_date = null;
    public $start_date = null;
    public $payment_method = null;
    public $transaction_type = null;
    public $view = null;

    public function mount(Request $request, $id){
        $queryParams = $request->query->all();
        $this->id = $id;
        $this->perPage = $this->perPage ?? 10;
        if(isset($queryParams['view'])){
            $this->view = $queryParams['view'];
            $this->transaction_type = $queryParams['view'];
        }
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function search(){
        $this->resetPage();
    }

    public function searchReset()
    {
        $this->start_date = null;
        $this->end_date = null;
        $this->payment_method = null;
        $this->transaction_type = null;
        $this->perPage = 10;
    }

    public function render()
    {
        $bank = Bank::where('id', $this->id)->first();
        $query = Transaction::query()
            ->where('bank_id', $this->id)
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC');

        if($this->start_date && $this->end_date){
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        }

        if($this->payment_method){
            $query->where('payment_by', $this->payment_method);
        }
        if($this->transaction_type){
            $query->where('type', $this->transaction_type);
        }

        if ($this->perPage === 'all') {
            $transactions = $query->get(); // Fetch all records
        } else {
            $transactions = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        return view('livewire.bank.statement', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
