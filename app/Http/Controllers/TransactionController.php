<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transaction;

class TransactionController extends Controller
{

    public function delete($id)
    {
        $row = Transaction::where('id', $id)->first();
        if ($row) {
            $type = $row->type;
            if ($type == 'deposit') {
                Bank::where('id', $row->bank_id)->decrement('balance', $row->amount);
            }else{
                Bank::where('id', $row->bank_id)->increment('balance', $row->amount);
            }
            $row->delete();
        }
        $alert = array('msg' => 'Bank transaction Successfully deleted', 'alert-type' => 'warning');
        return redirect()->route('transaction.bank.statement', $row->bank_id)->with($alert);
    }
}
