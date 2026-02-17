<?php

namespace App\Livewire\FollowUpdate\Supplier;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Livewire\Component;

class ViewAll extends Component
{
    public $supplier_id;
    public $preloadedSuppliers;
    public $start_date;
    public $start_date_formatted;
    public $end_date;
    public $end_date_formatted;
    public $invoices;
    public $supplier_label = '';

    public function mount(Request $request)
    {
        $this->supplier_id = $request->query('id');
        $supplier = Supplier::where('id', $this->supplier_id)->first();
        if($supplier){
            $this->supplier_label = $supplier->company_name . ' - ' . $supplier->address . ' - ' . $supplier->mobile;
        }
        $this->start_date = $request->query('start_date');
        $this->end_date = $request->query('end_date');
        $this->start_date_formatted = date('Y-m-d', strtotime($this->start_date));
        $this->end_date_formatted = date('Y-m-d', strtotime($this->end_date));
    }

    public function render()
    {
        if($this->supplier_id){
            if($this->start_date && $this->end_date){
                $this->invoices = \App\Models\SupplierFollowUpdate::where('supplier_id', $this->supplier_id)
                ->whereBetween('next_date', [$this->start_date_formatted, $this->end_date_formatted])
                ->whereNull('payment_date')
                ->orderBy('next_date','ASC')->get();
            }else{
                $this->invoices = \App\Models\SupplierFollowUpdate::where('supplier_id', $this->supplier_id)
                ->whereNull('payment_date')
                ->orderBy('next_date','ASC')->get();
            }
        }else{
            if($this->start_date && $this->end_date){
                $this->invoices = \App\Models\SupplierFollowUpdate::whereBetween('next_date', [$this->start_date_formatted, $this->end_date_formatted])
                ->whereNull('payment_date')
                ->orderBy('next_date','ASC')->get();
            }else{
                $this->invoices = \App\Models\SupplierFollowUpdate::orderBy('next_date','ASC')
                ->whereNull('payment_date')
                ->get();
            }
        }

        return view('livewire.follow-update.supplier.view-all', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
