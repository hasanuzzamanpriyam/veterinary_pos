@extends('layouts.admin')

@section('page-title')
Salary Expense Update
@endsection

@section('main-content')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Salary Expense</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content p-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('salary.expense.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left add_salary_expense_form">
                @csrf
                <input type="hidden" name="id" value="{{$salary_expense->id}}" />
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="id_no">Name</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="hidden" name="id_no" id="id_no" value="{{$salary_expense->name}}" >
                                <input type="text" class="form-control" value="{{$salary_expense->name}}" readonly>
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="w-100">
                                <label class="col-form-label col w-100 label-align" for="employee_address">Address</label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="employee_address" id="employee_address" cols="10" rows="1" class="form-control" readonly>{{$salary_expense->address}}</textarea>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="employee_mobile">Mobile</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" name="employee_mobile" id="employee_mobile" class="form-control" value="{{$salary_expense->mobile}}" readonly>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="employee_deg">Designation</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" name="employee_deg" id="employee_deg" class="form-control" value="{{$salary_expense->designation}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align">Date</label>
                            <div class="col-md-8 col-sm-8">
                                <input name="date" type="text" class="form-control" value="{{date('d-m-Y',strtotime($salary_expense->created_at))}}" placeholder="dd-mm-yyyy" readonly>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="amount">Salary Tk</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="amount" name="amount" class="form-control text-right" value="{{$salary_expense->amount}}">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="other_charge">Bouns/Others</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="other_charge" name="other_charge" class="form-control text-right" value="{{$salary_expense->other_charge}}">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="other_charge">Total Tk</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="total" class="form-control text-right" readonly value="{{$salary_expense->amount + $salary_expense->other_charge}} "/>
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="w-100">
                                <label class="col-form-label col w-100 label-align" for="remarks">Remarks</label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$salary_expense->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('salary.expense.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
jQuery(document).ready(function() {

    $("#amount, #other_charge").on('keyup', function(){
        setTimeout(function(){
            amoutCalculation();
        }, 500);
    });

    function amoutCalculation(){
        var amount=document.getElementById('amount').value;
        if(amount==''){
            amount=0;
        }else{
            amount=parseInt(amount);
        }
        var otherCharge=document.getElementById('other_charge').value;
        if(otherCharge==''){
            otherCharge=0;
        }else{
            otherCharge=parseInt(otherCharge);
        }
        var result=amount+otherCharge;
        $('#total').val(result);
    }
})
</script>
@endpush

