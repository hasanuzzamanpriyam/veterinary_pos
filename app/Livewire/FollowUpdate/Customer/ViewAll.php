<?php

namespace App\Livewire\FollowUpdate\Customer;

use App\Models\customer;
use Illuminate\Http\Request;
use Livewire\Component;

class ViewAll extends Component
{

    public $customer_id;
    public $preloadedCustomers;
    public $start_date;
    public $start_date_formatted;
    public $end_date;
    public $end_date_formatted;
    public $invoices;
    public $customer_label = '';

    public function mount(Request $request)
    {
        $this->customer_id = $request->query('id');
        $customer = customer::where('id', $this->customer_id)->first();
        if($customer){
            $this->customer_label = $customer->name . ' - ' . $customer->address . ' - ' . $customer->mobile;
        }
        $this->start_date = $request->query('start_date');
        $this->end_date = $request->query('end_date');
        $this->start_date_formatted = date('Y-m-d', strtotime($this->start_date));
        $this->end_date_formatted = date('Y-m-d', strtotime($this->end_date));
    }


    public function render()
    {
        if($this->customer_id){
            if($this->start_date && $this->end_date){
                $this->invoices = \App\Models\CustomerFollowUpdate::where('customer_id', $this->customer_id)
                ->whereBetween('next_date', [$this->start_date_formatted, $this->end_date_formatted])
                ->whereNull('payment_date')
                ->orderBy('next_date','ASC')->get();
            }else{
                $this->invoices = \App\Models\CustomerFollowUpdate::where('customer_id', $this->customer_id)
                ->whereNull('payment_date')
                ->orderBy('next_date','ASC')->get();
            }
        }else{
            if($this->start_date && $this->end_date){
                $this->invoices = \App\Models\CustomerFollowUpdate::whereBetween('next_date', [$this->start_date_formatted, $this->end_date_formatted])
                ->whereNull('payment_date')
                ->orderBy('next_date','ASC')->get();
            }else{
                $this->invoices = \App\Models\CustomerFollowUpdate::orderBy('next_date','ASC')
                ->whereNull('payment_date')
                ->get();
            }
        }
        // dd($this->invoices);

        return view('livewire.follow-update.customer.view-all', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
