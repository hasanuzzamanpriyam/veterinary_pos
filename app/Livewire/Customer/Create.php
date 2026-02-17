<?php

namespace App\Livewire\Customer;

use App\Models\customer;
use App\Models\CustomerTypes;
use App\Models\PriceGroup;
use Livewire\Component;

class Create extends Component
{

    public $price_groups;
    public $customer_types;
    public $customers;

    public $name;
    public $company_name;
    public $father_name;
    public $address;
    public $nid;
    public $birthday;
    public $mobile;
    public $phone;
    public $photo;
    public $email;
    public $ledger_page;
    public $price_group;
    public $type;
    public $security;
    public $credit_limit;
    public $advance_payment = 0;
    public $previous_due = 0;
    public $starting_date;

    // Guarantor Info
    public $guarantor_name;
    public $guarantor_company_name;
    public $guarantor_father_name;
    public $guarantor_address;
    public $guarantor_nid;
    public $guarantor_birthday;
    public $guarantor_mobile;
    public $guarantor_phone;
    public $guarantor_email;
    public $guarantor_security;
    public $guarantor_photo;
    public $guarantor_remarks;


    public function mount() {
        $this->price_groups = PriceGroup::get();
        $this->customer_types = CustomerTypes::latest()->get();
        $this->customers = customer::get();
    }

    public function rules(){
        return [
            'name' => 'required',
            'company_name' => 'nullable',
            'father_name' => 'nullable',
            'address' => 'nullable',
            'nid' => 'nullable',
            'birthday' => 'nullable',
            'mobile' => 'nullable',
            'phone' => 'nullable',
            'photo' => 'nullable',
            'email' => 'nullable',
            'ledger_page' => 'nullable',
            'price_group' => 'nullable',
            'type' => 'nullable',
            'security' => 'nullable',
            'credit_limit' => 'nullable',
            'advance_payment' => 'nullable',
            'previous_due' => 'nullable',
            'starting_date' => 'required',

            'guarantor_name' => 'nullable',
            'guarantor_company_name' => 'nullable',
            'guarantor_father_name' => 'nullable',
            'guarantor_address' => 'nullable',
            'guarantor_nid' => 'nullable',
            'guarantor_birthday' => 'nullable',
            'guarantor_mobile' => 'nullable',
            'guarantor_phone' => 'nullable',
            'guarantor_email' => 'nullable',
            'guarantor_security' => 'nullable',
            'guarantor_photo' => 'nullable',
            'guarantor_remarks' => 'nullable',

        ];
    }

    public function sessionCreate(){
        $validated_data = $this->validate();
        // dd($validated_data);
        $customer = [
            'name' => $validated_data['name'],
            'company_name' => $validated_data['company_name'],
            'father_name' => $validated_data['father_name'],
            'address' => $validated_data['address'],
            'nid' => $validated_data['nid'],
            'birthday' => $validated_data['birthday'],
            'mobile' => $validated_data['mobile'],
            'phone' => $validated_data['phone'],
            'photo' => $validated_data['photo'],
            'email' => $validated_data['email'],
            'ledger_page' => $validated_data['ledger_page'],
            'price_group' => $validated_data['price_group'],
            'type' => $validated_data['type'],
            'security' => $validated_data['security'],
            'credit_limit' => $validated_data['credit_limit'],
            'advance_payment' => empty($validated_data['advance_payment']) ? 0 : $validated_data['advance_payment'],
            'previous_due' => empty($validated_data['previous_due']) ? 0 : $validated_data['previous_due'],
            'starting_date' => $validated_data['starting_date'],


            'guarantor_name' => $validated_data['guarantor_name'],
            'guarantor_company_name' => $validated_data['guarantor_company_name'],
            'guarantor_father_name' => $validated_data['guarantor_father_name'],
            'guarantor_address' => $validated_data['guarantor_address'],
            'guarantor_nid' => $validated_data['guarantor_nid'],
            'guarantor_birthday' => $validated_data['guarantor_birthday'],
            'guarantor_mobile' => $validated_data['guarantor_mobile'],
            'guarantor_phone' => $validated_data['guarantor_phone'],
            'guarantor_email' => $validated_data['guarantor_email'],
            'guarantor_security' => $validated_data['guarantor_security'],
            'guarantor_photo' => $validated_data['guarantor_photo'],
            'guarantor_remarks' => $validated_data['guarantor_remarks'],

        ];

            session()->put('customer_data', $customer);

        return redirect()->route('live.customer.checkout');
    }

