<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use App\Models\SupplierTransactionDetails;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class SuppliersListDue extends Component
{
    use WithPagination;

    public $group_by_supplier;

    #[Url(as: 'perpage')]
    public $perPage;
    public $queryString;
    public $supplierWeights;

    public function mount()
    {

        $this->perPage = $this->perPage ?? 10;
        $this->queryString = '';
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }
    public function updateQueryString()
    {
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function filterData()
    {
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function resetData()
    {
        redirect()->route('supplier.transactions.ledger'); // Reset to the first page when perPage changes
    }

    public function downloadPdf()
    {
        $query = Supplier::with(['ledgers' => function ($query) {
            $query->whereIn('type', ['purchase', 'return', 'payment', 'other']);
        }])
            ->withSum(['ledgers as total_purchases' => function ($query) {
                $query->where('type', 'purchase');
            }], 'total_price')
            ->withSum(['ledgers as total_price_discounts' => function ($query) {
                $query->where('type', 'purchase');
            }], 'price_discount')
            ->withSum(['ledgers as total_vat' => function ($query) {
                $query->where('type', 'purchase');
            }], 'vat')
            ->withSum(['ledgers as total_carring' => function ($query) {
                $query->where('type', 'purchase')->orWhere('type', 'return');
            }], 'carring')
            ->withSum(['ledgers as total_others' => function ($query) {
                $query->where('type', 'purchase')->orWhere('type', 'return');
            }], 'other_charge')
            ->withSum(['ledgers as total_returns' => function ($query) {
                $query->where('type', 'return');
            }], 'total_price')
            ->withSum(['ledgers as total_payments' => function ($query) {
                $query->where('type', 'purchase')->orwhere('type', 'other')->orwhere('type', 'payment');
            }], 'payment')
            ->withSum(['ledgers as previous_due' => function ($query) {
                $query->where('type', 'other')->where('balance', '>', 0);
            }], 'balance')
            ->where('balance', '>', 0)
            ->where(function ($query) {
                $query->where('company_name', 'LIKE', "%$this->queryString%")
                    ->orWhere('address', 'LIKE', "%$this->queryString%")
                    ->orWhere('mobile', 'LIKE', "%$this->queryString%")
                    ->orWhere('id', $this->queryString);
            })
            ->orderBy('id', 'asc');

        $suppliers = $query->get(); // Fetch all records

        $supplierIds = $suppliers->pluck('id')->toArray();
        $tranx_all = SupplierTransactionDetails::whereIn('supplier_id', $supplierIds)->get();
        $this->group_by_supplier = $tranx_all->groupBy('supplier_id')->map(function ($items) {
            // Initialize totals array for products
            $totals = [];

            // Iterate through each item and sum up the sale and return quantities
            $items->each(function ($item) use (&$totals) {
                $transactionType = $item->transaction_type; // e.g., 'sale' or 'return'
                $productType = $item->product->type ?? 'pc';         // e.g., 'bag' or 'kg'
                $quantity = $item->quantity;
                $discount_quantity = $item->discount_qty;

                // Ensure that the product type is initialized in the totals array
                if (!isset($totals[$productType])) {
                    $totals[$productType] = [
                        'return' => 0,
                        'discount' => 0,
                        'purchase' => 0,
                    ];
                }

                // Accumulate the quantities for each transaction type
                if ($transactionType == 'purchase') {
                    $totals[$productType]['purchase'] += $quantity;
                    if ($discount_quantity > 0) {
                        $totals[$productType]['discount'] += $discount_quantity;
                    }
                } elseif ($transactionType == 'return') {
                    $totals[$productType]['return'] += $quantity;
                }
            });

            return $totals;
        });

        $this->supplierWeights = SupplierTransactionDetails::select(
            'supplier_id',
            DB::raw("SUM(CASE WHEN transaction_type = 'purchase' THEN weight * (quantity - discount_qty) ELSE 0 END) as total_purchased_weight"),
            DB::raw("SUM(CASE WHEN transaction_type = 'return' THEN weight * (quantity - discount_qty) ELSE 0 END) as total_returned_weight"),
            DB::raw("SUM(CASE WHEN transaction_type = 'purchase' THEN weight * (quantity - discount_qty) ELSE 0 END) - SUM(CASE WHEN transaction_type = 'return' THEN weight * quantity ELSE 0 END) as net_weight")
        )
            ->groupBy('supplier_id')
            ->get();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 50,
            'margin_bottom' => 25,
            'margin_header' => 10,
            // 'margin_footer' => 10,
            'default_font' => 'kalpurush',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'tempDir' => storage_path('app/mpdf/tmp'),
            'useSubstitutions' => true,
        ]);

        $html = view(
            'admin.download.supplier.pdf.due-supplier-list',
            [
                'supplierWeights' => $this->supplierWeights,
                'group_by_supplier' => $this->group_by_supplier,
                'suppliers' => $suppliers,
                'queryString' => $this->queryString
            ]
        )->render();

        // Process in chunks to avoid PCRE backtrack limit
        $chunks = splitHtml($html);
        foreach ($chunks as $chunk) {
            $mpdf->WriteHTML($chunk);
        }

        $mpdf->SetHTMLFooter('
            <table width="100%" style="font-size:6pt;">
                <tr>
                    <td width="50%">Generated: {DATE d-m-Y H:i:s}</td>
                    <td width="50%" style="text-align:right">Page {PAGENO}/{nbpg}</td>
                </tr>
            </table>
        ');

        $file_name = "Due_Suppliers.pdf";
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $file_name);
    }

    public function render()
    {
        $query = Supplier::with(['ledgers' => function ($query) {
            $query->whereIn('type', ['purchase', 'return', 'payment', 'other']);
        }])
            ->withSum(['ledgers as total_purchases' => function ($query) {
                $query->where('type', 'purchase');
            }], 'total_price')
            ->withSum(['ledgers as total_price_discounts' => function ($query) {
                $query->where('type', 'purchase');
            }], 'price_discount')
            ->withSum(['ledgers as total_vat' => function ($query) {
                $query->where('type', 'purchase');
            }], 'vat')
            ->withSum(['ledgers as total_carring' => function ($query) {
                $query->where('type', 'purchase')->orWhere('type', 'return');
            }], 'carring')
            ->withSum(['ledgers as total_others' => function ($query) {
                $query->where('type', 'purchase')->orWhere('type', 'return');
            }], 'other_charge')
            ->withSum(['ledgers as total_returns' => function ($query) {
                $query->where('type', 'return');
            }], 'total_price')
            ->withSum(['ledgers as total_payments' => function ($query) {
                $query->where('type', 'purchase')->orwhere('type', 'other')->orwhere('type', 'payment');
            }], 'payment')
            ->withSum(['ledgers as previous_due' => function ($query) {
                $query->where('type', 'other')->where('balance', '>', 0);
            }], 'balance')
            ->where('balance', '>', 0)
            ->where(function ($query) {
                $query->where('company_name', 'LIKE', "%$this->queryString%")
                    ->orWhere('address', 'LIKE', "%$this->queryString%")
                    ->orWhere('mobile', 'LIKE', "%$this->queryString%")
                    ->orWhere('id', $this->queryString);
            })
            ->orderBy('id', 'asc');

        if ($this->perPage === 'all') {
            $suppliers = $query->get(); // Fetch all records
        } else {
            $suppliers = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        $supplierIds = $suppliers->pluck('id')->toArray();
        $tranx_all = SupplierTransactionDetails::whereIn('supplier_id', $supplierIds)->get();
        $this->group_by_supplier = $tranx_all->groupBy('supplier_id')->map(function ($items) {
            // Initialize totals array for products
            $totals = [];

            // Iterate through each item and sum up the sale and return quantities
            $items->each(function ($item) use (&$totals) {
                $transactionType = $item->transaction_type; // e.g., 'sale' or 'return'
                $productType = $item->product->type ?? 'pc';         // e.g., 'bag' or 'kg'
                $quantity = $item->quantity;
                $discount_quantity = $item->discount_qty;

                // Ensure that the product type is initialized in the totals array
                if (!isset($totals[$productType])) {
                    $totals[$productType] = [
                        'return' => 0,
                        'discount' => 0,
                        'purchase' => 0,
                    ];
                }

                // Accumulate the quantities for each transaction type
                if ($transactionType == 'purchase') {
                    $totals[$productType]['purchase'] += $quantity;
                    if ($discount_quantity > 0) {
                        $totals[$productType]['discount'] += $discount_quantity;
                    }
                } elseif ($transactionType == 'return') {
                    $totals[$productType]['return'] += $quantity;
                }
            });

            return $totals;
        });

        $this->supplierWeights = SupplierTransactionDetails::select(
            'supplier_id',
            DB::raw("SUM(CASE WHEN transaction_type = 'purchase' THEN weight * (quantity - discount_qty) ELSE 0 END) as total_purchased_weight"),
            DB::raw("SUM(CASE WHEN transaction_type = 'return' THEN weight * (quantity - discount_qty) ELSE 0 END) as total_returned_weight"),
            DB::raw("SUM(CASE WHEN transaction_type = 'purchase' THEN weight * (quantity - discount_qty) ELSE 0 END) - SUM(CASE WHEN transaction_type = 'return' THEN weight * quantity ELSE 0 END) as net_weight")
        )
            ->groupBy('supplier_id')
            ->get();

        return view('livewire.supplier.suppliers-list-due', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
