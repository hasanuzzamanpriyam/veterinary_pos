<?php

namespace App\Livewire\Bank;

use App\Models\Bank;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;

class TransactionCreate extends Component
{

    public $transaction_type = ['deposit', 'withdraw'];
    public $view;
    public $bank_id;
    public $bank_info;
    public $date;
    public $amount = 0;
    public $payment_by;
    public $payment_by_bank;
    public $remarks;
    public $balance = 0;

    public function mount($view)
    {
        if ( !in_array($view, $this->transaction_type) ) {
            abort(404);
        }

        $this->view = $view;
    }

    public function rules()
    {
        return [
            'bank_id' => 'required',
            'date' => 'required',
            'amount' => 'required|gt:0',
            'payment_by' => 'required',
            'payment_by_bank' => 'nullable|max:255',
            'remarks' => 'nullable|max:255',
        ];
    }

    public function messages()
    {
        return [
            'bank_id.required' => 'Please select a bank',
            'date.required' => 'Please select a date',
            'amount.required' => 'Please enter an amount',
            'payment_by.required' => 'Please select a payment method',
        ];
    }

    public function get_previous_balance($bank_id, $date){
        if ($bank_id && $date) {
            $f_date = date('Y-m-d', strtotime($date));
            $data = Transaction::where('bank_id', $bank_id)->where('date', '=', $f_date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();;
            if(!$data){
                $data = Transaction::where('bank_id', $bank_id)->where('date', '<', $f_date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
            }
            return $data->balance ?? 0;
        }
    }

    public function amoutCalculation($amount){
        $clean_number = preg_replace("/[^0-9.]/", "", $amount);
        $this->amount = $clean_number ? $clean_number : 0;
    }

    public function store()
    {
        $this->validate();
        Transaction::insertGetId([
            'bank_id' => $this->bank_id,
            'type' => $this->view,
            'bank_name' => $this->bank_info->name,
            'bank_branch_name' => $this->bank_info->branch,
            'bank_account_no' => $this->bank_info->account_no,
            'amount' => $this->amount,
            'payment_by' => $this->payment_by,
            'payment_by_bank' => $this->payment_by_bank,
            'remarks' => $this->remarks,
            'date' => date('Y-m-d', strtotime($this->date)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($this->view == 'deposit') {
            Bank::where('id', $this->bank_id)->increment('balance', $this->amount);
        }else{
            Bank::where('id', $this->bank_id)->decrement('balance', $this->amount);
        }

        $notification = array('msg' => 'Transaction added successfully', 'alert-type' => 'success');
        return redirect()->route('transaction.bank.statement', $this->bank_id)->with($notification);
    }


    public function handleChange( $name, $value )
    {
        if ($name == 'bank_id') {
            $this->bank_id = $value;
            $this->bank_info = Bank::where('id', $value)->first();
            $this->balance = $this->bank_info->balance ?? 0;
        }else{
            $this->$name = $value;
        }
    }

    public function render()
    {
        $banks = Bank::get();

        $this->dispatch('dataUpdated');
        return view('livewire.bank.transaction-create', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
