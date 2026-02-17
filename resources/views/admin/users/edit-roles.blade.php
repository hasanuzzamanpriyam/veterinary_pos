@extends('layouts.admin')

@section('page-title', 'Edit User Roles')

@section('main-content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Roles for: {{ $user->name }}</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if($user->id === 1)
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> 
                        This is the primary admin user. Super Admin role cannot be removed.
                    </div>
                @endif

                <form action="{{ route('users.roles.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label><strong>User Information</strong></label>
                        <p>
                            <strong>Name:</strong> {{ $user->name }}<br>
                            <strong>Email:</strong> {{ $user->email }}
                        </p>
                    </div>

                    <div class="form-group mt-3">
                        <label><strong>Assign Roles</strong></label>
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       name="roles[]" 
                                       value="{{ $role->name }}" 
                                       id="role_{{ $role->id }}"
                                       {{ in_array($role->name, $userRoles) ? 'checked' : '' }}
                                       {{ $user->id === 1 && $role->name === 'Super Admin' ? 'disabled' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                    <small class="text-muted">({{ $role->permissions->count() }} permissions)</small>
                                </label>
                            </div>
                        @endforeach
                        @if($user->id === 1)
                            <input type="hidden" name="roles[]" value="Super Admin">
                        @endif
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Update Roles
                        </button>
                        <a href="{{ route('users.roles.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
