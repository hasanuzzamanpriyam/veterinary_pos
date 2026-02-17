<?php

namespace App\Livewire\BonusCount\Monthly;

use App\Models\MonthlyBonusCount;
use App\Models\Supplier;
use App\Models\SupplierBonus;
use Livewire\Component;

class Index extends Component
{
    public $supplier_id;
    public $supplier_info;
    public $next_counter = 1;
    public $monthly_bonus = [];


    public function mount() {
        $this->monthly_bonus[] = ['start' => '', 'end' => '', 'rate' => ''];
    }

    public function rules()
    {
        return
        [
            'supplier_id' => 'required',
            'monthly_bonus.*.from' => ['required', 'numeric'],
            'monthly_bonus.*.to' => ['required', 'numeric'],
            'monthly_bonus.*.rate' => ['required', 'numeric'],
        ];
    }

    public function messages(){

        return
        [
            'supplier_id.required' => 'Please Select Supplier',
            'monthly_bonus.*.from.required' => 'Please Enter From',
            'monthly_bonus.*.from.numeric' => 'Please Enter Valid From',
            'monthly_bonus.*.to.required' => 'Please Enter To',
            'monthly_bonus.*.to.numeric' => 'Please Enter Valid To',
            'monthly_bonus.*.rate.required' => 'Please Enter Rate',
            'monthly_bonus.*.rate.numeric' => 'Please Enter Valid Rate',
        ];
    }


    public function addMoreRow(){
        $this->monthly_bonus[] = ['start' => '', 'end' => '', 'rate' => ''];
    }

    public function deleteARow($index){
        unset($this->monthly_bonus[$index]);
    }

    public function getSupplier($id)
    {
        $this->supplier_id = $id;
        $this->supplier_info = Supplier::where('id',$id)->first();
    }

    public function store()
    {
        $data = $this->validate();

        if (isset($data['monthly_bonus'])) {

            if( count($data['monthly_bonus']) == 0){

                $notification = array('msg' => 'Please Add Bonus Count', 'alert-type' => 'error');
                return redirect()->route('bonus.create')->with($notification);
            } else {
                SupplierBonus::updateOrCreate(
                    ['supplier_id' => $this->supplier_id],
                    [
                        'monthly' => true,
                    ]
                );

                foreach ($data['monthly_bonus'] as $key => $value) {
                    MonthlyBonusCount::insert([
                        'supplier_id'=> $this->supplier_id,
                        'start' => $value['from'],
                        'end' => $value['to'],
                        'rate' => $value['rate']
                    ]);
                }

                $notification = array('msg' => 'Bonus Counts Successfully Inserted', 'alert-type' => 'success');
                $this->reset('supplier_info');
                return redirect()->route('bonus.index')->with($notification);
            }

        }else{
            $notification = array('msg' => 'Please Add Bonus Count values', 'alert-type' => 'error');
            return redirect()->route('bonus.create')->with($notification);
        }

    }



    public function render()
    {
        $suppliers = Supplier::all();
        return view('livewire.bonus-count.monthly.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
