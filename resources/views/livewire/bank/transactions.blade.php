@section('page-title', 'Bank Transaction List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="">Transaction List</h2>
                <ul class="nav navbar-right panel_toolbox mr-auto">
                    <li><span class="collapse-link btn btn-md btn-primary text-white "><i class="fa fa-eye"></i> Advance</span>
                    </li>
                </ul>
                <a href="{{route('bank.index')}}" class="btn btn-md btn-primary"><i class="fa fa-list" aria-hidden="true"></i> All Banks</a>
                <a href="{{route('transaction.create', ['view' => 'deposit'])}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Deposit</a>
                <a href="{{route('transaction.create', ['view' => 'withdraw'])}}" class="btn btn-md btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add Withdraw</a>
            </div>


        </div>


        <div class="p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 x_content" style="display: none;">
                    <form wire:submit.prevent="search" data-parsley-validate>
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 rounded">From Date</label>
                                    <div class="form-group">
                                        <input type="date" id="start_date" wire:model="start_date" class="form-control rounded">
                                    </div>
                                </div>



                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 rounded">To Date</label>

                                    <div class="form-group">
                                            <input type="date" id="end_date" wire:model="end_date" class="form-control rounded">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 rounded">Type</label>
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="transaction_type" wire:model="transaction_type" name="transaction_type" placeholder="Select Type" class="form-control rounded">
                                            <option value="">All</option>
                                            <option value="deposit">Deposit</option>
                                            <option value="withdraw">Withdraw</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 rounded">Method</label>
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="payment_method" wire:model="payment_method" name="payment_method" placeholder="Selct Method" class="form-control rounded">
                                            <option value="">All</option>
                                            @foreach ($payment_types as $types)
                                                <option value="{{$types}}">
                                                    {{ucfirst($types)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 rounded">Bank</label>
                                    <div class="form-group" wire:ignore>
                                        <select type="search" wire:model="get_bank_id" placeholder="search supplier" class="form-control rounded">
                                            <option value=""></option>
                                            @foreach ($banks as $bank)
                                                <option value="{{$bank->id}}">
                                                    {{$bank->name}} -
                                                    {{$bank->branch}} -
                                                    {{$bank->account_no}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" value="Search" class="form-control btn btn-success btn-xl">
                                </div>

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <button type="reset" wire:click="searchReset" class="form-control btn btn-danger btn-sm">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    {{cute_loader()}}
                    <div class="table-header d-flex align-items-center justify-content-between">
                        <div class="per-page">
                            <div class="form-group">
                                {{-- <select id="perpage" class="form-control" wire:change="updatePerPage($event.target.value)"> --}}
                                <select id="perpage" class="form-control rounded" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                            {{-- <form>
                            </form> --}}
                        </div>

                        <div class="ajax-search d-flex gap-2">
                            <div class="form-group">
                                <input type="text" id="customer-search" class="form-control rounded" style="min-width: 342px" placeholder="Name, Address or Phone" wire:model.live.debounce.500ms="queryString" />
                            </div>
                            <div class="form-group">
                                <button type="reset" wire:click="searchReset" class="form-control btn btn-danger btn-sm">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-box table-responsive">
                        {{-- notification message --}}
                        @if(session()->has('msg'))
                            <div class="text-center alert alert-success">
                                {{session()->get('msg')}}
                            </div>
                        @endif
                        {{-- @dump($start_date, $end_date) --}}
                        <table id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">S.N.</th>
                                <th class="all">Date</th>
                                <th class="all">Bank Name</th>
                                <th class="all">Branch Name</th>
                                <th class="all">Account</th>
                                <th class="all">Type</th>
                                <th class="all">Method</th>
                                <th class="all">Remarks</th>
                                <th class="all">Amount</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($transactions as $transaction )
                                    @php
                                        $total += $transaction->type == 'deposit' ? $transaction->amount : -$transaction->amount;

                                        $currentPage = method_exists($transactions, 'currentPage') ? $transactions->currentPage() : 1;
                                        $perPage = method_exists($transactions, 'perPage') ? $transactions->perPage() : $transactions->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr style="background-color: {{ $transaction->type == 'deposit' ? '#e1f3eb' : '#f3e1e1' }}">
                                        <td>{{ $iteration }}</td>
                                        <td>{{ date('d-m-Y',strtotime($transaction->updated_at)) }}</td>
                                        <td class="text-left">{{ $transaction->bank_name }}</td>
                                        <td class="text-left">{{ $transaction->bank_branch_name }}</td>
                                        <td class="text-left">{{ $transaction->bank_account_no }}</td>
                                        <td class="text-left">{{ ucfirst($transaction->type) }}</td>
                                        <td class="text-left">{{ ucfirst($transaction->payment_by) }}</td>
                                        <td class="text-left">{{ $transaction->remarks }}</td>
                                        <td class="text-right">{{ formatAmount($transaction->amount) }}/=</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="{{route('transaction.edit', ['view' => $transaction->type, 'id' => $transaction->id])}}" class="btn btn-success"><i class="fa fa-edit" ></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('transaction.delete',$transaction->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>{{formatAmount($total)}}/=</b></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if (method_exists($transactions, 'links'))
                        <div class="mt-4 w-100">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @push('scripts')
<script>

    $(document).ready(function () {
        alert("jerer");

        // let selectedDate = '';

        // $('#start_datepicker').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        //     todayHighlight: true
        // }).on('changeDate', function(e) {
        //     selectedDate = e.format('dd-mm-yyyy');
        // });
        // $(document).on('livewire:load', function(e){
        //     alert("hello");
        //     $('#sss').on('submit', function(e){
        //         e.preventDefault();
        //         // alert("hello");
        //         Livewire.emit('setDate', selectedDate);
        //     });
        // });

        // document.getElementById('sss').addEventListener('submit', function () {
        //     e.preventDefault();
        //     Livewire.emit('setDate', selectedDate); // Submit Button-এ ক্লিক করলে পাঠাবে
        // });

        // $('#start_datepicker').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        //     todayHighlight: true
        // });
        // $('#start_datepicker input[name=start_date]').on('change', function (e) {
        //     let dateParts = e.target.value.split("-");
        //     let formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0]; // new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
        //     console.log(formattedDate);
        //     // @this.set('start_date', formattedDate);
        //     //@this.set('start_date', e.target.value);
        //     $(this).val(e.target.value);
        // });

        // $('#end_datepicker').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        //     todayHighlight: true
        // });
        // $('#end_datepicker input[name=end_date]').on('change', function (e) {
        //     let dateParts = e.target.value.split("-");
        //     let formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0]; // new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
        //     // @this.set('end_date', formattedDate);
        //     @this.set('end_date', e.target.value);
        // });
    });
</script>
@endpush --}}
