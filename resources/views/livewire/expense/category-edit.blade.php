@section('page-title', 'Expense Category Edit')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Edit Expense Category</h2>
                <a href="{{route('expense_category.index')}}" class="btn btn-md btn-primary"><i class="fa fa-list" aria-hidden="true"></i> View All Categories</a>
            </div>
        </div>
        <div class="x_content">

            <form wire:submit.prevent="update()" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left" style="max-width: 500px; margin: 0 auto;">
                @csrf
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list-group">
                        @foreach ($errors->all() as $error)
                            <li class="list-group-item list-group-item-danger text-center py-2">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="row m-auto">
                    <div class="col-12 gap-2">

                        <div class="row mb-4">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="name">Category Name</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <input type="text" wire:model="name" id="name" name="name" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="description">Description</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <textarea type="text" wire:model="description" name="description" id="description" cols="10" rows="2"  class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('expense_category.index')}}" class="btn btn-primary" type="button">Cancel</a>
                        <button class="btn btn-primary" type="reset">Reset</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
