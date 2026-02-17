<div>
    <form wire:submit="update">
        <div class="col-lg-12 col-md-12 col-sm-12 offset-3">
            <div class="row ">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="supplier-search-area">
                        <div class="form-group ">
                            <div class="input-group date" id="datepicker">
                                <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                <div class="input-group-addon py-half">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="supplier-search-button">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-sm">Get</button>
                            <button type="button"  onclick="location.reload()" class="btn btn-danger btn-sm">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if($currentDate)
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="ln_solid"></div>
            <div>
                <h2 class="text-center m-0">View report: <span class="text-danger font-weight-bold">{{ $currentDate }}</span></h2>
            </div>
            <div class="ln_solid"></div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
            $('#datepicker input[name=date]').on('change', function(e) {
                @this.set('date', e.target.value);
            });
        });
    </script>
@endpush
