@extends('layouts.admin')

@section('page-title', 'Roles Management')

@section('main-content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Roles Management</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @can('role-create')
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create New Role
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif

                <table class="table table-striped table-bordered category_list_table">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th>Role Name</th>
                            <th>Permissions Count</th>
                            <th>Created At</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if(in_array($role->name, ['Super Admin', 'Admin', 'Manager', 'Staff', 'Viewer']))
                                        <span class="badge badge-info">System Role</span>
                                    @endif
                                </td>
                                <td>{{ $role->permissions->count() }} permissions</td>
                                <td>{{ $role->created_at->format('M d, Y') }}</td>
                                <td>
                                    @can('role-edit')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit {{ $role->name }} role">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    @endcan
                                    @if(!in_array($role->name, ['Super Admin', 'Admin', 'Manager', 'Staff', 'Viewer']))
                                        @can('role-delete')
                                            <form action="{{ route('roles.destroy', $role->id) }}"
                                                  method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger" disabled
                                                    title="You don't have permission to delete roles">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
