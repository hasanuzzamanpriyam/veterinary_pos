<?php

namespace App\Livewire\CashMaintenance;

use App\Models\CashManager;
use App\Models\CashTransactions;
use Livewire\Component;

class CashEditCheckout extends Component
{

    public $cash_data;
    public $id;
    public $date;
    public $allData = [];
    public $summary = [];


    public function mount()
    {

        if (!session()->has('cash_edit_data')) {
            return redirect()->to(route('cash_maintenance.index'));
        } else {
            $cash_data = session()->get('cash_edit_data');
            if(is_array($cash_data)){
                $this->id = $cash_data['id'];
                $this->date = $cash_data['date'];
                $this->allData = $cash_data['allData'];
                $this->summary = $cash_data['summary'];
            }
        }
    }

    public function cancel()
    {
        session()->forget('cash_edit_data');
        return redirect()->route('cash_maintenance.index');
    }

    public function update()
    {
        if($this->id){
            $insertData = [];
            $cash_manager = CashManager::where('id', $this->id)->first();
            if ($cash_manager) {
                $prev = floatval($this->summary['prev_balance'] ?? 0);
                $collection = floatval($this->summary['collection'] ?? 0);
                $payment = floatval($this->summary['payment'] ?? 0);
                $expense = floatval($this->summary['expense'] ?? 0);
                $home_cash = floatval($this->summary['home_cash'] ?? 0);
                $short_cash = floatval($this->summary['short_cash'] ?? 0);

                $dokan_cash = $prev + $collection - $payment - $expense - $home_cash - $short_cash;

                $cash_manager->update([
                    'prev_balance' => $prev,
                    'collection' => $collection,
                    'payment' => $payment,
                    'expense' => $expense,
                    'home_cash' => $home_cash,
                    'short_cash' => $short_cash,
                    'dokan_cash' => $dokan_cash,
                ]);


                CashTransactions::where('trnx_id', $this->id)->delete();
                foreach($this->allData as $data){
                    foreach($data as $item){
                        $insertData[] = [
                            'trnx_id' => $this->id,
                            'name' => $item['name'] ?? '',
                            'address' => $item['address'] ?? '',
                            'mobile' => $item['mobile'] ?? '',
                            'amount' => $item['amount'],
                            'note' => $item['note'] ?? '',
                            'type' => $item['type'],
                            'created_at' => $this->date,
                            'updated_at' => $this->date,
                        ];
                    }
                }

                CashTransactions::insert($insertData);

                session()->flash('cash_edit_data');

                $alert = array('msg' => 'Successfully Inserted', 'alert-type' => 'success');
                return redirect()->route('cash_maintenance.view', $this->id)->with($alert);
            }

        }

    }
    public function render()
    {
        return view('livewire.cash-maintenance.cash-edit-checkout', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
