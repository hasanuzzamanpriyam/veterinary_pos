<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BankController extends Controller
{

    public function create()
    {
        return view('admin.bank.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' =>  ['max:255'],
            'title' => ['max:100'],
            'branch' => ['max:255'],
            'remarks' => ['max:555'],
            'ac_mode' => ['max:555']
        ]);

        $bank = Bank::create([
            'code' => $request->code,
            'name' => $request->name,
            'title' => $request->title,
            'branch' => $request->branch,
            'account_no' => $request->account_no,
            'ac_mode' => $request->ac_mode,
            'balance' => 0,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Bank Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('bank.index')->with($alert);

    }
    public function edit($id)
    {
        $bank = Bank::where('id',$id)->first();

        return view('admin.bank.edit',get_defined_vars());
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' =>  ['max:255'],
            'title' => ['max:100'],
            'branch' => ['max:255'],
            'remarks' => ['max:555'],
            'ac_mode' => ['max:555']

        ]);

        Bank::where('id',$request->id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'title' => $request->title,
            'branch' => $request->branch,
            'account_no' => $request->account_no,
            'remarks' => $request->remarks,
            'ac_mode' => $request->ac_mode
        ]);

        $alert = array('msg' => 'Bank Successfully Update', 'alert-type' => 'info');
        return redirect()->route('bank.index')->with($alert);
    }

    public function delete($id)
    {


        Bank::where('id', $id)->delete();
        Transaction::where('bank_id', $id)->delete();

        $alert = array('msg' => 'Bank Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('bank.index')->with($alert);
    }
}
