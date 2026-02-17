@section('page-title', 'Add New Customer')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Add New Customer</h2>
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
            <form wire:submit.prevent="sessionCreate" enctype="multipart/form-data"
                id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="name">Customer Name
                                <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="name" wire:model="name" name="name" class="form-control ">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="company_name">Company
                                Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="company_name" wire:model="company_name" name="company_name" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="father_name">Father
                                Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="father_name" wire:model="father_name" name="father_name" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="last-name">Address<span
                                    class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="address" id="address" wire:model="address" cols="10" rows="1" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="nid">NID
                                Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="nid" wire:model="nid" name="nid" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel">Date Of Birth <span
                                    class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <div class="input-group date" id="datepicker33">
                                    <input name="birthday" type="text" wire:model="birthday" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="mobile">Mobile
                                Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="mobile" wire:model="mobile" name="mobile" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="phone">Phone
                                Number<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="phone" wire:model="phone" name="phone" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group customer_photo_group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="photo">Customer
                                Photo <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="file" id="photo" wire:model="photo" name="photo"
                                    class="dropify form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="email">Email<span
                                    class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="email" id="email" wire:model="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="ledger_page">Ledger
                                Page<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="ledger_page" wire:model="ledger_page" name="ledger_page" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="price_group">Price
                                Group<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select name="price_group" id="price_group" wire:model="price_group" class="form-control">
                                    @foreach ($price_groups as $price_group)
                                        <option value="{{ $price_group->id }}" {{ ($price_group->name == 'General Rate') ? 'selected' : ''}}>{{ $price_group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="security">Customer Type<span
                                    class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">

                                <select name="type" id="type" wire:model="type" class="form-control">
                                    @foreach ($customer_types as $customer_type => $type)
                                        <option value="{{ $type->name }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel"
                                for="security">Security<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="security" wire:model="security" name="security" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel" for="credit_limit">Credit
                                Limit<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="credit_limit" wire:model="credit_limit" name="credit_limit"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel"
                                for="advance_payment">Adv. Collection<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="advance_payment" wire:model="advance_payment" name="advance_payment"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel"
                                for="previous_due">Previous Due<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="previous_due" wire:model="previous_due" name="previous_due"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_customer_lebel">Starting Date<span
                                    class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <div class="input-group date" id="starting_date">
                                    <input name="starting_date" type="text" wire:model="starting_date" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="guarantor_info pt-4">
                    <div class="clearfix"></div>
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel">
                            <div class="d-flex align-items-center gap-3">
                                <h2 class="bg-primary text-white p-2 rounded">Add Guarantor</h2>
                                <a class="panel-heading bg-primary text-white rounded" role="tab" id="headingOne" data-toggle="collapse"
                                    data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-6">
                                            <div class="row justify-content-end">
                                                <div
                                                    class="search-area col-lg-12 col-md-12 col-sm-12 text-left pb-3">
                                                    <div wire:ignore class="row">
                                                        <div class="col-md-6">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="col-md-12">
                                                                <select class="d-block py-1 border w-100" class="form-control"
                                                                    id="ex-customer-search">
                                                                    <option value="">Select</option>
                                                                    @foreach ($customers as $customer)
                                                                        <option value="{{ $customer->id }}">
                                                                            {{ $customer->name }} -
                                                                            {{ $customer->address }} -
                                                                            {{ $customer->mobile }}
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
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_name">Name<span
                                                        class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" id="guarantor_name" wire:model="guarantor_name"
                                                        name="guarantor_name" class="form-control">
                                                </div>
                                            </div>
                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_company_name">Company Name<span
                                                        class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" id="guarantor_company_name" wire:model="guarantor_company_name"
                                                        name="guarantor_company_name" class="form-control">
                                                </div>
                                            </div>
                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_father_name">Father Name <span
                                                        class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" id="guarantor_father_name" wire:model="guarantor_father_name"
                                                        name="guarantor_father_name" class="form-control">
                                                </div>
                                            </div>
                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_address">Address<span class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" name="guarantor_address" id="guarantor_address" wire:model="guarantor_address" class="form-control">
                                                </div>
                                            </div>
                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_nid">NID Number<span class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="number" id="guarantor_nid" wire:model="guarantor_nid" name="guarantor_nid"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel">Date Of
                                                    Birth <span class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">

                                                    <div class="input-group date" id="guarantor_datepicker">
                                                        <input name="guarantor_birthday" type="text" wire:model="guarantor_birthday" class="form-control guarantor_birthday" placeholder="dd-mm-yyyy">
                                                        <div class="input-group-addon">
                                                            <span class="glyphicon glyphicon-th"></span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_mobile">Mobile Number<span
                                                        class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" id="guarantor_mobile" wire:model="guarantor_mobile"
                                                        name="guarantor_mobile" class="form-control">
                                                </div>
                                            </div>

                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_phone">Phone Number<span class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" id="guarantor_phone" wire:model="guarantor_phone"
                                                        name="guarantor_phone" class="form-control">
                                                </div>
                                            </div>

                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_email">Email<span class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="email" id="guarantor_email" wire:model="guarantor_email"
                                                        name="guarantor_email" class="form-control">
                                                </div>
                                            </div>

                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_security">Security<span class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" id="guarantor_security" wire:model="guarantor_security"
                                                        name="guarantor_security" class="form-control">
                                                </div>
                                            </div>

                                            <div class="item form-group ">
                                                <label class="col-form-label col-md-4 col-sm-4 label-align add_guarantor_lebel"
                                                    for="guarantor_remarks">Remarks<span class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="text" id="guarantor_remarks" wire:model="guarantor_remarks"
                                                        name="guarantor_remarks" class="form-control">
                                                </div>
                                            </div>
                                            <div class="item form-group guarantor_photo_group">
                                                <label
                                                    class="col-form-label col-md-4 col-sm-4 label-align guarantor_photo add_guarantor_lebel"
                                                    for="guarantor_photo">Guarantor Photo <span
                                                        class=""></span>
                                                </label>
                                                <div class="col-md-8 col-sm-8">
                                                    <input type="file" id="guarantor_photo" wire:model="guarantor_photo"
                                                        name="guarantor_photo" class="dropify form-control">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of accordion -->
                </div>

                {{-- <div class="ln_solid"></div> --}}
                <div class="item form-group">
                    <div class="col-md-6 col-sm-12  text-center">
                        <button class="btn btn-danger" type="button" wire:click.prevent="cancel">Cancel</button>
                        <button class="btn btn-warning" type="reset" wire:click.prevent="clear">Reset</button>
                        <button type="submit" class="btn btn-success">Checkout</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dropify.min.css') }}" />
@endpush

@push('scripts')
<script src="{{ asset('assets/js/dropify.min.js') }}"></script>
<script type="text/javascript">
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop',
            'remove': 'Remove',
        }
    });