    public function clear(){
        session()->forget('customer_data');
        return redirect()->route('live.customer.create');
    }

    public function cancel(){
        session()->forget('customer_data');
        return redirect()->route('customer.index');
    }

    public function render()
    {
        if(session()->has('customer_data')) {
            $customer = session()->get('customer_data');
            $this->name = isset($customer['name']) ? $customer['name'] : null;
            $this->company_name = isset($customer['company_name']) ? $customer['company_name'] : null;
            $this->father_name = isset($customer['father_name']) ? $customer['father_name'] : null;
            $this->address = isset($customer['address']) ? $customer['address'] : null;
            $this->nid = isset($customer['nid']) ? $customer['nid'] : null;
            $this->birthday = isset($this->birthday) ? $this->birthday : $customer['birthday'];
            $this->mobile = isset($customer['mobile']) ? $customer['mobile'] : null;
            $this->phone = isset($customer['phone']) ? $customer['phone'] : null;
            $this->photo = isset($customer['photo']) ? $customer['photo'] : null;
            $this->email = isset($customer['email']) ? $customer['email'] : null;
            $this->ledger_page = isset($customer['ledger_page']) ? $customer['ledger_page'] : null;
            $this->price_group = isset($customer['price_group']) ? $customer['price_group'] : null;
            $this->type = isset($customer['type']) ? $customer['type'] : null;
            $this->security = isset($customer['security']) ? $customer['security'] : null;
            $this->credit_limit = isset($customer['credit_limit']) ? $customer['credit_limit'] : null;
            $this->advance_payment = isset($customer['advance_payment']) ? $customer['advance_payment'] : null;
            $this->previous_due = isset($customer['previous_due']) ? $customer['previous_due'] : null;
            $this->starting_date = isset($this->starting_date) ? $this->starting_date : $customer['starting_date'];


            $this->guarantor_name = isset($customer['guarantor_name']) ? $customer['guarantor_name'] : null;
            $this->guarantor_company_name = isset($customer['guarantor_company_name']) ? $customer['guarantor_company_name'] : null;
            $this->guarantor_father_name = isset($customer['guarantor_father_name']) ? $customer['guarantor_father_name'] : null;
            $this->guarantor_address = isset($customer['guarantor_address']) ? $customer['guarantor_address'] : null;
            $this->guarantor_nid = isset($customer['guarantor_nid']) ? $customer['guarantor_nid'] : null;
            $this->guarantor_birthday = isset($customer['guarantor_birthday']) ? $customer['guarantor_birthday'] : null;
            $this->guarantor_mobile = isset($customer['guarantor_mobile']) ? $customer['guarantor_mobile'] : null;
            $this->guarantor_phone = isset($customer['guarantor_phone']) ? $customer['guarantor_phone'] : null;
            $this->guarantor_email = isset($customer['guarantor_email']) ? $customer['guarantor_email'] : null;
            $this->guarantor_security = isset($customer['guarantor_security']) ? $customer['guarantor_security'] : null;
            $this->guarantor_photo = isset($customer['guarantor_photo']) ? $customer['guarantor_photo'] : null;
            $this->guarantor_remarks = isset($customer['guarantor_remarks']) ? $customer['guarantor_remarks'] : null;

        }
        return view('livewire.customer.create', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
