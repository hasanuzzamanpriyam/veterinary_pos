<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use App\Models\SupplierLedger;
use Livewire\Component;

class Checkout extends Component
{

    public $supplier;

    public function mount()
    {

        $this->supplier = session()->has('supplier_data') ? session()->get('supplier_data') : null;
    }

    public function submit()
    {
        // dd($this->supplier);
        if ($this->supplier) {
            $balance = $this->supplier['advance_payment'] ? -$this->supplier['advance_payment'] : $this->supplier['previous_due'];

            $photo_path = null;

            $formated_starting_date = $this->supplier['starting_date'] ? date('Y-m-d', strtotime($this->supplier['starting_date'])) : null;

            $supplier_id = Supplier::insertGetId([
                'company_name' => $this->supplier['company_name'],
                'owner_name' => $this->supplier['owner_name'],
                'officer_name' => $this->supplier['officer_name'],
                'address' => $this->supplier['address'],
                'phone' => $this->supplier['phone'],
                'mobile' => $this->supplier['mobile'],
                'email' => $this->supplier['email'],
                'ledger_page' => $this->supplier['ledger_page'],
                'condition' => $this->supplier['condition'],
                'dealer_code' => $this->supplier['dealer_code'],
                'dealer_area' => $this->supplier['dealer_area'],
                'security' => $this->supplier['security'],
                'credit_limit' => $this->supplier['credit_limit'],
                'balance' => $balance ? $balance : 0,
                'starting_date' => $formated_starting_date,
                'photo' => $photo_path,

            ]);

            if ($this->supplier['advance_payment'] || $this->supplier['previous_due']) {
                SupplierLedger::insert([
                    'supplier_id'       => $supplier_id,
                    'type'              => 'other',
                    'balance'           => $balance ? $balance : 0,
                    'payment'           => $this->supplier['advance_payment'] ? $this->supplier['advance_payment'] : null,
                    'payment_remarks'   => $this->supplier['advance_payment'] ? "Advance Payment" : "Previous Due",
                    'date'              => $formated_starting_date,
                    'u_id'              => 0,
                ]);
            }

            session()->forget('supplier_data');
            $alert = array('msg' => 'Supplier Successfully Inserted', 'alert-type' => 'success');
            return redirect()->route('supplier.index')->with($alert);
        }
    }

    public function render()
    {

        return view('livewire.supplier.checkout')
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
