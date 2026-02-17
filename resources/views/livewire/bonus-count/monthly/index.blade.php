@section('page-title', 'Monthly Bonus Count Add')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2 ">
                <h2>Monthly Bonus Count Add</h2>
                <a href="{{route('bonus.index')}}" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
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

            <div class="row d-flex justify-content-center">
                <div class="collection-form-area">
                    <div class="search-area">
                        <div class="col-lg-12 col-md-12 col-sm-12 py-5">
                            <div class="row customer-search-area">
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <label  class="py-1 border entry-lebel sales_entry_lebel" for="customer_search">Supplier Name:</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="due_supplier_search" placeholder="search supplier" class="form-control">
                                            <option value=""></option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">
                                                    {{$supplier->company_name}} -
                                                    {{$supplier->address}} -
                                                    {{$supplier->mobile}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 com-sm-12">
                                    <div class="supplier-info-area py-2">
                                        @if($supplier_info)
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <p class="border border-dark p-2">Name: {{$supplier_info->company_name }}</p>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <p class="border border-dark p-2 text-wrap">Address: {{  $supplier_info->address }}</p>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <p class="border border-dark p-2">Mobile: {{  $supplier_info->mobile}}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form wire:submit.prevent=store() class="form-horizontal form-label-left sales_entry_form" method="POST">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-1 border entry-lebel sales_entry_lebel" for="10_ton">From - Weight (Ton)</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-1 border entry-lebel sales_entry_lebel" for="20_ton">To - Weight (Ton)</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-1 border entry-lebel sales_entry_lebel" for="mobile">Bonus Rate Tk</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column"></div>
                            </div>
                            @foreach ($monthly_bonus as $i => $bonus)
                                <div class="row" id="row_{{$i}}">
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border entry-lebel sales_entry_lebel d-none" for="from">From:</label>
                                            <input type="text" wire:model="monthly_bonus.{{$i}}.from" value="" class="form-control text-right">

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border entry-lebel sales_entry_lebel d-none" for="to">To:</label>
                                            <input type="text" wire:model="monthly_bonus.{{$i}}.to"  value="" class="form-control text-right">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border entry-lebel sales_entry_lebel d-none" for="rate">Bonus Rate:</label>
                                            <input type="text" wire:model="monthly_bonus.{{$i}}.rate"  value="" class="form-control text-right">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <button type="button" class="btn btn-danger btn-sm" wire:click="deleteARow({{$i}})">Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12">
                                <button type="button" class="btn btn-sm btn-info add-more-row" wire:click="addMoreRow">Add More</button>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 text-center">
                                <a href="{{route('bonus.index')}}" class="btn btn-danger" type="button">Cancel</a>
                                <button class="btn btn-warning" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

    $(document).ready(function () {


        $('#due_supplier_search').select2({
        placeholder: 'Select supplier from here',
        });

        $('#due_supplier_search').on('change', function (e) {
            @this.getSupplier(e.target.value);
        });

    });

    </script>

@endpush
