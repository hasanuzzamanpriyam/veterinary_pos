@extends('layouts.admin')

@section('page-title')
Product List
@endsection

@section('main-content')

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="back_button mb-2">
                    <a href="{{route('price_group.index')}}" class="btn btn-md btn-primary float-right"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Price Group Product List</h2>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>SL. No.</th>
                                        <th>Price Group Name</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Group</th>
                                        <th>Type</th>
                                        <th>Previous Rate</th>
                                        <th>Present Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{ $product->priceGroup->name}}</td>
                                        <td>{{ $product->product->code}}</td>
                                        <td>{{ $product->product->name}}</td>
                                        <td>{{ $product->product->brand->name}}</td>
                                        <td>{{ $product->product->category->name ?? " "}}</td>
                                        <td>{{ $product->product->productGroup->name}}</td>
                                        <td>{{ $product->product->type}}</td>
                                        <td class="text-right">{{ $product->product->price_rate}}/=</td>
                                        <td class="text-right">{{ $product->price_group_rate}}/=</td>
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
    </div>
</div>


@endsection
