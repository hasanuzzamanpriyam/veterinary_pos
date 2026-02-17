@section("page-title", "Employee Payment")

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Create Payment</h2>
                <a href="{{ route('collection.index') }}" class="mr-auto ml-3 cursor-pointer"><i
                        class="fa fa-close"></i></a>
            </div>

        </div>

        <div class="x_content p-3">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="list-group">
                    @foreach ($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger text-center py-2">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form wire:submit.prevent="store()" enctype="multipart/form-data" id="demo-form2"
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
                                            for="employee">Employee</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="employee_search" class="d-block py-1 border" class="form-control"
                                            id="collection-employee-search">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->name }} -
                                                    {{ $employee->address }} -
                                                    {{ $employee->mobile }}
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
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Employee Name</label>
                            <input type="text" value="{{ $employee_info->name ?? '' }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Address</label>
                            <input type="text" value="{{ $employee_info->address ?? '' }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Mobile</label>
                            <input type="text" value="{{ $employee_info->mobile ?? '' }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Balance</label>
                            <input type="text" value="{{ formatAmount($employee_info->balance ?? 0) }}" class="form-control text-right" readonly>
                        </div>
                    </div>
                </div>
                <div class="row collection-form-area d-flex justify-content-center">
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group ">
                            <label class="border py-1 collection_entry_lebel" for="date">Date</label>
                            <input name="date" type="text" class="form-control" placeholder="dd-mm-yyyy" value="{{date('d-m-Y')}}" readonly>
                            {{-- <div class="input-group date" id="invoice_date">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div> --}}
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="prepare">Paying By</label>
                            <select type="text" name="payment_method" wire:model="payment_method"  class="form-control">
                                <option value="">Select Option</option>
                                @foreach($payment_types as $payment_type)
                                    <option value="{{strtolower($payment_type)}}">{{$payment_type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="prepare">Remarks</label>
                            <input type="text" wire:model="remarks" id="transport_no" class="form-control" />
                        </div>

                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="prepare">Amount</label>
                            <input type="text" name="payment" id="payment" wire:keyup="dueCalculation($event.target.value)"
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
                            <label for="prepare" class="collection_entry_lebel">Total Tk</label>
                            <input type="text" name="current_due" readonly value="{{ formatAmount($balance ?? 0) }}" class="form-control text-right">
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
            $('#collection-employee-search').select2({
                placeholder: 'Select employee from here',
            });

            $('#collection-employee-search').on('change', function(e) {
                @this.searchEmployee(e.target.value);
            });

            $('#invoice_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#invoice_date input[name=date]').on('change', function(e) {
                @this.set('date', e.target.value);
            });

        });
    </script>
@endpush
