<?php

namespace App\Livewire\Customer;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\CustomerTypes;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class CustomersListDue extends Component
{
    use WithPagination;

    public $group_by_customer;

    #[Url(as: 'perpage')]
    public $perPage;
    public $queryString;

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

    public function downloadPdf()
    {
        $query = customer::with(['ledgers' => function ($query) {
            $query->whereIn('type', ['sale', 'return', 'collection', 'other']);
        }])
            ->withSum(['ledgers as total_sales' => function ($query) {
                $query->where('type', 'sale');
            }], 'total_price')
            ->withSum(['ledgers as total_price_discounts' => function ($query) {
                $query->where('type', 'sale');
            }], 'price_discount')
            ->withSum(['ledgers as total_vat' => function ($query) {
                $query->where('type', 'sale');
            }], 'vat')
            ->withSum(['ledgers as total_carring' => function ($query) {
                $query->where('type', 'sale');
            }], 'carring')
            ->withSum(['ledgers as total_others' => function ($query) {
                $query->where('type', 'sale');
            }], 'other_charge')
            ->withSum(['ledgers as total_returns' => function ($query) {
                $query->where('type', 'return');
            }], 'total_price')
            ->withSum(['ledgers as total_collections' => function ($query) {
                $query->where('type', 'sale')->orwhere('type', 'other')->orwhere('type', 'collection');
            }], 'payment')
            ->withSum(['ledgers as previous_due' => function ($query) {
                $query->where('type', 'other')->where('balance', '>', 0);
            }], 'balance')
            ->where('balance', '>', 0)
            ->where(function ($query) {
                $query->where('name', 'LIKE', "%$this->queryString%")
                    ->orWhere('address', 'LIKE', "%$this->queryString%")
                    ->orWhere('mobile', 'LIKE', "%$this->queryString%")
                    ->orWhere('id', $this->queryString);
            })
            ->orderBy('id', 'asc');

        $customers = $query->get(); // Fetch all records

        $customerIds = $customers->pluck('id')->toArray();
        $tranx_all = CustomerTransactionDetails::whereIn('customer_id', $customerIds)->get();
        $this->group_by_customer = $tranx_all->groupBy('customer_id')->map(function ($items) {
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
                        'sale' => 0,
                    ];
                }

                // Accumulate the quantities for each transaction type
                if ($transactionType == 'sale') {
                    $totals[$productType]['sale'] += $quantity;
                    if ($discount_quantity > 0) {
                        $totals[$productType]['discount'] += $discount_quantity;
                    }
                } elseif ($transactionType == 'return') {
                    $totals[$productType]['return'] += $quantity;
                }
            });

            return $totals;
        });

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
            'admin.download.customer.pdf.due-customer-list',
            [
                'group_by_customer' => $this->group_by_customer,
                'customers' => $customers,
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

        $file_name = "Due_Customers.pdf";
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $file_name);
    }

    public function render()
    {
        $query = customer::with(['ledgers' => function ($query) {
            $query->whereIn('type', ['sale', 'return', 'collection', 'other']);
        }])
            ->withSum(['ledgers as total_sales' => function ($query) {
                $query->where('type', 'sale');
            }], 'total_price')
            ->withSum(['ledgers as total_price_discounts' => function ($query) {
                $query->where('type', 'sale');
            }], 'price_discount')
            ->withSum(['ledgers as total_vat' => function ($query) {
                $query->where('type', 'sale');
            }], 'vat')
            ->withSum(['ledgers as total_carring' => function ($query) {
                $query->where('type', 'sale');
            }], 'carring')
            ->withSum(['ledgers as total_others' => function ($query) {
                $query->where('type', 'sale');
            }], 'other_charge')
            ->withSum(['ledgers as total_returns' => function ($query) {
                $query->where('type', 'return');
            }], 'total_price')
            ->withSum(['ledgers as total_collections' => function ($query) {
                $query->where('type', 'sale')->orwhere('type', 'other')->orwhere('type', 'collection');
            }], 'payment')
            ->withSum(['ledgers as previous_due' => function ($query) {
                $query->where('type', 'other')->where('balance', '>', 0);
            }], 'balance')
            ->where('balance', '>', 0)
            ->where(function ($query) {
                $query->where('name', 'LIKE', "%$this->queryString%")
                    ->orWhere('address', 'LIKE', "%$this->queryString%")
                    ->orWhere('mobile', 'LIKE', "%$this->queryString%")
                    ->orWhere('id', $this->queryString);
            })
            ->orderBy('id', 'asc');

        if ($this->perPage === 'all') {
            $customers = $query->get(); // Fetch all records
        } else {
            $customers = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        // Fetch customer IDs from the $customers collection
        $customerIds = $customers->pluck('id')->toArray();
        $tranx_all = CustomerTransactionDetails::whereIn('customer_id', $customerIds)->get();
        $this->group_by_customer = $tranx_all->groupBy('customer_id')->map(function ($items) {
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
                        'sale' => 0,
                    ];
                }

                // Accumulate the quantities for each transaction type
                if ($transactionType == 'sale') {
                    $totals[$productType]['sale'] += $quantity;
                    if ($discount_quantity > 0) {
                        $totals[$productType]['discount'] += $discount_quantity;
                    }
                } elseif ($transactionType == 'return') {
                    $totals[$productType]['return'] += $quantity;
                }
            });

            return $totals;
        });

        return view('livewire.customer.customers-list-due', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
