@section('page-title', 'Monthly Bonus Count List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Monthly Bonus Count List</h2>
                <a href="{{route('bonus.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Bonus Count</a>
            </div>

        </div>
        <div class="x_content p-3">
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

                        <div class="ajax-search d-flex align-items-center gap-2">
                            <div class="form-group">
                                <input type="text" wire:model="search_query" class="form-control form-control-sm" style="min-width: 400px" placeholder="Type Supplier Name"/>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-sm" wire:click="filterData" style="min-width: 100px">Get</button>
                                <button type="reset" class="btn btn-warning btn-sm" wire:click="resetData" style="min-width: 100px">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-box table-responsive">
                        <table class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="text-left">Company/Supplier Name</th>
                                <th class="all">Bonus Calculation Details</th>
                                <th class="all" style="width: 60px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 0;
                                @endphp
                                @foreach($parties as $key => $party)
                                    @php
                                        $count++;
                                        $currentPage = method_exists($parties, 'currentPage') ? $parties->currentPage() : 1;
                                        $perPage = method_exists($parties, 'perPage') ? $parties->perPage() : $parties->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{$iteration}}</td>
                                        <td class="text-left">{{$party->company_name}} - {{$party->address}} - {{$party->mobile}}</td>
                                        <td class="text-center">

                                            @if (count($bonus_list) > 0)
                                                <table class="table table-bordered table-striped w-100 table-sm">
                                                    <thead @class(['d-none' => $count > 1])>
                                                        <tr>
                                                            <th class="all text-left">From - Weight(Ton)</th>
                                                            <th class="all text-left">To - Weight(Ton)</th>
                                                            <th class="all">Rate Tk</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($bonus_list as $rate)
                                                            @if ($rate->supplier_id == $party->id)
                                                                <tr>
                                                                    <td class="text-left" style="width: 45%">{{$rate->start}}</td>
                                                                    <td class="text-left" style="width: 45%">{{$rate->end}}</td>
                                                                    <td class="text-right">{{$rate->rate}}/=</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if ($bonus_list->where('supplier_id', $party->id)->count() > 0)
                                                <a href="{{route('live.bonus.edit', $party->id)}}" class="btn btn-success btn-sm"><i class="fa fa-edit" ></i> Edit</a>
                                                @else
                                                <a href="{{route('bonus.create', $party->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-plus" ></i> Add</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if (method_exists($parties, 'links'))
                        <div class="mt-4 w-100">
                            {{ $parties->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
