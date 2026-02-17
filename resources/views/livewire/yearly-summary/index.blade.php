@section('page-title', 'Yearly Summary Report')
@php
    //global variables
    $total_dokan = 0;
    $total_sales = 0;
    $total_purchase = 0;
    $total_collection = 0;
    $total_salary = 0;
    $total_payment = 0;
    $total_bank = 0;
    $total_labour = 0;
    $total_carring = 0;
@endphp


<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">

            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2 class="mr-auto">Yearly Summary Report</h2>
                @if($date)<a href="{{route('monthly.report.pdf', $date)}}" class="btn btn-info btn-link">Download <i
                class="fa fa-file-pdf-o text-danger"></i></a>@endif
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
            <div class="col-lg-12 col-md-12 col-sm-12">
                <form wire:submit.prevent=yearlyReportSearch()>
                    <div class="col-lg-12 col-md-12 col-sm-12 offset-3">
                        <div class="row ">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    {{-- <label class="d-block py-1 border" for="supplier_search">Search by
                                        Year:</label> --}}
                                    <div class="form-group">
                                        <select wire:change="getYear($event.target.value)" type="text"
                                            class="form-control">
                                            <option>Select Year</option>
                                            @for ($i = date('Y'); $i >= (date('Y') - 10); $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="supplier-search-button">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <button type="button" wire:click="resetReport"
                                            class="btn btn-danger">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="daily-summary-sales-report-area mt-4">
                            <div class="x_title">
                                <h4 class="text-center"> <strong>Yearly Sales Report in <span
                                            class="text-dark">{{$year}}</span></strong></h4>
                            </div>
                            @if($sales)
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($sales as $report)
                                            @php
                                                $total_sales += $report->total_price;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$report->customer->name}}</td>
                                                <td class="text-center">{{$report->customer->address}}</td>
                                                <td class="text-center">{{$report->customer->mobile}}</td>
                                                <td class="text-right">{{$report->total_price}}/=</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="4"> No Data Found?</td>
                                            </tr>
                                        @endforelse
                                        <tr class="text-right">
                                            <td colspan="4">Total Amount = {{$total_sales}} /=</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="daily-summary-sales-report-area mt-4">
                            <div class="x_title">
                                <h4 class="text-center"> <strong>Yearly Purchase Report in <span
                                            class="text-dark">{{$year}}</span></strong></h4>
                            </div>
                            @if($purchase)
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($purchase as $report)
                                            @php
                                                $total_purchase += $report->total_price;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$report->supplier->company_name}}</td>
                                                <td class="text-center">{{$report->supplier->address}}</td>
                                                <td class="text-center">{{$report->supplier->mobile}}</td>
                                                <td class="text-right">{{$report->total_price}}/=</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="4"> No Data Found?</td>
                                            </tr>
                                        @endforelse
                                        <tr class="text-right">
                                            <td colspan="4">Total Amount = {{$total_purchase}} /=</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="daily-summary-sales-report-area mt-4">
                            <div class="x_title">
                                <h4 class="text-center"> <strong>Yearly Collection Report in <span
                                            class="text-dark">{{$year}}</span></strong></h4>
                            </div>
                            @if($collection)
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($collection as $report)
                                            @php
                                                $total_collection += $report->payment;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$report->customer->name}}</td>
                                                <td class="text-center">{{$report->customer->address}}</td>
                                                <td class="text-center">{{$report->customer->mobile}}</td>
                                                <td class="text-right">{{$report->payment}}/=</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="4"> No Data Found?</td>
                                            </tr>
                                        @endforelse
                                        <tr class="text-right">
                                            <td colspan="4">Total Amount = {{$total_collection}} /=</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="daily-summary-sales-report-area mt-4">
                            <div class="x_title">
                                <h4 class="text-center"> <strong>Yearly Payment Report in <span
                                            class="text-dark">{{$year}}</span></strong></h4>
                            </div>
                            @if($payment)
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($payment as $report)
                                            @php
                                                $total_payment += $report->payment;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$report->supplier->company_name}}</td>
                                                <td class="text-center">{{$report->supplier->address}}</td>
                                                <td class="text-center">{{$report->supplier->mobile}}</td>
                                                <td class="text-right">{{$report->payment}}/=</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="4"> No Data Found?</td>
                                            </tr>
                                        @endforelse
                                        <tr class="text-right">
                                            <td colspan="4">Total Amount = {{$total_payment}} /=</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="x_title">
                            <h4 class="text-center"> <strong>Yearly Expense Report in <span
                                        class="text-dark">{{$year}}</span></strong></h4>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="daily-summary-sales-report-area mt-4">
                                <div class="x_title">
                                    <h4 class="text-center"> <strong>Yearly Salary Expense Report in <span
                                                class="text-dark">{{$year}}</span></strong></h4>
                                </div>
                                @if($salary)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Employee Name</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>Payment Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($salary as $report)
                                                @php
                                                    $total_salary += $report->payment_amount;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{$report->employee->name}}</td>
                                                    <td class="text-center">{{$report->employee->address}}</td>
                                                    <td class="text-center">{{$report->employee->mobile}}</td>
                                                    <td class="text-right">{{$report->payment_amount}}/=</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="4"> No Data Found?</td>
                                                </tr>
                                            @endforelse
                                            <tr class="text-right">
                                                <td colspan="4">Total Amount = {{$total_salary}} /=</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="daily-summary-sales-report-area mt-4">
                                <div class="x_title">
                                    <h4 class="text-center"> <strong>Yearly Bank Expense Report in <span
                                                class="text-dark">{{$year}}</span></strong></h4>
                                </div>
                                @if($bank)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Bank Name</th>
                                                <th>Payment Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($bank as $report)
                                                @php
                                                    $total_bank += $report->payment_amount;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{$report->bank->name}}</td>
                                                    <td class="text-right">{{$report->payment_amount}}/=</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="2"> No Data Found?</td>
                                                </tr>
                                            @endforelse
                                            <tr class="text-right">
                                                <td colspan="2">Total Amount = {{$total_bank}} /=</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="daily-summary-sales-report-area mt-4">
                                <div class="x_title">
                                    <h4 class="text-center"> <strong>Yearly Labour Expense Report in <span
                                                class="text-dark">{{$year}}</span></strong></h4>
                                </div>
                                @if($labour)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Purpose</th>
                                                <th>Payment Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse($labour as $report)
                                                @php
                                                    $total_labour += $report->payment_amount;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{$report->purpose}}</td>
                                                    <td class="text-right">{{$report->payment_amount}}/=</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="2"> No Data Found?</td>
                                                </tr>
                                            @endforelse
                                            <tr class="text-right">
                                                <td colspan="2">Total Amount = {{$total_labour}} /=</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="daily-summary-sales-report-area mt-4">
                                <div class="x_title">
                                    <h4 class="text-center"> <strong>Yearly Carring Expense Report in <span
                                                class="text-dark">{{$year}}</span></strong></h4>
                                </div>
                                @if($carring)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Driver Name</th>
                                                <th>Gari Number</th>
                                                <th>Payment Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($carring as $report)
                                                @php
                                                    $total_carring += $report->payment_amount;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{$report->purpose}}</td>
                                                    <td class="text-right">{{$report->payment_amount}}/=</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="3"> No Data Found?</td>
                                                </tr>
                                            @endforelse
                                            <tr class="text-right">
                                                <td colspan="3">Total Amount = {{$total_carring}} /=</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="daily-summary-sales-report-area mt-4">
                                <div class="x_title">
                                    <h4 class="text-center"> <strong>Yearly Dokan Expense Report in <span
                                                class="text-dark">{{$year}}</span></strong></h4>
                                </div>
                                @if($dokan)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Dokan Name</th>
                                                <th>Rent Month</th>
                                                <th>Payment Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($dokan as $report)
                                                @php
                                                    $total_dokan += $report->payment_amount;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{$report->dokan_rent}}</td>
                                                    <td class="text-center">{{$report->rent_month}}</td>
                                                    <td class="text-right">{{$report->payment_amount}}/=</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="3"> No Data Found?</td>
                                                </tr>
                                            @endforelse
                                            <tr class="text-right">
                                                <td colspan="3">Total Amount = {{$total_dokan}} /=</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 com-sm-12">
                        <h4 class="text-center text-dark my-3">Total Amount</h4>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Total Sales</th>
                                    <th>Total Purchase</th>
                                    <th>Total Collection</th>
                                    <th>Total Payment</th>
                                    <th>Total Salary</th>
                                    <th>Total Bank</th>
                                    <th>Total Carring</th>
                                    <th>Total Labour</th>
                                    <th>Total Dokan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-right">{{$total_sales}}/=</td>
                                    <td class="text-right">{{$total_purchase}}/=</td>
                                    <td class="text-right">{{$total_collection}}/=</td>
                                    <td class="text-right">{{$total_payment}}/=</td>
                                    <td class="text-right">{{$total_salary}}/=</td>
                                    <td class="text-right">{{$total_bank}}/=</td>
                                    <td class="text-right">{{$total_carring}}/=</td>
                                    <td class="text-right">{{$total_labour}}/=</td>
                                    <td class="text-right">{{$total_dokan}}/=</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>