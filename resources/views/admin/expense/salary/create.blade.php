@extends('layouts.admin')

@section('page-title')
Salary Expense Add
@endsection

@section('main-content')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Add Salary Expense</h2>
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
            <form action="{{route('salary.expense.store')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left add_salary_expense_form">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="id_no">Name</label>
                            <div class="col-md-8 col-sm-8">
                                <select onchange="getEmployeeData()" type="text" id="id_no" name="id_no" class="form-control" >
                                    <option value="">Select Option</option>
                                    @foreach($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="w-100">
                                <label class="col-form-label col w-100 label-align" for="employee_address">Address</label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="employee_address" id="employee_address" cols="10" rows="1"  class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="employee_mobile">Mobile</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" name="employee_mobile" id="employee_mobile" class="form-control" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="employee_deg">Designation</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" name="employee_deg" id="employee_deg" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="expense_date">Date</label>
                            <div class="col-md-8 col-sm-8">
                                <div class="input-group date" id="expense_date_picker">
                                    <input name="date" id="expense_date" type="text" class="form-control" placeholder="dd-mm-yyyy" required>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="amount">Salary Tk</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="amount" name="amount" class="form-control text-right" />
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="other_charge">Bouns/Others</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="other_charge" name="other_charge" class="form-control text-right" />
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="other_charge">Total Tk</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="total" class="form-control text-right" readonly />
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="w-100">
                                <label class="col-form-label col w-100 label-align" for="remarks"> Remarks</label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('salary.expense.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button class="btn btn-warning" type="reset">Reset</button>
                        <button type="submit" class="btn btn-success">Submit</button>
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
    $('#expense_date_picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $("#amount, #other_charge").on('keyup', function(){
        setTimeout(function(){
            amoutCalculation();
        }, 500);
    });

})
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

function getEmployeeData() {
    var idNoValue = document.getElementById('id_no').value;

    if (idNoValue === "") {
        $('#employee_address').val('');
        $('#employee_mobile').val('');
        $('#employee_deg').val('');
        $('#amount').val('');
        $('#other_charge').val('');
        setTimeout(function(){
            amoutCalculation();
        }, 500);

    } else {
        $.ajax({
            method: "GET",
            dataType: 'json',
            url: "{{ route('get-single.employee', '') }}/" + encodeURIComponent(idNoValue),
            success: function(dbData) {
                $('#employee_address').val(dbData.address);
                $('#employee_mobile').val(dbData.mobile);
                $('#employee_deg').val(dbData.designation);
                $('#amount').val(parseInt(dbData.salary_amount));
                $('#other_charge').val(parseInt(dbData.bonus_amount));

                setTimeout(function(){
                    amoutCalculation();
                }, 700);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching employee data:", textStatus, errorThrown);
            }
        });
    }
}
</script>
@endpush
