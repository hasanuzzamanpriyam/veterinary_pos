<?php

namespace App\Livewire\Employee;

use App\Models\Employee;
use App\Models\EmployeeLedger;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmployeeLedgerEdit extends Component
{
    public $employee_info;
    public $employee_id;
    public $payment_method;
    public $remarks;
    public $amount;
    public $prev_balance;
    public $balance;
    public $ledger = null;

    public function mount($id = null)
    {
        if($id){
            $this->ledger = EmployeeLedger::where('id', $id)->first();
            $this->employee_info = $this->ledger->employee;
            $this->balance = $this->employee_info->balance;
            $this->prev_balance = $this->employee_info->balance + $this->ledger->amount;
            $this->payment_method = $this->ledger->payment_method;
            $this->remarks = $this->ledger->remarks;
            $this->amount = $this->ledger->amount;
        }

    }

    public function rules()
    {
        return [
            'employee_info'     => 'required',
            'payment_method'    => 'required',
            'amount'            => 'required|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'employee_info.required'    => 'Employee is required',
            'payment_method.required'   => 'Payment method is required',
            'amount.gt'                 => 'Amount is required'
        ];
    }

    public function dueCalculation($amount)
    {
        if($this->employee_info){
            $clean_number = preg_replace("/[^0-9.]/", "", $amount);
            $this->amount = $clean_number ? $clean_number : 0;
            $this->balance = $this->prev_balance - $this->amount;
        }
    }

    public function store()
    {
        $this->validate();

        DB::transaction(function () {

            // +- employee balance
            if ($this->employee_info->id){
                Employee::where('id', $this->employee_info->id)->increment('balance', $this->ledger->amount);
                Employee::where('id', $this->employee_info->id)->decrement('balance', $this->amount);
            }

            // increment employee balance
            EmployeeLedger::where('id', $this->ledger->id)->update([
                'payment_method' => $this->payment_method,
                'amount' => $this->amount,
                'remarks' => $this->remarks,
                'updated_at' => now(),
            ]);
        });
        $notification = array('msg' => 'Transaction added successfully', 'alert-type' => 'success');
        return redirect()->route('employee.payment.list')->with($notification);
    }

    public function render()
    {
        return view('livewire.employee.employee-ledger-edit', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
