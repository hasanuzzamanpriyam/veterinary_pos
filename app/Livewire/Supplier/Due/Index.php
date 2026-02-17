<?php

namespace App\Livewire\Supplier\Due;

use App\Models\SupplierLedger;
use App\Models\Supplier;
use Livewire\Component;

class Index extends Component
{
    public $due_supplier;
    public $get_supplier_id;
    public $last_due;
    public $date;

    // public function mount()
    // {
    //     // Set the initial date when the component is mounted
    //     $this->date = now()->toDateString();

    // }

    public function resetSupplier()
    {
        $this->reset('due_supplier');
        $this->reset('last_due');
        $this->reset('date');
        $this->reset('get_supplier_id');
    }




    public function supplierDueSearch()
    {

        if ($this->get_supplier_id && $this->date) {

            $this->due_supplier = SupplierLedger::where('supplier_id', $this->get_supplier_id)->where('date', $this->date)->get();
        } elseif ($this->get_supplier_id) {
            $this->last_due = SupplierLedger::where('supplier_id', $this->get_supplier_id)->orderBy('id', 'DESC')->first();
        }
    }


    public function render()
    {
        $suppliers = Supplier::get();
        return view('livewire.supplier.due.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
