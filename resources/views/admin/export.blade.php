@extends('layouts.admin')

@section('page-title')
Export
@endsection

@section('main-content')
<div class="col-md-12">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Export Database</h2>
            </div>
        </div>

        <div class="x_content text-center pb-4">

            <a href="{{route('export.now')}}" class="btn btn-md btn-danger">Export Now</a>
        </div>
    </div>

</div>
@endsection
