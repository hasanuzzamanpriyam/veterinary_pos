<?php

namespace App\Livewire\Customer\Due;

use App\Models\customer;
use App\Models\CustomerLedger;
use Livewire\Component;

class Index extends Component
{
    public $due_customer;
    public $last_due;
    public $get_customer_id;
    public $date;

    // public function mount()
    // {
    //     // Set the initial date when the component is mounted
    //     $this->date = now()->toDateString();

    // }

    public function resetCustomer()
    {
        $this->reset('due_customer');
        $this->reset('last_due');
        $this->reset('date');
        $this->reset('get_customer_id');
    }




    public function customerDueSearch()
    {

        if ($this->get_customer_id && $this->date) {

            $this->due_customer = CustomerLedger::where('customer_id', $this->get_customer_id)->where('date', $this->date)->get();
        } elseif ($this->get_customer_id) {
            $this->last_due = CustomerLedger::where('customer_id', $this->get_customer_id)->orderBy('id', 'DESC')->first();
        }
    }


    public function render()
    {
        $customers = customer::get();
        return view('livewire.customer.due.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
