<?php

namespace App\Http\Controllers;

use App\Models\CustomerFollowUpdate;

class CustomerFollowUpdateController extends Controller
{

    public function delete($id)
    {

        CustomerFollowUpdate::where('id',$id)->delete();

        $alert = array('msg' => 'Customer Follow Update Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('customer.follow.index')->with($alert);

    }
    // view details collection from here
    public function view($id)
    {
        $customer = CustomerFollowUpdate::where('id',$id)->first();
        return view('admin.follow_update.customer.view', compact('customer'));
    }

}
