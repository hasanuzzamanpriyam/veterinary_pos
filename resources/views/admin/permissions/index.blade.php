@extends('layouts.admin')

@section('page-title', 'Permissions Management')

@section('main-content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Permissions Management</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @can('permission-create')
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create New Permission
                            </a>
                        @endcan
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <strong>{{ ucfirst($module) }} Module</strong>
                                    <span class="badge badge-light float-right">
                                        {{ $modulePermissions->count() }} permissions
                                    </span>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($modulePermissions as $permission)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $permission->name }}</span>
                                                @can('permission-delete')
                                                    <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                          method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this permission?')">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-danger" disabled
                                                            title="You don't have permission to delete permissions">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                @endcan
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
