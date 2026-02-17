@extends('layouts.admin')

@section('page-title')
Monthly Purchase Report
@endsection

@section('main-content')

<livewire:purchase-report.index />
<div style="width:100%; overflow-x:auto;">

    <table style="
        width:85%;
        min-width:1200px;
        border-collapse:collapse;
        background:#ffffff;
        font-size:15px;
        color:#111827;  

    " border="1"
        class="mx-auto">

        <thead style="background:#f3f4f6;">
            <tr>
                <th style="padding:14px;">Sl</th>
                <th style="padding:14px;">Company Name</th>
                <th style="padding:14px;">Address</th>
                <th style="padding:14px;">Number</th>
                <th style="padding:14px;">Purchase Quantity</th>
                <th style="padding:14px;">Total</th>
                <th style="padding:14px;">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($purchases as $index => $purchase)
            <tr>
                <td style="padding:14px;">{{ $index + 1 }}</td>
                <td style="padding:14px; font-weight:600;">
                    {{ $purchase->company_name }}
                </td>
                <td style="padding:14px;">
                    {{ $purchase->address }}
                </td>
                <td style="padding:14px;">
                    {{ $purchase->mobile }}
                </td>
                <td style="padding:14px;">
                    {{ $purchase->total_quantity }}
                </td>
                <td style="padding:14px; font-weight:600;">
                    {{ number_format($purchase->total_amount, 2) }}
                </td>
                <td style="padding:14px;">
                    <a href="{{ route('purchase.view', $purchase->supplier_id) }}"
                        style="
                        background:#2563eb;
                        color:#fff;
                        padding:6px 12px;
                        border-radius:4px;
                        text-decoration:none;
                       ">
                        View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:16px; text-align:center;">
                    No purchase records found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


@endsection