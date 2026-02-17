@extends('layouts.admin')

@section('page-title')
Customer Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Customer</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
        </div>

        <div class="x_content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('customer.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="name">Customer Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="name" name="name" value="{{$customer->name}}"  class="form-control ">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="company_name">Company Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="company_name" name="company_name" value="{{$customer->company_name}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="father_name">Father Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="father_name" name="father_name" value="{{$customer->father_name}}"  class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="last-name">Address<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="address" id="address" cols="10" rows="1"  class="form-control">{{$customer->address}} </textarea>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="nid">NID Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="nid" name="nid" value="{{$customer->nid}}"  class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align">Date Of Birth <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">


                                <div class="input-group date" id="datepicker33">
                                    <input name="birthday" type="text" class="form-control" value="{{ date('d-m-Y',strtotime($customer->birthday))}}" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="mobile">Mobile Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="mobile" name="mobile" value="{{$customer->mobile}}"  class="form-control">
                            </div>
                        </div>

                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="phone">Phone Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="phone" name="phone"  value="{{$customer->phone}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="photo">Customer Photo
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="file" id="photo" name="photo"  class="dropify form-control">
                                @if ($customer->photo)
                                    <img src="{{asset($customer->photo)}}" alt="Photo" width="30px" height="40px">
                                @endif
                                <input type="hidden" id="old_photo" name="old_photo" value="{{$customer->photo}}">
                                <input type="hidden" id="id" name="id" value="{{$customer->id}}">

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="email">Email<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="email" id="email" name="email" value="{{$customer->email}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="ledger_page">Ledger Page<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="ledger_page" name="ledger_page" value="{{$customer->ledger_page}}"  class="form-control">
                            </div>
                        </div>

                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="price_group">Price Group<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                              <select name="price_group" id="price_group"  class="form-control">
                                @foreach ($price_groups as $price_group)
                                    <option value="{{$price_group->id}}" @if($price_group->id == $customer->price_group_id) selected="" @endif>{{$price_group->name}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="security">Type<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select name="type" id="type" class="form-control">
                                    @foreach ($customer_types as $type)
                                        <option value="{{$type->name}}" @if($type->name == $customer->type) selected @endif>{{$type->name}}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="security">Security<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="security" name="security" value="{{$customer->security}}"  class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="credit_limit">Credit Limit<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="credit_limit" name="credit_limit" value="{{$customer->credit_limit}}"  class="form-control">
                            </div>
                        </div>


                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="advance_payment">Adv. Payment<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="advance_payment" name="advance_payment" value="{{$customer->balance < 0 ? -$customer->balance : 0}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="previous_due">Previous Due<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="previous_due" name="previous_due" value="{{$customer->balance > 0 ? $customer->balance : 0}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align">Starting Date <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">


                                <div class="input-group date" id="starting_date">
                                    <input name="starting_date" type="text" class="form-control" value="{{ date('d-m-Y',strtotime($customer->starting_date))}}" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="guarantor_info mt-4">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-6">
                            <div class="row justify-content-end">
                                <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left pb-3">
                                    <div wire:ignore class="row">
                                        <div class="col-md-6">
                                            <h2 class="panel-title text-dark">Guarantor Information</h2>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                @php($old_customers = DB::table('customers')->get())
                                                <select class="d-block py-1 border w-100" class="form-control"
                                                    id="ex-customer-search">
                                                    <option value="">Select Guarantor</option>
                                                    @foreach ($old_customers as $old_customer)
                                                    <option value="{{ $old_customer->id }}">
                                                        {{ $old_customer->name }} -
                                                        {{ $old_customer->address }} -
                                                        {{ $old_customer->mobile }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_name">Guarantor Name<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="guarantor_name" name="guarantor_name"
                                        value="{{$customer->guarantor_name}}" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_company_name">Company Name<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="guarantor_company_name" name="guarantor_company_name"
                                        value="{{$customer->guarantor_company_name}}" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_father_name">Father Name <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="guarantor_father_name" name="guarantor_father_name"
                                        value="{{$customer->guarantor_father_name}}" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_address">Address<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <textarea type="text" name="guarantor_address" id="guarantor_address" cols="10"
                                        rows="1" class="form-control"> {{$customer->guarantor_address}} </textarea>
                                </div>
                            </div>
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="guarantor_nid">NID
                                    Number<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="number" id="guarantor_nid" name="guarantor_nid"
                                        value="{{$customer->guarantor_nid}}" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align">Date Of Birth <span
                                        class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <div class="input-group date" id="guarantor_datepicker">
                                        <input name="guarantor_birthday" type="text"
                                            class="form-control guarantor_birthday"
                                            value="{{date('d-m-Y',strtotime($customer->guarantor_birthday))}}"
                                            placeholder="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_mobile">Mobile Number<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="guarantor_mobile" name="guarantor_mobile"
                                        value="{{$customer->guarantor_mobile}}" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="guarantor_phone">Phone
                                    Number<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="guarantor_phone" name="guarantor_phone"
                                        value="{{$customer->guarantor_phone}}" class="form-control">
                                </div>
                            </div>

                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_email">Email<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="email" id="guarantor_email" name="guarantor_email"
                                        value="{{$customer->guarantor_email}}" class="form-control">
                                </div>
                            </div>

                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_security">Security<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="guarantor_security" name="guarantor_security"
                                        value="{{$customer->guarantor_security}}" class="form-control">
                                </div>
                            </div>

                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_remarks">Remarks<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <textarea type="text" name="guarantor_remarks" id="guarantor_remarks" cols="10"
                                        rows="1" class="form-control">{{$customer->guarantor_remarks}}</textarea>
                                </div>
                            </div>
                            <div class="item form-group ">
                                <label class="col-form-label col-md-4 col-sm-4 label-align"
                                    for="guarantor_photo">Guarantor Photo <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="file" id="guarantor_photo" name="guarantor_photo"
                                        class="dropify form-control">
                                    @if ($customer->guarantor_photo)
                                    <img src="{{asset($customer->guarantor_photo)}}" alt="Photo" width="30px"
                                        height="40px">
                                    @endif
                                    <input type="hidden" id="guarantor_old_photo" name="guarantor_old_photo"
                                        value="{{$customer->guarantor_photo}}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                </div>
                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12  text-center">
                        <a href="{{route('customer.index')}}" class="btn btn-danger" type="button">Cancel</a>
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
<script>
        $(document).ready(function() {
            $('#ex-customer-search').select2();
            $('#ex-customer-search').on('change', function(e) {
                var itemId = $('#ex-customer-search').select2("val");
                if( itemId ) {
                    $.ajax({
                        url: "{{ route('customer.search', '') }}/" + encodeURIComponent(itemId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#guarantor_name').val(data.name);
                            $('#guarantor_mobile').val(data.mobile);
                            $('#guarantor_phone').val(data.phone);
                            $('#guarantor_company_name').val(data.company_name);
                            $('#guarantor_father_name').val(data.father_name);
                            $('#guarantor_address').val(data.address);
                            $('#guarantor_nid').val(data.nid);
                            $('#guarantor_birthday').val(data.birthday);
                            $('#guarantor_email').val(data.email);
                            $('#guarantor_security').val(data.security);
                            $('#guarantor_remarks').val(data.remarks);
                        }
                    });
                }
            });


            $('#datepicker33').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
            $('#starting_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#guarantor_datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>

@endsection
