@extends('layouts.admin')

@section('page-title', 'User Roles Management')

@section('main-content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>User Roles Management
                    <span class="badge badge-primary" style="font-size: 14px; margin-left: 10px;">
                        Total Users: {{ $totalUsers ?? 0 }}
                    </span>
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Assigned Roles</th>
                            <th>Created At</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge badge-secondary">No Role</span>
                                    @endforelse
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    @can('user-role-assign')
                                        <a href="{{ route('users.roles.edit', $user->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit Roles
                                        </a>
                                    @endcan
                                    @can('user-role-assign')
                                        @if(auth()->user()->id !== $user->id && $user->id !== 1)
                                            <form action="{{ route('users.destroy', $user->id) }}"
                                                  method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger" disabled
                                                    title="Cannot delete this user">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-sm btn-danger" disabled
                                                title="You don't have permission to delete users">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
