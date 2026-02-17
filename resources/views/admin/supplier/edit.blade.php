@extends('layouts.admin')

@section('page-title')
Supplier Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2>Update Supplier</h2>
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
            <form action="{{route('supplier.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="company_name">Company Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="company_name" name="company_name" value="{{$supplier->company_name}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="owner_name">Owner Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="owner_name" name="owner_name"  value="{{$supplier->owner_name}}"class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="officer_name">Officer Name<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="officer_name" name="officer_name"  value="{{$supplier->officer_name}}"class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="last-name">Address<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="address" id="address" cols="10" rows="1"  class="form-control">{{$supplier->address}}</textarea>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="mobile">Mobile Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="mobile" name="mobile"  value="{{$supplier->mobile}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="phone">Officer Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="phone" name="phone"  value="{{$supplier->phone}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="email">Email<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="email" id="email" name="email" value="{{$supplier->email}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="photo">Supplier Photo <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="file" id="photo" name="photo"  class="dropify form-control">
                                @if ($supplier->photo)
                                    <img src="{{asset($supplier->photo)}}" alt="Photo" width="30px" height="40px">
                                @endif
                                <input type="hidden" id="old_photo" name="old_photo" value="{{$supplier->photo}}">
                                <input type="hidden" id="id" name="id" value="{{$supplier->id}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="ledger_page">Ledger Page<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="ledger_page" name="ledger_page" value="{{$supplier->ledger_page}}"  class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="dealer_code">Dealer Code<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="dealer_code" name="dealer_code"  value="{{$supplier->dealer_code}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="dealer_area">Dealer Area<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="dealer_area" name="dealer_area" value="{{$supplier->dealer_area}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="security">Security<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="security" name="security" value="{{$supplier->security}}"  class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="credit_limit">Credit Limit<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="credit_limit" name="credit_limit" value="{{$supplier->credit_limit}}"  class="form-control">
                            </div>
                        </div>

                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="advance_payment">Adv. Payment<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="advance_payment" name="advance_payment" value="{{$supplier->balance < 0 ? -$supplier->balance : 0}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="previous_due">Previous Due<span class=""></span>
                            </label>

                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="previous_due" name="previous_due" value="{{$supplier->balance > 0 ? $supplier->balance : 0}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Condition<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" class="form-control" name="condition" value="{{$supplier->condition}}" />
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Starting Date<span
                                    class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <div class="input-group date" id="starting_date">
                                    <input name="starting_date" type="text" class="form-control" placeholder="dd-mm-yyyy" value="{{ date('d-m-Y',strtotime($supplier->starting_date))}}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-6 col-sm-12 text-center">
                        <a href="{{route('supplier.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- image plugin js--}}
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/dropify.min.js')}}"></script>
        <script type="text/javascript">

        $('.dropify').dropify({
                    messages: {
                        'default': 'Drag and drop',
                        'remove':  'Remove',
                    }
                });
        </script>

@endsection
@push('scripts')
    <script type="text/javascript">


    $('#starting_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#starting_date').on('change', function(e) {

    })


    </script>
@endpush
