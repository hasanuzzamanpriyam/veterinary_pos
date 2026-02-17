@extends('layouts.admin')

@section('page-title')
Labour Expense Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Labour Expense</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>

        </div>
        <div class="x_content p-3">
            <br />
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('labour.expense.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align">Date<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input id="date" name="date" class="date-picker form-control" value="{{$labour_expense->date}}" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                                <input type="hidden" name="id" id="id" value="{{$labour_expense->id}}">
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="voucher_no">Voucher No<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="voucher_no" name="voucher_no"  value="{{$labour_expense->voucher_no}}" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="purpose">Purpose
                                <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="purpose" name="purpose" value="{{$labour_expense->purpose}}" class="form-control" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="amount">Amount
                              <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="amount" onkeyup="amoutCalculation()" name="amount" value="{{$labour_expense->amount}}" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="receiver_name">Receive
                            <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="receiver_name" name="receiver_name" value="{{$labour_expense->receiver_name}}" class="form-control" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="payment_by">Paying By<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select type="text" id="payment_by" name="payment_by"  class="form-control" >
                                    <option value="">Select Option</option>
                                    @foreach($payment_types as $payment_type)
                                        <option value="{{$payment_type}}" @if($labour_expense->payment_by == $payment_type ) selected="" @endif>{{$payment_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="payment_amount">Payment Amount<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="payment_amount" name="payment_amount" value="{{$labour_expense->payment_amount}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="remarks"> Remarks
                               <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$labour_expense->remarks}}</textarea>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('labour.expense.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // amout calculation
function amoutCalculation() {
    var amount=document.getElementById('amount').value;
    if(amount==''){
        amount=0;
    }else{
        amount=parseInt(amount);
    }

    $('#payment_amount').val(amount);
}

</script>
@endsection
