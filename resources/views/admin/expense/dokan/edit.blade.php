@extends('layouts.admin')

@section('page-title')
Dokan Expense Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Dokan Expense</h2>
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
            <form action="{{route('dokan.expense.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align">Date<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input id="date" name="date" class="date-picker form-control" value="{{$dokan_expense->date}}" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                                <input type="hidden" name="id" id="id" value="{{$dokan_expense->id}}">
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="voucher_no">Voucher No<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="voucher_no" name="voucher_no"  value="{{$dokan_expense->voucher_no}}" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="receiver_name">Dokan Name
                                <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select type="text" name="id_no" id="id_no" class="form-control">
                                    <option value="">Select Option</option>
                                    @foreach ($stores as $store)
                                    <option value="{{$store->id}}" @if($store->id==$dokan_expense->id_no) selected @endif>{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-labelcol-md-4 col-sm-4 label-align" for="amount">Rent Amount
                              <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="amount" onkeyup="amoutCalculation()" name="amount" value="{{$dokan_expense->amount}}" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="amount_month">Rent Month
                            <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <div class="row">
                                    <div class="col-7 pr-1">
                                        <select type="text" id="amount_month" name="amount_month" value="{{$dokan_expense->amount_month}}" class="form-control" >
                                            <option value="">Select Option</option>
                                            @foreach($months as $amount_month)
                                                <option value="{{$amount_month}}" @if($dokan_expense->amount_month == $amount_month) selected @endif>{{$amount_month}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-5 ml-0 pl-0">
                                        <select type="text" id="year" name="year" class="form-control" >
                                            <option value="">Select</option>
                                            @php $years = range(2023, date('Y')); @endphp
                                            @foreach($years as $year)
                                                <option value="{{$year}}" @if($year==$dokan_expense->year) selected @endif>{{$year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="receiving_by">Receiving By<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select type="text" id="receiving_by" name="receiving_by" class="form-control" >
                                    <option value="">Select Option</option>
                                    @foreach($payment_types as $payment_type)
                                        <option value="{{$payment_type}}" @if($dokan_expense->receiving_by == $payment_type ) selected="" @endif>{{$payment_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="payment_by">Paying By<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select type="text" id="payment_by" name="payment_by" class="form-control" >
                                    <option value="">Select Option</option>
                                    @foreach($payment_types as $payment_type)
                                        <option value="{{$payment_type}}" @if($dokan_expense->payment_by == $payment_type ) selected="" @endif>{{$payment_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="payment_amount">Payment Amount<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="payment_amount" name="payment_amount" value="{{$dokan_expense->payment_amount}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="remarks"> Remarks
                               <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$dokan_expense->remarks}}</textarea>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('dokan.expense.index')}}" class="btn btn-danger" type="button">Cancel</a>
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
