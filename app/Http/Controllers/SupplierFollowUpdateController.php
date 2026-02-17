<?php

namespace App\Http\Controllers;

use App\Models\SupplierFollowUpdate;

class SupplierFollowUpdateController extends Controller
{

    public function delete($id)
    {

        SupplierFollowUpdate::where('id',$id)->delete();

        $alert = array('msg' => 'Supplier Follow Update Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('supplier.follow.index')->with($alert);

    }
    // view details collection from here
    public function view($id)
    {
        $supplier = SupplierFollowUpdate::where('id',$id)->first();
        return view('admin.follow_update.supplier.view', compact('supplier'));
    }
}
