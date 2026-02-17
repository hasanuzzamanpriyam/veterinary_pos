<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Show Supplier Due</h2>
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
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 offset-2">
                        <form wire:submit.prevent=supplierDueSearch()>
                            <div class="row align-item-center">
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="supplier-search-area">
                                        <label class="d-block py-1 border" for="date">Date:</label>
                                        <div class="form-group">
                                            <input type="date" name="date" wire:model="date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="customer-search-area">
                                        <label class="d-block py-1 border" for="customer_search">Supplier Name:</label>
                                        <div class="form-group" wire:ignore>
                                            <select type="search" id="due_supplier_search" name="get_supplier_id"
                                                placeholder="search supplier" class="form-control">
                                                <option value=""></option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{$supplier->id}}">
                                                        {{$supplier->company_name}} -
                                                        {{$supplier->address}}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="customer-search-button pt-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Search</button>
                                            <button type="button" wire:click="resetSupplier"
                                                class="btn btn-danger">Reset</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4">
                            @if(isset($due_supplier) || isset($last_due))
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Supplier ID</th>
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th>Mobile No.</th>
                                            <th>Total Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($last_due)
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($last_due->date))}}</td>
                                                <td>{{$last_due->supplier->id}}</td>
                                                <td>{{$last_due->supplier->company_name ?? ""}}</td>
                                                <td>{{$last_due->supplier->address ?? ""}}</td>
                                                <td>{{$last_due->supplier->mobile ?? ""}}</td>
                                                <td class="text-right">{{$last_due->balance}}/=</td>
                                            </tr>
                                        @elseif($due_supplier)
                                            @forelse ($due_supplier as $supplier)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($supplier->date))}}</td>
                                                    <td>{{$supplier->supplier->id}}</td>
                                                    <td>{{$supplier->supplier->company_name ?? ""}}</td>
                                                    <td>{{$supplier->supplier->address ?? ""}}</td>
                                                    <td>{{$supplier->supplier->mobile ?? ""}}</td>
                                                    <td class="text-right">{{$supplier->balance}}/=</td>
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="5">No Data Found!</td>
                                                </tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            @else
                            @endif
                        </div>
                    </div>
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
                //@this.searchCustomer(e.target.value);
                var data = $('#due_supplier_search').select2("val");
                @this.set('get_supplier_id', data);
            });

        });

    </script>

@endpush