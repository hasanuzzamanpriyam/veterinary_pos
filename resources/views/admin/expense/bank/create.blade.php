@extends('layouts.admin')

@section('page-title')
Bank Expense Add
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Add Bank Expense</h2>
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
            <form action="{{route('bank.expense.store')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align">Date<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input id="date" name="date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="id_no">Bank Name
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select type="text" name="id_no" id="id_no" class="form-control">
                                    <option value="">Select Option</option>
                                    @foreach ($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="amount">Profit Amount
                              <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="amount" onkeyup="amoutCalculation()" name="amount" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="amount_month">Profit Month
                            <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <div class="row">
                                    <div class="col-7 pr-1">
                                        <select type="text" id="amount_month" name="amount_month" class="form-control" >
                                            <option value="">Select Option</option>
                                            @foreach($months as $amount_month)
                                                <option value="{{$amount_month}}">{{$amount_month}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-5 ml-0 pl-0">
                                        <select type="text" id="year" name="year" class="form-control" >
                                            <option value="">Select</option>
                                            @php $years = range(2023, date('Y')); @endphp
                                            @foreach($years as $year)
                                                <option value="{{$year}}" @if($year==date('Y')) selected @endif>{{$year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="other_charge">Other Charge
                              <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" onkeyup="amoutCalculation()" id="other_charge" name="other_charge" class="form-control" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="payment_by">Paying By<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select type="text" id="payment_by" name="payment_by"  class="form-control" >
                                    <option value="">Select Option</option>
                                    @foreach($payment_types as $payment_type)
                                        <option value="{{$payment_type}}">{{$payment_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="payment_amount">Payment Amount<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="payment_amount" name="payment_amount"  class="form-control">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="remarks"> Remarks
                               <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control"></textarea>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('bank.expense.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button class="btn btn-warning" type="reset">Reset</button>
                        <button type="submit" class="btn btn-success">Submit</button>
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
    var otherCharge=document.getElementById('other_charge').value;
    if(otherCharge==''){
        otherCharge=0;
    }else{
        otherCharge=parseInt(otherCharge);
    }
    var result=amount+otherCharge;
    $('#payment_amount').val(result);
}

</script>
@endsection
