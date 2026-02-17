@extends('layouts.admin')

@section('page-title')
Collection Update
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2>Update Collection</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
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
            <form action="#" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left update_form_area">
                @csrf
                <input type="hidden"  name="customer_id"  value="{{$customer_collection->customer_id}}">
                <input type="hidden"  name="id"  value="{{$customer_collection->id}}">
                <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="row">
                        <div class="collection-form-area d-flex justify-content-center">


                            <div class="col-lg-3 col-md-3 col-sm-12">

                                <div class="form-group ">

                             {{-- {{ dump($customer_collection)}} --}}

                                    <label class="" for="date">Date:</label>
                                    <div class="input-group date" id="datepicker33">
                                        <input name="date" type="text" class="form-control" value="{{ date('d-m-Y',strtotime($customer_collection->date) )}}" placeholder="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <label for="supplier_name">Invoice No:</label>
                                    <input type="text" readonly  name="invoice_no"  value="{{$customer_collection->id}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="supplier_name">Customer Name:</label>
                                    <input type="text"  name="customer_name"  value="{{$customer_collection->customer->name}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label  for="prepare">Paying By:</label>
                                    <select type="text" name="payment_by"  class="form-control">
                                        <option value="">Select Option</option>
                                            <!-- @foreach($payment_types as $payment_type) -->
                                                <option value=" {{ $customer_collection->payment_by}}" selected="selected"> {{$customer_collection->payment_by}}</option>
                                            <!-- @endforeach -->
                                    </select>
                                </div>

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="supplier_name">Address:</label>
                                    <input type="text"  name="address" value="{{$customer_collection->customer->address}}" class="form-control">
                                </div>

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label  for="prepare">Remarks:</label>
                                    <textarea type="text" name="remarks" id="transport_no" class="form-control" cols="5" rows="2">{{$customer_collection->remarks}}</textarea>
                                </div>

                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12">

                                    <div class="form-group">
                                        <label for="supplier_name">Total Due:</label>
                                        <input type="text"  name="previous_advance"  value="{{$customer_collection->previous_due}}" class="form-control">
                                    </div>


                                <div class="form-group">
                                    <label  for="prepare">Amount:</label>
                                    <input type="text" name="payment" value="{{$customer_collection->payment}}" id="payment" class="form-control">
                                </div>

                                @if(isset($customer_collection->current_due))
                                    <div class="form-group">
                                        <label  for="prepare">Due Amount:</label>
                                        <input type="text" name="current_advance"  value="{{$customer_collection->current_due}}" id="current_due" class="form-control">
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label  for="prepare">Due Amount:</label>
                                        <input type="text" name="current_due"  value="" id="current_due" class="form-control">
                                    </div>
                                @endif


                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-12 col-sm-12 text-center">
                            <a href="{{route('collection.index')}}" class="btn btn-danger" type="button">Cancel</a>
                            <button class="btn btn-warning" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@push('scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> --}}

<script>

    $(document).ready(function () {

        $('#datepicker33').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });


    });
</script>

@endpush
