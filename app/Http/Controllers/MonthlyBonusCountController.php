<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyBonusCount;
use App\Models\Supplier;


class MonthlyBonusCountController extends Controller
{
    public function supplierSearch(Request $request)
    {

        $data = [];

        if($request->filled('q')){
            $data = Supplier::select("company_name", "mobile","address","id")
                        ->where('company_name', 'LIKE', '%'. $request->get('q'). '%')
                        ->get();
        }
        else
        {
            $data = Supplier::get();
        }

        return response()->json($data);

    }

    public function delete($id)
    {
        MonthlyBonusCount::where('supplier_id',$id)->delete();
        $alert = array('msg' => 'Bonus Count List Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('bonus.index')->with($alert);

    }

    public static function getBonusCount($id)
    {
        $data = MonthlyBonusCount::where('supplier_id', $id)->first();
        return $data ?? 0;
    }
}
