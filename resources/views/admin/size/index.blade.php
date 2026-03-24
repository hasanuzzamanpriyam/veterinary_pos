@extends('layouts.admin')

@section('page-title', 'Type List')

@section('main-content')
<div class="col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Type List</h5>
            <a href="{{ route('size.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Type
            </a>
        </div>

        <div class="card-body">
            @if(session()->has('msg'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session()->get('msg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="datatable-responsive" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th>S.N.</th>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sizes as $size)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $size->id }}</td>
                                <td>{{ $size->name }}</td>
                                <td class="text-wrap">{{ $size->description ?? '—' }}</td>
                                <td class="text-wrap">{{ $size->remarks ?? '—' }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('size.edit', $size->id) }}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('size.delete', $size->id) }}" class="btn btn-danger btn-sm" id="delete" data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No types found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush