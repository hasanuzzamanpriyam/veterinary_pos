@section('page-title', 'Add Cash Offer')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex justify-content-start  gap-1">
                <h2 class="mr-2">Add Cash Offer</h2>
                {{-- <a class="close-link add-payment-close" href="{{ route('payment.index') }}"><i class="fa fa-close"></i></a> --}}
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
            <form wire:submit.prevent="store()"  enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row mb-4">
                    <div class="col-lg-12 col-md-12 col-sm-6">
                        <div class="row justify-content-end">
                            <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left purchase_return_entry_supplier_col">
                                {{-- start supplier select area --}}
                                <div wire:ignore class="row">
                                    <div class="col-md-3">
                                        <label class="py-1 px-2 border entry-lebel add_payment_lebel" for="payment-supplier-search">Supplier/Company</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="d-block py-1 px-2 border" class="form-control"
                                            id="payment-supplier-search">
                                            <option value="">Select Supplier/Company</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">
                                                    {{ $supplier->company_name }} -
                                                    {{ $supplier->address }} -
                                                    {{ $supplier->mobile }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>{{-- end supplier select area --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="d-block border py-1 px-2 add_payment_lebel" for="supplier_name">Supplier Name</label>
                            <input type="text" class="form-control" wire:model="supplier_name">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="d-block border py-1 px-2 add_payment_lebel" for="supplier_name">Address</label>
                            <input type="text" name="address" class="form-control" wire:model="address">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="d-block border py-1 px-2 add_payment_lebel" for="supplier_name">Mobile</label>
                            <input type="text" wire:model="mobile" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group ">
                            <label class="d-block border py-1 px-2 add_payment_lebel" class="" for="date">Date</label>
                            <div class="input-group date" id="datepicker33">

                                <input name="date"  wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="d-block border py-1 px-2 add_payment_lebel" for="description">Description</label>
                            <input type="text" wire:model="description" id="description" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="d-block border py-1 px-2 add_payment_lebel" for="amount">Amount</label>
                            <input type="text" wire:keyup="updateAmount($event.target.value)" id="amount" class="form-control text-right">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 text-center">
                                <a href="{{route('cash.offer.list')}}" class="btn btn-danger" type="button">Cancel</a>
                                <button class="btn btn-warning" type="reset" onClick="window.location.reload()">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>

    $(document).ready(function () {
       $('#payment-supplier-search').select2({
        placeholder: 'Select Supplier from here',
       });
       $('#payment-supplier-search').on('change', function (e) {
            @this.searchSupplier(e.target.value);
       });

       $('#datepicker33').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#datepicker33 input[name=date]').on('change', function (e) {
            @this.set('date', e.target.value);
        });
    });
    </script>

@endpush
