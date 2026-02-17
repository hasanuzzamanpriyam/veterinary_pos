<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gateways = PaymentGateway::all();
        return view('admin.payment_gateway.index', compact('gateways'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment_gateway.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [ 'required'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],

        ]);

        PaymentGateway::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Payment Gateway Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('payment-gateways.index')->with($alert);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        // dump($paymentGateway);
        return view('admin.payment_gateway.edit', compact('paymentGateway'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $request->validate([
            'name' => ['required'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],

        ]);

        $paymentGateway->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Payment Gateway Successfully Update', 'alert-type' => 'info');
        return redirect()->route('payment-gateways.index')->with($alert);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentGateway $paymentGateway)
    {
        $paymentGateway->delete();
        $alert = array('msg' => 'Payment Gateway Successfully Deleted', 'alert-type' => 'error');
        return redirect()->route('payment-gateways.index')->with($alert);
    }
}
