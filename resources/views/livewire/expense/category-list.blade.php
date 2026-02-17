@section('page-title', 'Expense Category List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel p-3">
        <div class="x_title ">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2 class="mr-auto">Expense Category List</h2>
                    <a href="{{route('expense_category.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Category</a>
                </div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    {{cute_loader()}}
                    <div class="table-header d-flex align-items-center justify-content-between">
                        <div class="per-page">
                            <div class="form-group">
                                <select id="perpage" class="form-control" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>

                        <div class="ajax-search">
                            <div class="form-group">
                                <input type="text" id="customer-search" class="form-control" style="min-width: 342px" placeholder="Filter by Category Name" wire:model.live.debounce.500ms="queryString" />
                            </div>
                        </div>
                    </div>
                    <div class="card-box table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all" style="width: 50px">SL</th>
                                <th class="all">Category Name</th>
                                <th class="all">Description</th>
                                <th class="all" style="width: 50px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expense_categories as $expense_category)
                                    @php
                                        $currentPage = method_exists($expense_categories, 'currentPage') ? $expense_categories->currentPage() : 1;
                                        $perPage = method_exists($expense_categories, 'perPage') ? $expense_categories->perPage() : $expense_categories->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{ $iteration }}</td>
                                        <td>{{$expense_category->name}}</td>
                                        <td class="text-wrap">{{$expense_category->description}}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{route('expense_category.edit',$expense_category->id)}}"><i class="fa fa-edit text-primary" style="font-size:18px"></i></a>
                                                <a href="{{route('expense_category.delete',$expense_category->id)}}" class="btn btn-sm btn-link p-0" id="delete"><i class="fa fa-trash text-danger" style="font-size:18px"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if (method_exists($expense_categories, 'links'))
                        <div class="mt-4 w-100">
                            {{ $expense_categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
