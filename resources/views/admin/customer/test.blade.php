@extends('layouts.admin')

@section('page-title')
Customertest
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Customer test page</h2>

            </div>
        </div>
        <div class="x_content">
            @php
                $list = array('one','two','three');

                var_dump($list);
            @endphp
        </div>
    </div>
</div>

@endsection
