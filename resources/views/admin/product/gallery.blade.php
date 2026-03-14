@extends('layouts.admin')

@section('page-title', 'Product Gallery')

@section('main-content')
<div class="container-fluid">
    <div class="page-title d-flex align-items-center justify-content-between p-3">
        <h3 class="mb-0">Product Gallery</h3>
        <div class="search-area">
            <form action="{{ route('product.gallery') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                <a href="{{ route('product.view', $product->id) }}" target="_blank" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                            @if($product->photo)
                                <img src="{{ asset($product->photo) }}" alt="{{ $product->name }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                            @else
                                <div class="text-muted text-center p-3">
                                    <i class="fa fa-image fa-3x mb-2"></i>
                                    <p class="small">No Image</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title text-dark font-weight-bold mb-1">{{ $product->name }}</h6>
                            <div class="small text-muted">
                                @if($product->brand)
                                    <span class="d-block"><i class="fa fa-tag mr-1"></i> Brand: {{ $product->brand->name }}</span>
                                @endif
                                @if($product->category)
                                    <span class="d-block"><i class="fa fa-folder mr-1"></i> Category: {{ $product->category->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fa fa-image fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No products found</h5>
            </div>
        @endforelse
    </div>

    @if(method_exists($products, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 8px;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
    .card-img-top {
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        background-color: #f8f9fa;
    }
    .card-title {
        font-size: 0.95rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .page-title {
        background: white;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>
@endpush