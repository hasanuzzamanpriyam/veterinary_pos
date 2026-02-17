<!-- resources/views/invoice.blade.php -->
@extends('layouts.download')  <!-- Extending the print layout -->

@section('page-title')
    @if ($type == 'due')
    Due Customer List
    @else
    Customer List
    @endif
@endsection

@section('main-content')
    @if ($type == 'due')
        <h2 class="mt-4">Due Customer List</h2>
    @else
        <h2 class="mt-4">All Customer List</h2>
    @endif
    @php
        $g_total_sumarry = [];
    @endphp
    <table>
        <thead>
            <tr>
                @foreach ( $columns as $key => $column )
                    <th class="{{$key}}">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                @php
                    $total_sales = $customer->total_sales ?? 0;
                    $g_total_sumarry['sales'] = $g_total_sumarry['sales'] ?? 0;
                    $g_total_sumarry['sales'] += $total_sales;
                    $total_return = $customer->total_returns ?? 0;
                    $g_total_sumarry['return'] = $g_total_sumarry['return'] ?? 0;
                    $g_total_sumarry['return'] += $total_return;
                    $total_discount = $customer->total_price_discounts ?? 0;
                    $g_total_sumarry['discount'] = $g_total_sumarry['discount'] ?? 0;
                    $g_total_sumarry['discount'] += $total_discount;
                    $total_carring = $customer->total_carring ?? 0;
                    $g_total_sumarry['carring'] = $g_total_sumarry['carring'] ?? 0;
                    $g_total_sumarry['carring'] += $total_carring;
                    $total_others = $customer->total_others ?? 0;
                    $g_total_sumarry['others'] = $g_total_sumarry['others'] ?? 0;
                    $g_total_sumarry['others'] += $total_others;
                    $total_collection = $customer->total_collections ?? 0;
                    $g_total_sumarry['collection'] = $g_total_sumarry['collection'] ?? 0;
                    $g_total_sumarry['collection'] += $total_collection;
                    $prev_due = $customer->previous_due ?? 0;
                    $g_total_sumarry['previous_due'] = $g_total_sumarry['previous_due'] ?? 0;
                    $g_total_sumarry['previous_due'] += $prev_due;

                    $balance = $customer->balance;
                    $g_total_sumarry['balance'] = $g_total_sumarry['balance'] ?? 0;
                    $g_total_sumarry['balance'] += $balance;

                    $total_qty = [];
                    $total_sale_discount_qty = [];
                    $total_return_qty = [];
                    $total_sale_qty = [];
                    $total_tk = $total_sales - $total_return - $total_discount + $total_carring + $total_others;
                    $g_total_sumarry['total_tk'] = $g_total_sumarry['total_tk'] ?? 0;
                    $g_total_sumarry['total_tk'] += $total_sales;
                    $qty_summary = $group_by_customer->get($customer->id);
                    if (is_array($qty_summary) && count($qty_summary) > 0) {
                        foreach($qty_summary as $key => $value) {
                            $total_qty[$key] = $total_qty[$key] ?? 0;
                            $total_sale_discount_qty[$key] = $total_sale_discount_qty[$key] ?? 0;
                            $total_return_qty[$key] = $total_return_qty[$key] ?? 0;
                            $total_sale_qty[$key] = $total_sale_qty[$key] ?? 0;
                            $g_total_sumarry['sale_qty'][$key] = $g_total_sumarry['sale_qty'][$key] ?? 0;
                            $g_total_sumarry['sale_qty'][$key] += $value['sale'];
                            $g_total_sumarry['discount_qty'][$key] = $g_total_sumarry['discount_qty'][$key] ?? 0;
                            $g_total_sumarry['discount_qty'][$key] += $value['discount'];
                            $g_total_sumarry['return_qty'][$key] = $g_total_sumarry['return_qty'][$key] ?? 0;
                            $g_total_sumarry['return_qty'][$key] += $value['return'];
                            $g_total_sumarry['total_qty'][$key] = $g_total_sumarry['total_qty'][$key] ?? 0;
                            $g_total_sumarry['total_qty'][$key] += $value['sale'] - $value['discount'] - $value['return'];

                            $total_qty[$key] += $value['sale'];
                            $total_sale_discount_qty[$key] += $value['discount'];
                            $total_return_qty[$key] += $value['return'];
                            $total_sale_qty[$key] += $value['sale'] - $value['discount'] - $value['return'];
                        }
                        ksort($total_qty);
                        ksort($total_sale_discount_qty);
                        ksort($total_return_qty);
                        ksort($total_sale_qty);
                    }
                @endphp
                <tr>
                    <td class="id">{{ $loop->iteration }}</td>
                    <td class="customer_name_td name">{{ $customer->name }}</td>
                    <td class="customer_address_td address">{{ $customer->address }}</td>
                    <td class="customer_mobile_td phone">{{ $customer->mobile }}</td>
                    <td class="customer_ledger_td ledger">{{ $customer->ledger_page }}</td>
                    @if ( array_key_exists("price_group", $columns) )
                    <td class="customer_price_group_td price_group">{{ $customer->priceGroup->name }}</td>
                    @endif
                    <td class="customer_type_td type">{{ $customer->type }}</td>

                    {{-- Credit Limit --}}
                    @if ( array_key_exists("credit_limit", $columns) )
                    <td class="text-right customer_credit_limit_td credit_limit">
                        {{ $customer->credit_limit ? $customer->credit_limit : 0 }}
                    </td>
                    @endif
                    @if ( array_key_exists("quantity", $columns) )
                    <td class="quantity">
                        @foreach ($total_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif
                    @if ( array_key_exists("discount_qty", $columns) )
                    <td class="discount_qty">
                        @foreach ($total_sale_discount_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif
                    @if ( array_key_exists("return_qty", $columns) )
                    <td class="return_qty">
                        @foreach ($total_return_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif
                    @if ( array_key_exists("sale_qty", $columns) )
                    <td class="sale_qty">
                        @foreach ($total_sale_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif

                    {{-- Total Sales/Price --}}
                    @if ( array_key_exists("sale_amount", $columns) )
                    <td class="text-right sale_amount">{{ $total_sales }}</td>
                    @endif
                    {{-- Return --}}
                    @if ( array_key_exists("return", $columns) )
                    <td class="text-right return">{{ $total_return }}</td>
                    @endif
                    {{-- Discount --}}
                    @if ( array_key_exists("discount", $columns) )
                    <td class="text-right discount">{{ $total_discount }}</td>
                    @endif
                    {{-- Carring --}}
                    @if ( array_key_exists("carring", $columns) )
                    <td class="text-right carring">{{ $total_carring }}</td>
                    @endif
                    {{-- Others --}}
                    @if ( array_key_exists("others", $columns) )
                    <td class="text-right others">{{ $total_others }}</td>
                    @endif
                    {{-- Total TK --}}
                    @if ( array_key_exists("total", $columns) )
                    <td class="text-right total">{{ $total_tk }}</td>
                    @endif
                    {{-- Collection --}}
                    @if ( array_key_exists("collection", $columns) )
                    <td class="text-right collection">{{ $total_collection }}</td>
                    @endif
                    {{-- Balance --}}
                    <td class="text-right balance">{{ $balance }}</td>
                </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td class="id"></td>
                <td class="name"></td>
                <td class="address"></td>
                <td class="phone"></td>
                <td class="ledger"></td>
                @if ( array_key_exists("price_group", $columns) )
                <td class="price_group"></td>
                @endif
                <td class="type"></td>
                @if ( array_key_exists("credit_limit", $columns) )
                <td class="credit_limit"></td>
                @endif
                @if ( array_key_exists("quantity", $columns) )
                <td class="quantity">
                    @if( isset($g_total_sumarry['sale_qty']) && count($g_total_sumarry['sale_qty']) > 0 )
                        @php(ksort($g_total_sumarry['sale_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['sale_qty']) && count($g_total_sumarry['sale_qty']) > 0)
                            @foreach($g_total_sumarry['sale_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("discount_qty", $columns) )
                <td class="discount_qty">
                    @if( isset($g_total_sumarry['discount_qty']) && count($g_total_sumarry['discount_qty']) > 0 )
                        @php(ksort($g_total_sumarry['discount_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['discount_qty']) && count($g_total_sumarry['discount_qty']) > 0)
                            @foreach($g_total_sumarry['discount_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("return_qty", $columns) )
                <td class="return_qty">
                    @if( isset($g_total_sumarry['return_qty']) && count($g_total_sumarry['return_qty']) > 0 )
                        @php(ksort($g_total_sumarry['return_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['return_qty']) && count($g_total_sumarry['return_qty']) > 0)
                            @foreach($g_total_sumarry['return_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("sale_qty", $columns) )
                <td class="sale_qty">
                    @if( isset($g_total_sumarry['total_qty']) && count($g_total_sumarry['total_qty']) > 0 )
                        @php(ksort($g_total_sumarry['total_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['total_qty']) && count($g_total_sumarry['total_qty']) > 0)
                            @foreach($g_total_sumarry['total_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ $value }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("sale_amount", $columns) )
                <td class="text-right sale_amount"><b>{{$g_total_sumarry['sales']}}</b></td>
                @endif
                @if ( array_key_exists("return", $columns) )
                <td class="text-right return"><b>{{$g_total_sumarry['return']}}</b></td>
                @endif
                @if ( array_key_exists("discount", $columns) )
                <td class="text-right discount"><b>{{$g_total_sumarry['discount']}}</b></td>
                @endif
                @if ( array_key_exists("carring", $columns) )
                <td class="text-right carring"><b>{{$g_total_sumarry['carring']}}</b></td>
                @endif
                @if ( array_key_exists("others", $columns) )
                <td class="text-right others"><b>{{$g_total_sumarry['others']}}</b></td>
                @endif
                @if ( array_key_exists("total", $columns) )
                <td class="text-right total"><b>{{$g_total_sumarry['total_tk']}}</b></td>
                @endif
                @if ( array_key_exists("collection", $columns) )
                <td class="text-right collection"><b>{{$g_total_sumarry['collection']}}</b></td>
                @endif
                <td class="text-right balance"><b>{{$g_total_sumarry['balance']}}</b></td>
            </tr>
        </tfoot>
    </table>
@endsection
