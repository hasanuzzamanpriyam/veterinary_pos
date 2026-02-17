@extends('layouts.admin')

@section('page-title')
    Due Customer List
@endsection

@section('main-content')
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title p-3">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2 class="mr-auto">Due Customer List</h2>
                </div>
            </div>
            <div class="x_content p-3">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive"
                                class="table table-striped table-bordered dt-responsive nowrap category_list_table"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>

                                        <th class="all">Select</th>
                                        <th class="all">ID</th>
                                        <th class="all customer_name_th">Customer Name</th>
                                        <th class="all customer_address_th">Address</th>
                                        <th class="all customer_mobile_th">Mobile</th>
                                        <th class="all"> Customer Type</th>
                                        <th class="all customer_ledger_th">Ledger</th>
                                        <th class="all">Sales Qty</th>
                                        <th class="all">Total (TK)</th>
                                        <th class="all">Collection</th>
                                        <th class="all">Total Due</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        @php
                                            $total_sales = $ledger->find($customer->id)->total_sales ?? 0;
                                            // $G_total_sales += $total_sales;
                                            $total_return = $ledger->find($customer->id)->total_returns ?? 0;
                                            // $G_total_return += $total_return;
                                            $total_discount = $ledger->find($customer->id)->total_price_discounts ?? 0;
                                            // $G_total_discount += $total_discount;
                                            $total_carring = $ledger->find($customer->id)->total_carring ?? 0;
                                            // $G_total_carring += $total_carring;
                                            $total_others = $ledger->find($customer->id)->total_others ?? 0;
                                            // $G_total_others += $total_others;
                                            $total_collection = $ledger->find($customer->id)->total_collections ?? 0;
                                            // $G_total_collection += $total_collection;
                                            $balance = $customer->balance;

                                            $total_sale_qty = $ledger->find($customer->id)->total_sale_qty ?? 0;
                                            $total_sale_discount_qty = $ledger->find($customer->id)->total_sale_discount_qty ?? 0;
                                            $total_return_qty = $ledger->find($customer->id)->total_return_qty ?? 0;
                                            $total_tk = $total_sales - $total_return - $total_discount + $total_carring + $total_others;
                                        @endphp
                                    <tr>

                                        <!-- <td>{{ $loop->iteration }}</td> -->

                                        <td><input type="checkbox" name="due-customer-select" class="form-control btn btn-primary"></td>
                                        <td>{{ $customer->id }}</td>

                                        <td class="customer_name_td">{{ $customer->name }}</td>
                                        <td class="customer_address_td">{{ $customer->address }}</td>
                                        <td class="customer_mobile_td">{{ $customer->mobile }}</td>
                                        @foreach ($customer_types as $customer_type => $type)
                                        {{-- {{dump($type)}} --}}
                                        @endforeach

                                        <td class="customer_type_td">{{ $customer->type }}</td>

                                        <td class="customer_ledger_td">{{ $customer->ledger_page }}</td>
                                        <td>{{$total_sale_qty - $total_sale_discount_qty - $total_return_qty}}</td>
                                         {{-- Total Sales/Price --}}
                                         <td class="text-right">{{ $total_tk }}/=</td>

                                          {{-- Collection --}}
                                        <td class="text-right">{{ $total_collection }}/=</td>

                                        <td class="text-right">{{$balance}}</td>
                                        <td>
                                            <div class="btn-group btn-group-vertical customer_diplay_list">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{ route('customer.edit', ['id' => $customer->id]) }}"
                                                            class="btn btn-success btn-sm w-20">Edit <i
                                                            class="fa fa-eye"></i></a></li>
                                                    <li><a href="{{ route('customer.view', ['id' => $customer->id]) }}"
                                                            class="btn btn-success btn-sm w-20">View <i
                                                            class="fa fa-eye"></i></a></li>
                                                    <li><a href="{{ route('customer.sales.report1', ['id' => $customer->id]) }}"
                                                            class="btn btn-success btn-sm w-20">Sales 1 <i
                                                            class="fa fa-eye"></i></a></li>
                                                    <li><a href="{{ route('collection.customer.report', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">Collection <i
                                                                class="fa fa-book"></i></a></li>
                                                    <li><a href="{{ route('customer.ledger1', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">Ledger-1 <i
                                                                class="fa fa-book"></i></a></li>
                                                    <li><a href="{{ route('customer.statement1', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">Statement-1 <i
                                                                class="fa fa-tasks"></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
