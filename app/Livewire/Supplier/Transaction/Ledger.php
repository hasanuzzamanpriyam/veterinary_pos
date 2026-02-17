<?php

namespace App\Livewire\Supplier\Transaction;

use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\LazyCollection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class Ledger extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage = 10;

    public $suppliers;
    public $supplier_id;
    public $start_date;
    public $end_date;
    public $search_query;

    protected $queryString = [
        'supplier_id' => ['as' => 'id'],
        'start_date',
        'end_date',
        'perPage',
    ];

    public function mount(Request $request)
    {
        $this->supplier_id = $request->query('id');
        $this->start_date  = $request->query('start_date');
        $this->end_date    = $request->query('end_date');

        $this->suppliers = Supplier::select('id', 'company_name', 'address', 'mobile')
            ->orderBy('company_name')
            ->get();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function filterData()
    {
        $this->resetPage();
    }

    public function resetData()
    {
        return redirect()->route('supplier.transactions.ledger');
    }

    /**
     * ============================
     * MAIN RENDER METHOD
     * ============================
     */
    public function render()
    {
        $supplier = Supplier::find($this->supplier_id);

        if (!$supplier) {
            return view('livewire.supplier.transaction.ledger', [
                'supplier_ledger_info' => collect(),
                'products' => collect(),
                'supplier' => null,
                'previous_balance' => null,
            ])
                ->extends('layouts.admin')
                ->section('main-content');
        }

        /* -----------------------------
         * Date handling
         * ----------------------------- */
        $f_start = $this->start_date
            ? Carbon::createFromFormat('d-m-Y', $this->start_date)->startOfDay()
            : null;

        $f_end = $this->end_date
            ? Carbon::createFromFormat('d-m-Y', $this->end_date)->endOfDay()
            : null;

        /* -----------------------------
         * Previous balance
         * ----------------------------- */
        $previous_balance = null;

        if ($f_start && empty($this->search_query)) {
            $previous_balance = SupplierLedger::where('supplier_id', $this->supplier_id)
                ->where('date', '<', $f_start)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            if ($previous_balance) {
                $previous_balance->type = 'prev';
            }
        }

        /* -----------------------------
         * Base ledger query
         * ----------------------------- */
        $ledgerQuery = SupplierLedger::query()
            ->with('warehouse')
            ->where('supplier_id', $this->supplier_id)
            ->when(
                $f_start && $f_end,
                fn($q) =>
                $q->whereBetween('date', [$f_start, $f_end])
            )
            ->when($this->search_query, function (Builder $q) {
                $q->where(function ($sub) {
                    $sub->where('type', 'like', "%{$this->search_query}%")
                        ->orWhere('payment_by', 'like', "%{$this->search_query}%")
                        ->orWhere('bank_title', 'like', "%{$this->search_query}%");
                });
            })
            ->orderBy('date')
            ->orderBy('id');

        /* ==================================================
         * PAGINATION / ALL MODE (MEMORY SAFE)
         * ================================================== */
        if ($this->perPage === 'all') {

            // Use get() instead of cursor() to allow array access in view
            $supplier_ledger_info = $ledgerQuery->get();

            // Prepend previous balance if exists
            if ($previous_balance) {
                $supplier_ledger_info->prepend($previous_balance);
            }

            // Load products with eager loading and group for efficient access
            $products = SupplierTransactionDetails::with(['product.size'])
                ->where('supplier_id', $this->supplier_id)
                ->get()
                ->groupBy('transaction_id');
        } else {

            $supplier_ledger_info = $ledgerQuery->paginate((int) $this->perPage);

            $ledgerIds = $supplier_ledger_info->pluck('id')->toArray();

            $products = SupplierTransactionDetails::with(['product.size'])
                ->whereIn('transaction_id', $ledgerIds)
                ->get()
                ->groupBy('transaction_id');
        }

        return view('livewire.supplier.transaction.ledger', [
            'supplier_ledger_info' => $supplier_ledger_info,
            'products' => $products,
            'supplier' => $supplier,
            'previous_balance' => $previous_balance,
            'perPage' => $this->perPage,
        ])
            ->extends('layouts.admin')
            ->section('main-content');
    }

    /**
     * ============================
     * PDF DOWNLOAD (UNCHANGED)
     * ============================
     */
    public function downloadPdf()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $supplier = Supplier::findOrFail($this->supplier_id);

        $f_start = $this->start_date
            ? Carbon::createFromFormat('d-m-Y', $this->start_date)->startOfDay()
            : null;

        $f_end = $this->end_date
            ? Carbon::createFromFormat('d-m-Y', $this->end_date)->endOfDay()
            : null;

        $ledger = SupplierLedger::where('supplier_id', $this->supplier_id)
            ->when(
                $f_start && $f_end,
                fn($q) =>
                $q->whereBetween('date', [$f_start, $f_end])
            )
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $products = SupplierTransactionDetails::with(['product.size'])
            ->where('supplier_id', $this->supplier_id)
            ->get()
            ->groupBy('transaction_id');

        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 50,
            'margin_bottom' => 25,
            'default_font' => 'kalpurush',
            'tempDir' => storage_path('app/mpdf/tmp'),
        ]);

        $html = view('admin.download.transaction.pdf.supplier-ledger', [
            'supplier_ledger_info' => $ledger,
            'products' => $products,
            'supplier' => $supplier,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'search_query' => $this->search_query,
        ])->render();

        $mpdf->WriteHTML($html);

        return response()->streamDownload(
            fn() => print($mpdf->Output('', 'S')),
            'supplier_ledger.pdf'
        );
    }
}
