@section('page-title', 'Yearly Bonus Count Edit')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2 ">
                <h2>Yearly Bonus Count Edit</h2>
                <a href="{{route('yearly.bonus-count.index')}}" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content p-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row d-flex justify-content-center">
                <div class="collection-form-area">
                    <div class="search-area">
                        <div class="col-lg-12 col-md-12 col-sm-12 py-5">
                            <h2 class="text-center text-dark">Supplier Info</h2>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 com-sm-12">
                                    <div class="supplier-info-area py-2">
                                        @if($supplier_info)
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <p class="border border-dark p-2">Name: {{$supplier_info->company_name }}</p>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <p class="border border-dark p-2 text-wrap">Address: {{  $supplier_info->address }}</p>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <p class="border border-dark p-2">Mobile: {{  $supplier_info->mobile}}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @dump($bonus_list) --}}
                    <form wire:submit.prevent=store() class="form-horizontal form-label-left sales_entry_form" method="POST">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-1 border entry-lebel sales_entry_lebel" for="10_ton">From - Weight(Ton)</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-1 border entry-lebel sales_entry_lebel" for="20_ton">To - Weight(Ton)</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="py-1 border entry-lebel sales_entry_lebel" for="mobile">Bonus Rate Tk</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column"></div>
                            </div>
                            {{-- @dd($yearly_bonus) --}}

                            @foreach ($yearly_bonus as $i => $bonus)
                                <div class="row" id="row_{{$i}}">
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border entry-lebel sales_entry_lebel d-none" for="from">From:</label>
                                            <input type="text" wire:model="yearly_bonus.{{$i}}.start" value="{{$bonus['start']}}" class="form-control text-right">

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border entry-lebel sales_entry_lebel d-none" for="to">To:</label>
                                            <input type="text" wire:model="yearly_bonus.{{$i}}.end"  value="{{$bonus['end']}}" class="form-control text-right">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border entry-lebel sales_entry_lebel d-none" for="rate">Bonus Rate:</label>
                                            <input type="text"  wire:model="yearly_bonus.{{$i}}.rate"  value="{{$bonus['rate']}}" class="form-control text-right">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <button type="button" wire:click="deleteARow({{$i}})" class="btn btn-sm btn-danger">Delete</button>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12">
                                <button type="button" class="btn btn-sm btn-info add-more-row" wire:click="addMoreRow">Add More</button>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 text-center">
                                <button type="button" class="btn btn-danger" wire:click="deleteAllRow">Delete All</button>
                                <a href="{{route('yearly.bonus-count.index')}}" class="btn btn-info" type="button">Cancel</a>
                                <button class="btn btn-warning" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
