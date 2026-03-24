@extends('layouts.admin')

@section('page-title', 'Add Type')

@section('main-content')
<div class="col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add New Type</h5>
            <a href="{{ route('size.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('size.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Type Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter type name" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Optional description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="remarks" class="col-md-3 col-form-label text-md-right">Remarks</label>
                            <div class="col-md-9">
                                <textarea name="remarks" id="remarks" rows="2" class="form-control @error('remarks') is-invalid @enderror" placeholder="Optional remarks">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <button type="reset" class="btn btn-warning">
                                    <i class="fa fa-undo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Save Type
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection