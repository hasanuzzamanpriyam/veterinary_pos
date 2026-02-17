<?php

namespace App\Livewire\BonusCount\Monthly;

use App\Models\MonthlyBonusCount;
use App\Models\Supplier;
use App\Models\SupplierBonus;
use Livewire\Component;

class Edit extends Component
{

    public $supplier_id;
    public $supplier_info;
    public $monthly_bonus = [];

    public function mount($id) {
        $this->supplier_id = $id;
        $bonus_data = MonthlyBonusCount::where('supplier_id', $id)->orderBy('id', 'asc')->get();
        if ($bonus_data->count() > 0) {
            foreach ($bonus_data as $key => $value) {
                $this->monthly_bonus[$key]['start'] = $value->start;
                $this->monthly_bonus[$key]['end'] = $value->end;
                $this->monthly_bonus[$key]['rate'] = $value->rate;
            }
        }
    }

    public function addMoreRow(){
        $this->monthly_bonus[] = ['start' => '', 'end' => '', 'rate' => ''];
    }

    public function deleteARow($index){
        unset($this->monthly_bonus[$index]);
    }

    public function rules()
    {
        return
        [
            'supplier_id' => 'required',
            'monthly_bonus.*.start' => ['required', 'numeric'],
            'monthly_bonus.*.end' => ['required', 'numeric'],
            'monthly_bonus.*.rate' => ['required', 'numeric'],
        ];
    }

    public function messages(){

        return
        [
            'supplier_id.required' => 'Please Select Supplier',
            'monthly_bonus.*.start.required' => 'Please Enter From',
            'monthly_bonus.*.start.numeric' => 'Please Enter Valid From',
            'monthly_bonus.*.end.required' => 'Please Enter To',
            'monthly_bonus.*.end.numeric' => 'Please Enter Valid To',
            'monthly_bonus.*.rate.required' => 'Please Enter Rate',
            'monthly_bonus.*.rate.numeric' => 'Please Enter Valid Rate',
        ];
    }

    public function store() {
        $data = $this->validate();

        if (isset($data['monthly_bonus'])) {

            if( count($data['monthly_bonus']) == 0){

                $notification = array('msg' => 'Please Add Bonus Count', 'alert-type' => 'error');
                return redirect()->route('bonus.create')->with($notification);
            } else {
                MonthlyBonusCount::where('supplier_id', $this->supplier_id)->delete();

                foreach ($data['monthly_bonus'] as $key => $value) {
                    SupplierBonus::updateOrCreate(
                        ['supplier_id' => $this->supplier_id],
                        [
                            'monthly' => true,
                        ]
                    );
                    MonthlyBonusCount::insert([
                        'supplier_id'=> $this->supplier_id,
                        'start' => $value['start'],
                        'end' => $value['end'],
                        'rate' => $value['rate']
                    ]);
                }

                $notification = array('msg' => 'Bonus Counts Successfully Updated', 'alert-type' => 'success');
                $this->reset('supplier_info');
                return redirect()->route('bonus.index')->with($notification);
            }

        }else{
            $notification = array('msg' => 'Please Add Bonus Count values', 'alert-type' => 'error');
            return redirect()->route('bonus.create')->with($notification);
        }

    }

    public function deleteAllRow()
    {
        MonthlyBonusCount::where('supplier_id', $this->supplier_id)->delete();
        $bonus = SupplierBonus::where('supplier_id', $this->supplier_id)->first();
        if($bonus){
            if ($bonus->yearly == false && $bonus->cash_offer == false) {
                $bonus->delete();
            }else{
                SupplierBonus::updateOrCreate(
                    ['supplier_id' => $this->supplier_id],
                    [
                        'monthly' => false,
                    ]
                );
            }
        }
        $notification = array('msg' => 'Bonus Counts Successfully Deleted', 'alert-type' => 'success');
        $this->reset('supplier_info');
        return redirect()->route('bonus.index')->with($notification);
    }

    public function render()
    {
        $this->supplier_info = Supplier::where('id',$this->supplier_id)->first();


        return view('livewire.bonus-count.monthly.edit')
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
