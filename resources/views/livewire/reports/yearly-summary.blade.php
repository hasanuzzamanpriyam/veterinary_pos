@section('page-title', 'Yearly Summary Report')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2 class="mr-auto">Yearly Summary Report</h2>
            </div>
        </div>
        <div class="x_content pb-4">
            <!-- তারিখ সিলেক্টর ফর্ম -->
            <livewire:reports.year-selector />
            <div class="container relative">
                <div wire:loading.flex class="position-absolute w-100 h-100 p-5 align-items-start justify-content-center" style="min-height: 250px; top: 0; left: 0; z-index: 1050;">
                    <div class="position-absolute w-100 h-100 modal-backdrop show" style="z-index: 10;"></div>
                    <div class="bg-white p-4 rounded-lg shadow-lg text-center" style="z-index: 20;">
                        <p class="font-weight-bold">Fetching Data...</p>
                        <div class="spinner-border text-primary mt-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <livewire:reports.yearly-summary-table :summary="$summary" wire:key="summary-overview" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
