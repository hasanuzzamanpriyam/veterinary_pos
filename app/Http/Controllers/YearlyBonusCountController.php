<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\YearlyBonusCount;
use Illuminate\Http\Request;

class YearlyBonusCountController extends Controller
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
       YearlyBonusCount::where('id',$id)->delete();
       $alert = array('msg' => 'Yearly Bonus Count List Successfully Deleted', 'alert-type' => 'warning');
       return redirect()->route('yearly.bonus.index')->with($alert);

   }
}
