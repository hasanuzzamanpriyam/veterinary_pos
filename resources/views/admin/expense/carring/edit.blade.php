@extends('layouts.admin')

@section('page-title')
Carring Expense Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Carring Expense</h2>
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
            <form action="{{route('carring.expense.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align">Date<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input id="date" name="date" class="date-picker form-control" value="{{$carring_expense->date}}" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>

                                <input type="hidden" name="id" id="id" value="{{$carring_expense->id}}">
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="voucher_no">Voucher No<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="voucher_no" name="voucher_no"  value="{{$carring_expense->voucher_no}}" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="gary_number">Gary Number
                                <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="gary_number" name="gary_number" value="{{$carring_expense->gary_number}}" class="form-control" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="load_point">Load Point
                              <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="load_point" name="load_point" value="{{$carring_expense->load_point}}" class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="delivery_point">Delivary Point
                              <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="delivery_point" name="delivery_point" value="{{$carring_expense->delivery_point}}" class="form-control" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="driver_name">Driver Name
                              <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="driver_name" name="driver_name" value="{{$carring_expense->driver_name}}" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="payment_amount">Payment Amount<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="payment_amount" name="payment_amount"  value="{{$carring_expense->payment_amount}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="remarks"> Remarks
                               <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$carring_expense->remarks}}</textarea>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('carring.expense.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
