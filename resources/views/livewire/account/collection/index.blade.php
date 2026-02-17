@section('page-title', 'Add Collection')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Add Collection</h2>
                <a href="{{ route('collection.index') }}" class="mr-auto ml-3 cursor-pointer"><i
                        class="fa fa-close"></i></a>
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
            <form wire:submit.prevent="storeCollection()" enctype="multipart/form-data" id="demo-form2"
                data-parsley-validate class="form-horizontal form-label-left collection_from">
                @csrf
                <div class="row mb-4">
                    <div class="col-lg-12 col-md-12 col-sm-6">
                        <div class="row justify-content-end">
                            <div
                                class="search-area col-lg-12 col-md-12 col-sm-12 text-left purchase_return_entry_supplier_col">
                                <div wire:ignore class="row">
                                    <div class="col-md-3">
                                        <label class="py-1 border entry-lebel collection_entry_lebel"
                                            for="customer">Customer</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="customer_search" class="d-block py-1 border" class="form-control"
                                            id="collection-customer-search">
                                            <option value="">Select Customer</option>
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


                <div class="row collection-form-area d-flex justify-content-center">
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Customer Name</label>
                            <input type="text" name="customer_name" wire:model="customer_name" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Address</label>
                            <input type="text" name="address" wire:model="address" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Mobile</label>
                            <input type="text" name="mobile" wire:model="mobile" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Total Due</label>
                            <input type="text" name="balance" wire:model="balance" value=""
                                class="form-control text-right">
                        </div>
                    </div>
                </div>
                <div class="row collection-form-area d-flex justify-content-center">
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group ">
                            <label class="border py-1 collection_entry_lebel" for="date">Date</label>
                            <div class="input-group date" id="datepicker33">
                                <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="prepare">Received By</label>
                            <select type="text" wire:model="payment_by"
                                wire:change="paymentSearch($event.target.value)" name="payment_by"
                                class="form-control">
                                <option value="">Select Option</option>
                                @foreach ($gateways as $gateway)
                                <option value="{{ $gateway->name }}">{{ $gateway->name }}</option>
                                @endforeach
                            </select>

                            @if (isset($bank_list))
                                @if ($bank_list == 1)
                                    <select type="text" wire:model="bank_title" name="bank_title" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->title }}">{{ $bank->title }}</option>
                                        @endforeach
                                    </select>
                                @elseif($bank_list == 2)
                                    <input type="text" wire:model="bank_title" placeholder="Type cheque no" class="form-control">
                                @else
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="prepare">Remarks</label>
                            <input type="text" name="received_by" wire:model="received_by" id="transport_no"
                                class="form-control">
                        </div>

                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="prepare">Amount</label>
                            <input type="text" name="payment" id="payment" wire:keyup="dueCollection('due_collection', $event.target.value)"
                                class="form-control text-right">
                        </div>
                    </div>
                </div>
                <div class="row collection-form-area d-flex justify-content-center">
                    <div class="col-lg-3 col-md-2 col-sm-12"></div>
                    <div class="col-lg-4 col-md-2 col-sm-12"></div>
                    <div class="col-lg-3 col-md-2 col-sm-12"></div>
                    <div class="col-lg-2 col-md-2 col-sm-12">

                        <div class="form-group">
                            <label for="prepare" class="collection_entry_lebel">Due Amount</label>
                            <input type="text" name="current_due" readonly value="{{ $current_due }}"
                                id="current_due" class="form-control text-right">
                        </div>

                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{ route('collection.index') }}" class="btn btn-danger" type="button">Cancel</a>
                        <button class="btn btn-warning " type="reset"
                            onClick="window.location.reload()">Reset</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#collection-customer-search').select2({
                placeholder: 'Select customer from here',
            });

            $('#collection-customer-search').on('change', function(e) {
                @this.searchCustomer(e.target.value);
            });

            $('#datepicker33').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#datepicker33 input[name=date]').on('change', function(e) {
                @this.set('date', e.target.value);
            });

        });
    </script>
@endpush
