<?php

namespace App\Livewire\Bonus\Cashoffer;

use App\Models\CashOffer;
use Livewire\Component;

class Edit extends Component
{
    public $offer;
    public $date;
    public $amount;
    public $description;

    public function mount($id)
    {
        if(!$id) return redirect()->route('cash.offer.list');

        $this->offer = CashOffer::find($id)->with('supplier')->first();
        $this->date = date('d-m-Y', strtotime($this->offer->date));
        $this->amount = $this->offer->amount;
        $this->description = $this->offer->description;

    }

    public function rules()
    {
        return [
            'date' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ];
    }

    public function update()
    {
        $this->validate();
        $this->offer->update([
            'date' => date('Y-m-d', strtotime($this->date)),
            'amount' => $this->amount,
            'description' => $this->description,
        ]);

        $notification = array('msg' => 'Cash offer updated successfully', 'alert-type' => 'success');
        return redirect()->route('cash.offer.list')->with($notification);
    }

    public function render()
    {
        return view('livewire.bonus.cashoffer.edit', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
