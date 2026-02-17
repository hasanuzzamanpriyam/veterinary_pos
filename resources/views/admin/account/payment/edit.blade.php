@extends('layouts.admin')

@section('page-title')
Payment Update
@endsection

@section('main-content')

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2 class="payments_update_title">Update Payment:</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="#">Settings 1</a>
                            </li>
                            <li><a class="dropdown-item" href="#">Settings 2</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
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
                <form action="{{route('payment.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left update_payment_form">
                    @csrf
                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="row">
                            <div class="collection-form-area d-flex justify-content-center">
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <div class="form-group ">
                                        <label class="" for="date">Date:</label>
                                            <input id="date" name="date" value="{{$supplier->date}}" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                            <script>
                                                function timeFunctionLong(input) {
                                                    setTimeout(function() {
                                                        input.type = 'text';
                                                    }, 60000);
                                                }
                                            </script>
                                    </div>

                                    <div class="form-group">
                                        <label for="supplier_name">Voucher No:</label>
                                        <input type="text"  name="invoice_no"  value="{{$supplier->invoice_no}}" class="form-control">
                                        <input type="hidden"  name="id"  value="{{$supplier->id}}">
                                        <input type="hidden"  name="supplier_id"  value="{{$supplier->supplier_id}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="supplier_name">Supplier Name:</label>
                                        <input type="text"  name="supplier_name"  value="{{$supplier->supplier->company_name}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label  for="prepare">Paying By:</label>
                                        <select type="text" name="payment_by"  class="form-control">
                                            <option value="">Select Option</option>
                                                @foreach($payment_types as $payment_type)
                                                    <option value="{{$payment_type}}" @if($supplier->payment_by == $payment_type ) selected="" @endif>{{$payment_type}}</option>
                                                @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label  for="prepare">Bank Name:</label>
                                        <select type="text" name="bank_title"  class="form-control">
                                                <option value="">Select Option</option>
                                                    @foreach($banks as $bank)
                                                        <option value="{{$bank->title}}" @if($supplier->bank_title == $bank->title ) selected="" @endif>{{$bank->title}}</option>
                                                    @endforeach
                                        </select>
                                    </div>
                                    @if($supplier->payment_by === 'Cheque')
                                        <div class="form-group">
                                            <label  for="prepare">Cheque No.:</label>
                                                <input type="number" name="cheque" value="{{$supplier->bank_title}}" class="form-control">
                                        </div>
                                    @else
                                    <div class="form-group">
                                        <label  for="prepare">Cheque No.:</label>
                                            <input type="number"  name="cheque" class="form-control">

                                    </div>
                                    @endif
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="supplier_name">Address:</label>
                                        <input type="text"  name="address" value="{{$supplier->supplier->address}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label  for="prepare">Purpose:</label>
                                        <textarea type="text" name="purpose"  cols="5" rows="1" class="form-control">{{$supplier->purpose}}</textarea>
                                    </div>

                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="supplier_name">Mobile:</label>
                                        <input type="text"  name="mobile"  value="{{$supplier->supplier->mobile}}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label  for="prepare">Remarks:</label>
                                        <textarea type="text" name="remarks" id="transport_no" class="form-control" cols="5" rows="1">{{$supplier->remarks}}</textarea>
                                    </div>

                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    @if(isset($supplier->previous_advance))
                                        <div class="form-group">
                                            <label for="supplier_name">Total Due:</label>
                                            <input type="text"  name="previous_advance"  value="{{-$supplier->previous_advance}}" class="form-control">
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="supplier_name">Total Due:</label>
                                            <input type="text"  name="previous_due"  value="{{$supplier->previous_due}}" class="form-control">
                                        </div>
                                    @endif


                                    <div class="form-group">
                                        <label  for="prepare">Amount:</label>
                                        <input type="text" name="payment" value="{{$supplier->paid_amount}}" id="payment" class="form-control">
                                    </div>
                                    @if(isset($supplier->current_advance))
                                        <div class="form-group">
                                            <label  for="prepare">Due Amount:</label>
                                            <input type="text" name="current_advance"  value="{{-$supplier->current_advance}}" id="current_due" class="form-control">
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label  for="prepare">Due Amount:</label>
                                            <input type="text" name="current_due"  value="{{$supplier->current_due}}" id="current_due" class="form-control">
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 text-center">
                                <a href="{{route('payment.index')}}" class="btn btn-danger" type="button">Cancel</a>
                                <button class="btn btn-warning" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
