<?php

namespace App\Livewire\Customer;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\PriceGroup;
use Livewire\Component;

class Checkout extends Component
{
    public $customer;
    public $price_groups;

    public function mount()
    {
        $this->customer = session()->has('customer_data') ? session()->get('customer_data') : null;
        $this->price_groups = $this->customer && $this->customer['price_group'] ? PriceGroup::where('id', $this->customer['price_group'])->first() : null;
    }

    public function clear(){
        session()->forget('customer_data');
        return redirect()->route('live.customer.create');
    }
    public function cancel(){
        session()->forget('customer_data');
        return redirect()->route('customer.index');
    }

    public function submit(){
        // dd($this->customer);
        if ( $this->customer ) {
            $balance = $this->customer['advance_payment'] ? -$this->customer['advance_payment'] : $this->customer['previous_due'];

            $photo_path = null;
            $guarantor_photo_path = null;

            $formated_starting_date = $this->customer['starting_date'] ? date('Y-m-d', strtotime($this->customer['starting_date'])) : null;

            $customer_id = customer::insertGetId([
                'name' => $this->customer['name'],
                'father_name' => $this->customer['father_name'],
                'company_name' => $this->customer['company_name'],
                'email' => $this->customer['email'],
                'phone' => $this->customer['phone'],
                'mobile' => $this->customer['mobile'],
                'address' => $this->customer['address'],
                'nid' => $this->customer['nid'],
                'birthday' => date('Y-m-d', strtotime($this->customer['birthday'])),
                'ledger_page' => $this->customer['ledger_page'],
                'type' => $this->customer['type'],
                'price_group_id' => $this->customer['price_group'],
                'security' => $this->customer['security'],
                'credit_limit' => $this->customer['credit_limit'],
                'balance' => $balance ? $balance : 0,
                'starting_date' => $formated_starting_date,
                'photo' => $photo_path,
                'guarantor_name' => $this->customer['guarantor_name'],
                'guarantor_company_name' => $this->customer['guarantor_company_name'],
                'guarantor_birthday' => date('Y-m-d', strtotime($this->customer['guarantor_birthday'])),
                'guarantor_mobile' => $this->customer['guarantor_mobile'],
                'guarantor_father_name' => $this->customer['guarantor_father_name'],
                'guarantor_phone' => $this->customer['guarantor_phone'],
                'guarantor_email' => $this->customer['guarantor_email'],
                'guarantor_address' => $this->customer['guarantor_address'],
                'guarantor_security' => $this->customer['guarantor_security'],
                'guarantor_nid' => $this->customer['guarantor_nid'],
                'guarantor_remarks' => $this->customer['guarantor_remarks'],
                'guarantor_photo' => $guarantor_photo_path,
            ]);
            if( $this->customer['advance_payment'] || $this->customer['previous_due'] ){
                CustomerLedger::insert([
                    'customer_id' => $customer_id,
                    'type'        => 'other',
                    'balance'     => $balance ? $balance : 0,
                    'payment'     => $this->customer['advance_payment'] ? $this->customer['advance_payment'] : 0,
                    'remarks'     => $this->customer['advance_payment'] ? "Advance Collection" : "Previous Due",
                    'received_by' => $this->customer['advance_payment'] ? "Advance Collection" : "Previous Due",
                    'date'        => $formated_starting_date,
                    'u_id'        => 0,
                ]);
            }

            session()->forget('customer_data');
            $alert = array('msg' => 'Customer Successfully Inserted', 'alert-type' => 'success');
            return redirect()->route('customer.index')->with($alert);
        }
    }

    public function render()
    {
        return view('livewire.customer.checkout', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
