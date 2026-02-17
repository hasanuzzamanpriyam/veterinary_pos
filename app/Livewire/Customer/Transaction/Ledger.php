<?php

namespace App\Livewire\Customer\Transaction;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class Ledger extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage = 10;

    public $customers;
    public $customer_id;
    public $start_date;
    public $end_date;
    public $search_query;

    public function mount(Request $request)
    {
        $this->customer_id = $request->query('id');
        $this->start_date = $request->query('start_date');
        $this->end_date = $request->query('end_date');
        $this->customers = customer::latest()->orderBy('id', 'asc')->get();
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }
    public function filterData()
    {
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function downloadPdf()
    {
        $customer = customer::where('id', $this->customer_id)->first();

        $customer_ledger_info = $products = [];
        if ($this->start_date != null && $this->end_date != null) {
            $f_start_date = date('Y-m-d', strtotime($this->start_date));
            $f_end_date = date('Y-m-d', strtotime($this->end_date));
            $f_end_date = Carbon::parse($f_end_date)->endOfDay();

            $products = CustomerTransactionDetails::where('customer_id', $this->customer_id)
                ->whereBetween('date', [$f_start_date, $f_end_date])
                ->get();

            $customer_ledger_collection = CustomerLedger::where('customer_id', $this->customer_id)
                ->whereBetween('date', [$f_start_date, $f_end_date])
                ->when(!empty($this->search_query), function (Builder $query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('type', 'like', '%' . $this->search_query . '%')
                            ->orWhere('payment_by', 'like', '%' . $this->search_query . '%')
                            ->orWhere('delivery_man', 'like', '%' . $this->search_query . '%')
                            ->orWhere('transport_no', 'like', '%' . $this->search_query . '%')
                            ->orWhere('remarks', 'like', '%' . $this->search_query . '%')
                            ->orWhere('bank_title', 'like', '%' . $this->search_query . '%');
                    });
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $merged = collect();
            if (empty($this->search_query)) {
                $previous_ledger = CustomerLedger::where('customer_id', $this->customer_id)
                    ->where('date', '<', $f_start_date)
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($previous_ledger) {
                    $previous_ledger->type = 'prev';
                    $merged->push($previous_ledger);
                }
            }
            $merged = $merged->merge($customer_ledger_collection);
            $customer_ledger_info = $merged;
        } else {
            $products = CustomerTransactionDetails::where('customer_id', $this->customer_id)->get();

            $query = CustomerLedger::where('customer_id', $this->customer_id)
                ->when(!empty($this->search_query), function (Builder $query) {
                    $query->where(function ($q) {

                        $q->orWhere('type', 'like', '%' . $this->search_query . '%')
                            ->orWhere('payment_by', 'like', '%' . $this->search_query . '%')
                            ->orWhere('delivery_man', 'like', '%' . $this->search_query . '%')
                            ->orWhere('transport_no', 'like', '%' . $this->search_query . '%')
                            ->orWhere('remarks', 'like', '%' . $this->search_query . '%')
                            ->orWhere('bank_title', 'like', '%' . $this->search_query . '%');
                    });
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc');

            $customer_ledger_info = $query->get();
        }
        if ($this->start_date && $this->end_date) {
            $a_startDate = $this->start_date;
            $a_endDate = $this->end_date;
        } else {
            $a_startDate = $customer_ledger_info->min('date');
            $a_endDate = $customer_ledger_info->max('date');
            $a_startDate = date('d-m-Y', strtotime($a_startDate));
            $a_endDate = date('d-m-Y', strtotime($a_endDate));
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 50,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10,
            'default_font' => 'kalpurush',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'tempDir' => storage_path('app/mpdf/tmp'),
            'useSubstitutions' => true,
        ]);

        $html = view('admin.download.transaction.pdf.customer-ledger', [
            'customer_ledger_info' => $customer_ledger_info,
            'products' => $products,
            'customer' => $customer,
            'start_date' => $a_startDate,
            'end_date' => $a_endDate,
            'search_query' => $this->search_query
        ])->render();

        // Process in chunks to avoid PCRE backtrack limit
        $chunks = splitHtml($html);
        foreach ($chunks as $chunk) {
            $mpdf->WriteHTML($chunk);
        }

        $mpdf->SetHTMLFooter('
            <table width="100%" style="font-family:kalpurush; font-size:6pt;">
                <tr>
                    <td width="50%">Generated: {DATE d-m-Y H:i:s}</td>
                    <td width="50%" style="text-align:right">Page {PAGENO}/{nbpg}</td>
                </tr>
            </table>
        ');

        $cs_name = preg_replace('/[^\w\s]/', '', $customer->name);
        $cs_name = preg_replace('/\s+/', ' ', $cs_name);
        $cs_name = str_replace(' ', '_', trim($cs_name));
        $file_name = "CL_" . $cs_name . '_' . $a_startDate . '_' . $a_endDate . '.pdf';
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $file_name);
    }

    public function resetData()
    {
        redirect()->route('customer.transactions.ledger'); // Reset to the first page when perPage changes
    }

    public function render()
    {
        $customer = customer::where('id', $this->customer_id)->first();
        if ($customer) {
            $customer_label = $customer->name . ' - ' . $customer->address . ' - ' . $customer->mobile;
        }
        $customer_ledger_info = $products = [];
        if ($this->start_date != null && $this->end_date != null) {
            $f_start_date = date('Y-m-d', strtotime($this->start_date));
            $f_end_date = date('Y-m-d', strtotime($this->end_date));
            $f_end_date = Carbon::parse($f_end_date)->endOfDay();

            $products = CustomerTransactionDetails::where('customer_id', $this->customer_id)
                ->whereBetween('date', [$f_start_date, $f_end_date])
                // ->when(!empty($this->search_query), function (Builder $query) {
                //         $query->where(function ($subQuery) {
                //             $subQuery->where('product_code', 'like', '%' . $this->search_query . '%')
                //                 ->orWhere('product_name', 'like', '%' . $this->search_query . '%')
                //                 ->orWhere('transaction_type', 'like', '%' . $this->search_query . '%');
                //             });
                //         })
                ->get();

            // $transaction_ids = $products->pluck('transaction_id')->toArray();

            $customer_ledger_collection = CustomerLedger::where('customer_id', $this->customer_id)
                // ->whereIn('id', $transaction_ids)
                ->whereBetween('date', [$f_start_date, $f_end_date])
                ->when(!empty($this->search_query), function (Builder $query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('type', 'like', '%' . $this->search_query . '%')
                            ->orWhere('payment_by', 'like', '%' . $this->search_query . '%')
                            ->orWhere('delivery_man', 'like', '%' . $this->search_query . '%')
                            ->orWhere('transport_no', 'like', '%' . $this->search_query . '%')
                            ->orWhere('remarks', 'like', '%' . $this->search_query . '%')
                            ->orWhere('bank_title', 'like', '%' . $this->search_query . '%');
                    });
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $merged = collect();
            if (empty($this->search_query)) {
                $previous_ledger = CustomerLedger::where('customer_id', $this->customer_id)
                    ->where('date', '<', $f_start_date)
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($previous_ledger) {
                    $previous_ledger->type = 'prev';
                    $merged->push($previous_ledger);
                }
            }
            $merged = $merged->merge($customer_ledger_collection);

            // Step 4: Manual pagination

            if ($this->perPage == 'all') {
                $customer_ledger_info = $merged;
            } else {
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = $this->perPage;
                $paginated = new LengthAwarePaginator(
                    $merged->forPage($currentPage, (int) $perPage),
                    $merged->count(),
                    (int) $perPage,
                    $currentPage,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
                $customer_ledger_info = $paginated;
            }
        } else {
            $products = CustomerTransactionDetails::where('customer_id', $this->customer_id)
                // ->when(!empty($this->search_query), function (Builder $query) {
                //     $query->where(function ($subQuery) {
                //         $subQuery->where('product_code', 'like', '%' . $this->search_query . '%')
                //             ->orWhere('product_name', 'like', '%' . $this->search_query . '%')
                //             ->orWhere('transaction_type', 'like', '%' . $this->search_query . '%');
                //     });
                // })
                ->get();

            // $transaction_ids = $products->pluck('transaction_id')->toArray();

            $query = CustomerLedger::where('customer_id', $this->customer_id)
                ->when(!empty($this->search_query), function (Builder $query) {
                    $query->where(function ($q) {
                        $q->orWhere('type', 'like', '%' . $this->search_query . '%')
                            ->orWhere('payment_by', 'like', '%' . $this->search_query . '%')
                            ->orWhere('delivery_man', 'like', '%' . $this->search_query . '%')
                            ->orWhere('transport_no', 'like', '%' . $this->search_query . '%')
                            ->orWhere('remarks', 'like', '%' . $this->search_query . '%')
                            ->orWhere('bank_title', 'like', '%' . $this->search_query . '%');
                    });
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc');

            if ($this->perPage === 'all') {
                $customer_ledger_info = $query->get();
            } else {
                $customer_ledger_info = $query->paginate((int) $this->perPage);
            }
        }

        return view('livewire.customer.transaction.ledger', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
