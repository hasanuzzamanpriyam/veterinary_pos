<div class="col-md-12 col-sm-12">
    <div class="x_panel v">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Customer Dues</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content pt-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form wire:submit.prevent=customerDueSearch() class="px-3">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="date-area">
                                    <label class="d-block p-2 border" for="supplier_search">Date</label>
                                    <div class="form-group m-0">
                                        <input type="date" name="date" wire:model="date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="customer-search-area">
                                    <label class="d-block p-2 border" for="customer_search">Customer Name</label>
                                    <div class="form-group m-0" wire:ignore>
                                        <select type="search" id="due_customer_search" name="get_customer_id"
                                            placeholder="search customer" class="form-control">
                                            <option value=""></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}">
                                                    {{$customer->name}} -
                                                    {{$customer->address}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 d-flex align-items-end">
                                <div class="customer-search-button">
                                    <div class="form-group m-0">
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <button type="button" wire:click="resetCustomer"
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
                        @if(isset($due_customer) || isset($last_due))
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th>Company Name</th>
                                        <th>Address</th>
                                        <th>Mobile No.</th>
                                        <th>Due Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($last_due)
                                        <tr>
                                            <td>{{date('d-m-Y', strtotime($last_due->date))}}</td>
                                            <td>{{$last_due->customer->id}}</td>
                                            <td>{{$last_due->customer->name}}</td>
                                            <td>{{$last_due->customer->company_name ?? ""}}</td>
                                            <td>{{$last_due->customer->address ?? ""}}</td>
                                            <td>{{$last_due->customer->mobile ?? ""}}</td>
                                            <td class="text-right">{{$last_due->balance}}/=</td>
                                        </tr>
                                    @elseif($due_customer)
                                        @forelse ($due_customer as $customer)
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($customer->date))}}</td>
                                                <td>{{$customer->customer->id}}</td>
                                                <td>{{$customer->customer->name}}</td>
                                                <td>{{$customer->customer->company_name ?? ""}}</td>
                                                <td>{{$customer->customer->address ?? ""}}</td>
                                                <td>{{$customer->customer->mobile ?? ""}}</td>
                                                <td class="text-right">{{$customer->balance}}/=</td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="7">No Data Found!</td>
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


@push('scripts')
    <script>

        $(document).ready(function () {


            $('#due_customer_search').select2({
                placeholder: 'Select customer from here',
            });

            $('#due_customer_search').on('change', function (e) {
                //@this.searchCustomer(e.target.value);
                var data = $('#due_customer_search').select2("val");
                @this.set('get_customer_id', data);
            });


        });

    </script>

@endpush