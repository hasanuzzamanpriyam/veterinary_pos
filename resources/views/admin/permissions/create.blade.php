@extends('layouts.admin')

@section('page-title', 'Create Permission')

@section('main-content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Create New Permission</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf

                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        New permissions will be automatically assigned to the Super Admin role.
                    </div>

                    <div class="form-group">
                        <label for="name">Permission Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name') }}" required
                               placeholder="e.g., custom-feature-access">
                        <small class="form-text text-muted">
                            Use lowercase with hyphens (e.g., module-action)
                        </small>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Create Permission
                        </button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
