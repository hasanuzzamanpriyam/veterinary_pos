<?php

namespace App\Http\Controllers;

use App\Models\CashManager;
use App\Models\CashTransactions;

class CashMaintenanceController extends Controller
{
    public function delete($id)
    {
        if($id){
            $cash_id = CashManager::where('id', $id)->first();
            if($cash_id){
                CashTransactions::where('trnx_id', $id)->delete();
            }
            $cash_id->delete();
            $alert = array('msg' => 'Cash Maintenance Successfully Deleted', 'alert-type' => 'success');
            return redirect()->route('cash_maintenance.index')->with($alert);
        }


    }
}
