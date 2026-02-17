<?php

namespace App\Livewire\BonusCount\Yearly;

use App\Models\Supplier;
use App\Models\SupplierBonus;
use App\Models\YearlyBonusCount;
use Livewire\Component;

class Edit extends Component
{
    public $supplier_id;
    public $supplier_info;
    public $yearly_bonus = [];

    public function mount($id) {
        $this->supplier_id = $id;
        $bonus_data = YearlyBonusCount::where('supplier_id', $id)->orderBy('id', 'asc')->get();
        if ($bonus_data->count() > 0) {
            foreach ($bonus_data as $key => $value) {
                $this->yearly_bonus[$key]['start'] = $value->start;
                $this->yearly_bonus[$key]['end'] = $value->end;
                $this->yearly_bonus[$key]['rate'] = $value->rate;
            }
        }
    }

    public function addMoreRow(){
        $this->yearly_bonus[] = ['start' => '', 'end' => '', 'rate' => ''];
    }

    public function deleteARow($index){
        unset($this->yearly_bonus[$index]);
    }

    public function rules()
    {
        return
        [
            'supplier_id' => 'required',
            'yearly_bonus.*.start' => ['required', 'numeric'],
            'yearly_bonus.*.end' => ['required', 'numeric'],
            'yearly_bonus.*.rate' => ['required', 'numeric'],
        ];
    }

    public function messages(){

        return
        [
            'supplier_id.required' => 'Please Select Supplier',
            'yearly_bonus.*.start.required' => 'Please Enter From',
            'yearly_bonus.*.start.numeric' => 'Please Enter Valid From',
            'yearly_bonus.*.end.required' => 'Please Enter To',
            'yearly_bonus.*.end.numeric' => 'Please Enter Valid To',
            'yearly_bonus.*.rate.required' => 'Please Enter Rate',
            'yearly_bonus.*.rate.numeric' => 'Please Enter Valid Rate',
        ];
    }

    public function store() {
        $data = $this->validate();

        if (isset($data['yearly_bonus'])) {

            if( count($data['yearly_bonus']) == 0){

                $notification = array('msg' => 'Please Add Bonus Count', 'alert-type' => 'error');
                return redirect()->route('yearly.bonus.create')->with($notification);
            } else {
                YearlyBonusCount::where('supplier_id', $this->supplier_id)->delete();

                foreach ($data['yearly_bonus'] as $key => $value) {
                    SupplierBonus::updateOrCreate(
                        ['supplier_id' => $this->supplier_id],
                        [
                            'yearly' => true,
                        ]
                    );
                    YearlyBonusCount::insert([
                        'supplier_id'=> $this->supplier_id,
                        'start' => $value['start'],
                        'end' => $value['end'],
                        'rate' => $value['rate']
                    ]);
                }

                $notification = array('msg' => 'Bonus Counts Successfully Updated', 'alert-type' => 'success');
                $this->reset('supplier_info');
                return redirect()->route('yearly.bonus-count.index')->with($notification);
            }

        }else{
            $notification = array('msg' => 'Please Add Bonus Count values', 'alert-type' => 'error');
            return redirect()->route('bonus.create')->with($notification);
        }

    }

    public function deleteAllRow()
    {
        YearlyBonusCount::where('supplier_id', $this->supplier_id)->delete();
        $bonus = SupplierBonus::where('supplier_id', $this->supplier_id)->first();
        if($bonus) {
            if ($bonus->monthly == false && $bonus->cash_offer == false) {
                $bonus->delete();
            }else{
                SupplierBonus::updateOrCreate(
                    ['supplier_id' => $this->supplier_id],
                    [
                        'yearly' => false,
                    ]
                );
            }
        }
        $notification = array('msg' => 'Bonus Counts Successfully Deleted', 'alert-type' => 'success');
        $this->reset('supplier_info');
        return redirect()->route('yearly.bonus-count.index')->with($notification);
    }

    public function render()
    {
        $this->supplier_info = Supplier::where('id',$this->supplier_id)->first();
        return view('livewire.bonus-count.yearly.edit');
    }
}
