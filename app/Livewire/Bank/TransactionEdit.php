<?php

namespace App\Livewire\Bank;

use App\Models\Bank;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;

class TransactionEdit extends Component
{

    public $transaction_type = ['deposit', 'withdraw'];
    public $view;
    public $bank_id;
    public $bank_info;
    public $transaction;
    public $date;
    public $amount = 0;
    public $balance = 0;
    public $payment_by;
    public $payment_by_bank;
    public $remarks;

    public function mount($view, $id)
    {
        if ( !in_array($view, $this->transaction_type) ) {
            abort(404);
        }

        $this->view = $view;
        if ($id){
            $this->transaction = Transaction::where('id', $id)->first();
            $this->bank_id = $this->transaction->bank_id;
            $this->bank_info = Bank::where('id', $this->transaction->bank_id)->first();
            $this->date = date('d-m-Y', strtotime($this->transaction->date));
            $this->amount = $this->transaction->amount;
            $this->balance = $this->bank_info->balance;
            $this->payment_by = $this->transaction->payment_by;
            $this->payment_by_bank = $this->transaction->payment_by_bank;
            $this->remarks = $this->transaction->remarks;
        }
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
        $modified_amount = $this->amount - $this->transaction->amount;
        Transaction::where('id', $this->transaction->id)->update([
            'payment_by' => $this->payment_by,
            'payment_by_bank' => $this->payment_by_bank,
            'remarks' => $this->remarks,
            'amount' => $this->amount,
            'date' => date('Y-m-d', strtotime($this->date)),
            'updated_at' => now(),
        ]);

        if ($this->view == 'deposit') {
            Bank::where('id', $this->bank_id)->increment('balance', $modified_amount);
        }else{
            Bank::where('id', $this->bank_id)->decrement('balance', $modified_amount);
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
        $prev_balance = $this->view == 'deposit' ? $this->bank_info->balance - $this->transaction->amount : $this->bank_info->balance + $this->transaction->amount;
        $total = 0;
        if(is_numeric($this->amount)){
            $total = $this->view == 'deposit' ? $prev_balance + $this->amount : $prev_balance - $this->amount;
        }
        return view('livewire.bank.transaction-edit', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
