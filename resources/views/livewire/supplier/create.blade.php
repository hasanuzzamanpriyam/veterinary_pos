@section('page-title', 'Supplier Add')

<div class="col-md-12 col-sm-12 ">
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title p-3">
                    <div class="header-title d-flex align-items-center gap-2">
                        <h2>Add New Supplier</h2>
                        <a href="{{route('supplier.index')}}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
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
                    <form wire:submit.prevent="sessionCreate()" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                        @csrf
                        <div class="row jusitfy-content-center">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="company_name">Company Name <span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="company_name" wire:model="company_name" name="company_name"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="owner_name">Owner Name <span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="owner_name" wire:model="owner_name" name="owner_name"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="officer_name">Officer Name<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="officer_name" wire:model="officer_name" name="officer_name"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="last-name">Address<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <textarea type="text" name="address" wire:model="address" id="address" cols="10" rows="1"  class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="mobile">Mobile Number<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="mobile" wire:model="mobile" name="mobile"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="phone">Officer Number<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="phone" wire:model="phone" name="phone"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="email">Email<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="email" id="email" wire:model="email" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="photo">Supplier Photo <span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="file" id="photo" wire:model="photo" name="photo"  class="dropify form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="ledger_page">Ledger Page<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="ledger_page" wire:model="ledger_page" name="ledger_page"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="dealer_code">Dealer Code<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="dealer_code" wire:model="dealer_code" name="dealer_code"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="dealer_area">Dealer Area<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="dealer_area" wire:model="dealer_area" name="dealer_area"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="security">Security<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" id="security" wire:model="security" name="security"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="credit_limit">Credit Limit<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="number" id="credit_limit" wire:model="credit_limit" name="credit_limit"  class="form-control">
                                    </div>
                                </div>

                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="advance_payment">Adv.Payment<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="number" id="advance_payment" wire:model="advance_payment" name="advance_payment"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel" for="previous_due">Previous Due<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="number" id="previous_due" wire:model="previous_due" name="previous_due"  class="form-control">
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Condition<span class=""></span>
                                    </label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="text" class="form-control" name="condition" wire:model="condition" />
                                    </div>
                                </div>
                                <div class="item form-group ">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Starting Date<span
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

                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-12 text-center">
                                <a href="{{route('supplier.index')}}" class="btn btn-danger" type="button">Cancel</a>
                                <button class="btn btn-warning" type="reset" wire:click.prevent="clear">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@push('styles')
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />
@endpush

@push('scripts')
<script src="{{asset('assets/js/dropify.min.js')}}"></script>
    <script type="text/javascript">

    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop',
            'remove':  'Remove',
        }
    });

    $('#starting_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#starting_date').on('change', function(e) {
        @this.set('starting_date', e.target.value);
    })


    </script>
@endpush
