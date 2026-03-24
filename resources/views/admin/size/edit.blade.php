@extends('layouts.admin')

@section('page-title', 'Update Type')

@section('main-content')
<div class="col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Update Type</h5>
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

            <form action="{{ route('size.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $size->id }}">

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Type Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $size->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $size->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="remarks" class="col-md-3 col-form-label text-md-right">Remarks</label>
                            <div class="col-md-9">
                                <textarea name="remarks" id="remarks" rows="2" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $size->remarks) }}</textarea>
                                @error('remarks')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <a href="{{ route('size.index') }}" class="btn btn-danger">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-edit"></i> Update Type
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