</script>
<script>
    $(document).ready(function() {
        @this.set('type', $('#type').val());
        @this.set('price_group', $('#price_group').val());
        $('#ex-customer-search').select2();
        $('#ex-customer-search').on('change', function(e) {
            var itemId = $('#ex-customer-search').select2("val");
            if( itemId ) {
                $.ajax({
                    url: "{{ route('customer.search', '') }}/" + encodeURIComponent(itemId),
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);

                        $('#guarantor_name').val(data.name).trigger('change');
                        $('#guarantor_mobile').val(data.mobile).trigger('change');
                        $('#guarantor_phone').val(data.phone).trigger('change');
                        $('#guarantor_company_name').val(data.company_name).trigger('change');
                        $('#guarantor_father_name').val(data.father_name).trigger('change');
                        $('#guarantor_address').val(data.address).trigger('change');
                        $('#guarantor_nid').val(data.nid).trigger('change');
                        $('.guarantor_birthday').val(data.birthday).trigger('change');
                        $('#guarantor_email').val(data.email).trigger('change');
                        $('#guarantor_security').val(data.security).trigger('change');
                        $('#guarantor_remarks').val(data.remarks).trigger('change');
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

        $('#datepicker33').on('change', function(e) {
            @this.set('birthday', e.target.value);
        })
        $('#starting_date').on('change', function(e) {
            @this.set('starting_date', e.target.value);
        })

        $('#guarantor_datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#guarantor_name').on('change', function(e) {
            @this.set('guarantor_name', e.target.value);
        });
        $('#guarantor_mobile').on('change', function(e) {
            @this.set('guarantor_mobile', e.target.value);
        });
        $('#guarantor_phone').on('change', function(e) {
            @this.set('guarantor_phone', e.target.value);
        });
        $('#guarantor_company_name').on('change', function(e) {
            @this.set('guarantor_company_name', e.target.value);
        });
        $('#guarantor_father_name').on('change', function(e) {
            @this.set('guarantor_father_name', e.target.value);
        });
        $('#guarantor_address').on('change', function(e) {
            @this.set('guarantor_address', e.target.value);
        });
        $('#guarantor_nid').on('change', function(e) {
            @this.set('guarantor_nid', e.target.value);
        });
        $('.guarantor_birthday').on('change', function(e) {
            @this.set('guarantor_birthday', e.target.value);
        });
        $('#guarantor_email').on('change', function(e) {
            @this.set('guarantor_email', e.target.value);
        });
        $('#guarantor_security').on('change', function(e) {
            @this.set('guarantor_security', e.target.value);
        });
        $('#guarantor_remarks').on('change', function(e) {
            @this.set('guarantor_remarks', e.target.value);
        });
    });
</script>
@endpush
