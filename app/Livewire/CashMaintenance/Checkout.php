<?php

namespace App\Livewire\CashMaintenance;

use App\Models\CashManager;
use App\Models\CashTransactions;
use Livewire\Component;

class Checkout extends Component
{
    public $cash_data;
    public $date;
    public $prev_balance;
    public $allData = [];
    public $summary = [];

    public function mount()
    {
        if (!session()->has('cash_data')) {
            return redirect()->to(route('cash_maintenance.create'));
        } else {
            $cash_data = session()->get('cash_data');
            if(is_array($cash_data)){
                $this->date = $cash_data['date'];
                $this->prev_balance = $cash_data['prev_balance'];
                $this->allData = $cash_data['allData'];
                $this->summary = $cash_data['summary'];
            }
        }
    }

    public function cancel()
    {
        session()->forget('cash_data');
        return redirect()->route('cash_maintenance.index');

    }

    public function store()
    {
        $insertData = [];
        $combinedDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($this->date)) . ' ' . date('H:i:s')));

        foreach($this->allData as $data){
            foreach($data as $item){
                $insertData[] = [
                    'name' => $item['name'] ?? '',
                    'address' => $item['address'] ?? '',
                    'mobile' => $item['mobile'] ?? '',
                    'amount' => $item['amount'],
                    'note' => $item['note'] ?? '',
                    'type' => $item['type'],
                    'created_at' => $combinedDateTime,
                    'updated_at' => $combinedDateTime,
                ];
            }
        }

        $existing = CashManager::whereDate('date', date('Y-m-d', strtotime($this->date)))->first();
        if(!$existing){
            $balance = floatval($this->prev_balance)
                    + floatval($this->summary['collection'])
                    - floatval($this->summary['payment'])
                    - floatval($this->summary['expense'])
                    - floatval($this->summary['home_cash'])
                    - floatval($this->summary['short_cash']);
            $newId = CashManager::insertGetId([
                'date' => $combinedDateTime,
                'prev_balance' => $this->prev_balance,
                'collection' => $this->summary['collection'],
                'payment' => $this->summary['payment'],
                'expense' => $this->summary['expense'],
                'home_cash' => $this->summary['home_cash'],
                'short_cash' => $this->summary['short_cash'],
                'dokan_cash' => $balance,
                'created_at' => $combinedDateTime,
                'updated_at' => $combinedDateTime,

            ]);

            // map trnx_id for each item
            foreach($insertData as &$data){
                $data['trnx_id'] = $newId;
            }

            CashTransactions::insert($insertData);
        }

        session()->forget('cash_data');

        $alert = array('msg' => 'Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('cash_maintenance.index')->with($alert);
    }

    public function render()
    {
        return view('livewire.cash-maintenance.checkout', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
