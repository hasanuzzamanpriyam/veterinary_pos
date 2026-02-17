<?php

namespace App\Livewire\Bonus\Cashoffer;

use App\Models\CashOffer;
use App\Models\Supplier;
use App\Models\SupplierBonus;
use Livewire\Component;

class Create extends Component
{
    public $supplier_id;
    public $supplier_name;
    public $address;
    public $mobile;
    public $date;
    public $description;
    public $amount;

    public function searchSupplier($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        $this->supplier_id =  $supplier->id;
        $this->supplier_name =  $supplier->company_name;
        $this->address =  $supplier->address;
        $this->mobile =  $supplier->mobile;
    }

    public function updateAmount($amount)
    {

        // dump($amount);
        if ($amount){
            $this->amount = $amount;
        }

    }

    public function store()
    {
        // dump($this->amount, $this->supplier_id, $this->date, $this->description);
        if($this->supplier_id && $this->amount && $this->date){
            $cashoffer = new CashOffer();
            $cashoffer->supplier_id = $this->supplier_id;
            $cashoffer->amount = $this->amount;
            $cashoffer->date = date('Y-m-d', strtotime($this->date));
            $cashoffer->description = $this->description ?? '';
            $cashoffer->save();

            SupplierBonus::updateOrCreate(
                ['supplier_id' => $this->supplier_id],
                [
                    'cash_offer' => true,
                ]
            );

            $notification = array('msg' => 'Cash offer added successfully', 'alert-type' => 'success');
            return redirect()->route('cash.offer.list')->with($notification);
        }
    }

    public function render()
    {
        $suppliers = Supplier::all();

        return view('livewire.bonus.cashoffer.create', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
