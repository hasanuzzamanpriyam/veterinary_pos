@extends('layouts.admin')

@section('page-title')
Price Group List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Price Group List</h2>
                <a href="{{route('price_group.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Price Group</a>
            </div>

        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        <table id="PriceGroupList" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="all">ID</th>
                                <th class="all">Price Group Name</th>
                                <th class="all">Description</th>
                                <th class="all">Remarks</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($price_groups as $price_group)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$price_group->id}}</td>
                                        <td>{{$price_group->name}}</td>
                                        <td class="text-wrap">{{$price_group->description}}</td>
                                        <td class="text-wrap">{{$price_group->remarks}}</td>
                                        <td>
                                            <a href="{{route('price_group.edit',$price_group->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a>
                                            <a href="{{route('price_group.delete',$price_group->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a>
                                            <a href="{{route('price_group.add',$price_group->id)}}" class="btn btn-info"><i class="fa fa-plus" ></i></a>
                                            {{-- <a href="{{route('price_group.show',$price_group->id)}}" class="btn btn-info"><i class="fa fa-list" ></i></a> --}}
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

@push('scripts')
<script>

    $(document).ready(function () {
        $("#PriceGroupList").DataTable({
            ordering: false,
        });
    });

</script>
@endpush
