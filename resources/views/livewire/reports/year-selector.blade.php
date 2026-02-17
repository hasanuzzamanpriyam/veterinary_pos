<div>
    <form wire:submit="update" style="max-width: 600px; margin: 0 auto">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="supplier-search-area">
                        <div class="form-group ">
                            <div class="input-group date" id="startdatepicker">
                                <input name="startdate" wire:model="startdate" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                <div class="input-group-addon py-half">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="supplier-search-area">
                        <div class="form-group ">
                            <div class="input-group date" id="enddatepicker">
                                <input name="enddate" wire:model="enddate" type="text" class="form-control" placeholder="dd-mm-yyyy">
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
    @if($currentStartDate && $currentEndDate)
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="ln_solid"></div>
            <div>
                <h2 class="text-center m-0">View report: <span class="text-danger font-weight-bold">Start: {{ $currentStartDate }} <span class="text-dark">to</span> End: {{ $currentEndDate }}</span></h2>
            </div>
            <div class="ln_solid"></div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#startdatepicker').datepicker( {
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $('#enddatepicker').datepicker( {
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $('#startdatepicker input[name=startdate]').on('change', function(e) {
                @this.set('startdate', e.target.value);
            });
            $('#enddatepicker input[name=enddate]').on('change', function(e) {
                @this.set('enddate', e.target.value);
            });
        });
    </script>
@endpush
