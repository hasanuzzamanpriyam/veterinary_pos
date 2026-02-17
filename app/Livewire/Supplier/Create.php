<?php

namespace App\Livewire\Supplier;

use Livewire\Component;

class Create extends Component
{
    public $company_name;
    public $owner_name;
    public $officer_name;
    public $address;
    public $phone;
    public $mobile;
    public $email;
    public $photo;
    public $ledger_page;
    public $condition;
    public $dealer_code;
    public $dealer_area;
    public $security;
    public $credit_limit;
    public $advance_payment;
    public $previous_due;
    public $starting_date;


    public function rules()
    {

        return [
            'company_name' => ['required', 'max:255'],
            'owner_name' => ['nullable', 'max:255'],
            'officer_name' => ['nullable', 'max:255'],
            'address' => ['nullable', 'max:255'],
            'phone' => ['nullable', 'max:20'],
            'mobile' => ['nullable', 'max:20'],
            'email' => ['nullable', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1000'],
            'ledger_page' => ['nullable', 'max:255'],
            'dealer_code' => ['nullable', 'max:20'],
            'dealer_area' => ['nullable', 'max:255'],
            'condition' => ['nullable', 'max:255'],
            'security' => ['nullable', 'max:255'],
            'credit_limit' => ['nullable', 'max:20'],
            'advance_payment' => ['nullable', 'max:20'],
            'previous_due' => ['nullable', 'max:20'],
            'starting_date' => 'required',
        ];
    }

    public function sessionCreate()
    {
        $validated_data = $this->validate();
        // dd($validated_data);
        $supplier = [
            'company_name' => $validated_data['company_name'],
            'owner_name' => $validated_data['owner_name'],
            'officer_name' => $validated_data['officer_name'],
            'address' => $validated_data['address'],
            'phone' => $validated_data['phone'],
            'mobile' => $validated_data['mobile'],
            'email' => $validated_data['email'],
            'photo' => $validated_data['photo'],
            'ledger_page' => $validated_data['ledger_page'],
            'condition' => $validated_data['condition'],
            'dealer_code' => $validated_data['dealer_code'],
            'dealer_area' => $validated_data['dealer_area'],
            'security' => $validated_data['security'],
            'credit_limit' => $validated_data['credit_limit'],
            'advance_payment' => $validated_data['advance_payment'],
            'previous_due' => $validated_data['previous_due'],
            'starting_date' => $validated_data['starting_date'],
        ];

        session()->put('supplier_data', $supplier);

        return redirect()->route('live.supplier.checkout');
    }

    public function clear()
    {
        session()->forget('supplier_data');
        return redirect()->route('live.supplier.create');
    }

    public function render()
    {
        if (session()->has('supplier_data')) {
            $supplier = session()->get('supplier_data');
            $this->company_name = isset($supplier['company_name']) ? $supplier['company_name'] : null;
            $this->owner_name = isset($supplier['owner_name']) ? $supplier['owner_name'] : null;
            $this->officer_name = isset($supplier['officer_name']) ? $supplier['officer_name'] : null;
            $this->address = isset($supplier['address']) ? $supplier['address'] : null;
            $this->phone = isset($supplier['phone']) ? $supplier['phone'] : null;
            $this->mobile = isset($supplier['mobile']) ? $supplier['mobile'] : null;
            $this->email = isset($supplier['email']) ? $supplier['email'] : null;
            $this->photo = isset($supplier['photo']) ? $supplier['photo'] : null;
            $this->ledger_page = isset($supplier['ledger_page']) ? $supplier['ledger_page'] : null;
            $this->condition = isset($supplier['condition']) ? $supplier['condition'] : null;
            $this->dealer_code = isset($supplier['dealer_code']) ? $supplier['dealer_code'] : null;
            $this->dealer_area = isset($supplier['dealer_area']) ? $supplier['dealer_area'] : null;
            $this->security = isset($supplier['security']) ? $supplier['security'] : null;
            $this->credit_limit = isset($supplier['credit_limit']) ? $supplier['credit_limit'] : null;
            $this->advance_payment = isset($supplier['advance_payment']) ? $supplier['advance_payment'] : null;
            $this->previous_due = isset($supplier['previous_due']) ? $supplier['previous_due'] : null;
            $this->starting_date = isset($this->starting_date) ? $this->starting_date : $supplier['starting_date'];
        }
        return view('livewire.supplier.create')
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
