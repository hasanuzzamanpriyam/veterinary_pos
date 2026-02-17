<?php

namespace App\Livewire\BonusCount\Yearly;

use App\Models\Supplier;
use App\Models\SupplierBonus;
use App\Models\YearlyBonusCount;
use Livewire\Component;

class Index extends Component
{
    public $supplier_id;
    public $supplier_info;
    public $next_counter = 1;
    public $yearly_bonus = [];

    public function mount() {
        $this->yearly_bonus[] = ['start' => '', 'end' => '', 'rate' => ''];
    }



    public function rules()
    {
        return
        [
            'supplier_id' => 'required',
            'yearly_bonus.*.from' => ['required', 'numeric'],
            'yearly_bonus.*.to' => ['required', 'numeric'],
            'yearly_bonus.*.rate' => ['required', 'numeric'],
        ];
    }

    public function messages(){

        return
        [
            'supplier_id.required' => 'Please Select Supplier',
            'yearly_bonus.*.from.required' => 'Please Enter From',
            'yearly_bonus.*.from.numeric' => 'Please Enter Valid From',
            'yearly_bonus.*.to.required' => 'Please Enter To',
            'yearly_bonus.*.to.numeric' => 'Please Enter Valid To',
            'yearly_bonus.*.rate.required' => 'Please Enter Rate',
            'yearly_bonus.*.rate.numeric' => 'Please Enter Valid Rate',
        ];
    }

    public function addMoreRow(){
        $this->yearly_bonus[] = ['start' => '', 'end' => '', 'rate' => ''];
    }

    public function deleteARow($index){
        unset($this->yearly_bonus[$index]);
    }

    public function getSupplier($id)
    {
        $this->supplier_id = $id;
        $this->supplier_info = Supplier::where('id',$id)->first();


    }

    public function store()
    {
        $data = $this->validate();

        if (isset($data['yearly_bonus'])) {

            if( count($data['yearly_bonus']) == 0){

                $notification = array('msg' => 'Please Add Bonus Count', 'alert-type' => 'error');
                return redirect()->route('yearly.bonus.create')->with($notification);
            } else {
                SupplierBonus::updateOrCreate(
                    ['supplier_id' => $this->supplier_id],
                    [
                        'yearly' => true,
                    ]
                );
                foreach ($data['yearly_bonus'] as $key => $value) {
                    YearlyBonusCount::insert([
                        'supplier_id'=> $this->supplier_id,
                        'start' => $value['from'],
                        'end' => $value['to'],
                        'rate' => $value['rate']
                    ]);
                }

                $notification = array('msg' => 'Bonus Counts Successfully Inserted', 'alert-type' => 'success');
                $this->reset('supplier_info');
                return redirect()->route('yearly.bonus-count.index')->with($notification);
            }

        }else{
            $notification = array('msg' => 'Please Add Bonus Count values', 'alert-type' => 'error');
            return redirect()->route('yearly.bonus.create')->with($notification);
        }

    }

    public function render()
    {
        $suppliers = Supplier::all();
        return view('livewire.bonus-count.yearly.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
