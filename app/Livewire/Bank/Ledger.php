<?php

namespace App\Livewire\Bank;

use App\Models\Bank;
use App\Models\Transaction;
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

    public $banks;
    public $bank_id;
    public $start_date;
    public $end_date;
    public $search_query;

    public function mount(Request $request)
    {
        $this->bank_id = $request->query('id');
        $this->start_date = $request->query('start_date');
        $this->end_date = $request->query('end_date');
        $this->banks = Bank::latest()->orderBy('id', 'asc')->get();
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

    public function resetData()
    {
        redirect()->route('bank.ledger'); // Reset to the first page when perPage changes
    }

    public function downloadPdf()
    {
        $bank = Bank::where('id', $this->bank_id)->first();

        $query = Transaction::query()
            ->where('bank_id', $this->bank_id)
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [
                date('Y-m-d', strtotime($this->start_date)),
                date('Y-m-d', strtotime($this->end_date))
            ]);
        }

        if ($this->search_query) {

            $query->where(function ($q) {
                $q->orWhere('type', 'like', '%' . $this->search_query . '%')
                    ->orWhere('bank_name', 'like', '%' . $this->search_query . '%')
                    ->orWhere('bank_branch_name', 'like', '%' . $this->search_query . '%')
                    ->orWhere('payment_by_bank', 'like', '%' . $this->search_query . '%')
                    ->orWhere('remarks', 'like', '%' . $this->search_query . '%')
                    ->orWhere('bank_account_no', 'like', '%' . $this->search_query . '%');
            });
        }

        $data = $query->get();

        $merged = collect();
        if ($this->start_date && $this->end_date && empty($this->search_query) && empty($this->payment_method)) {
            $prev_balance = Transaction::query()
                ->where('bank_id', $this->bank_id)
                ->where('date', '<', date('Y-m-d', strtotime($this->start_date)))
                ->orderBy('date', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->reduce(function ($carry, $transaction) {
                    if ($transaction->type === 'withdraw') {
                        return $carry - $transaction->amount;
                    } elseif ($transaction->type === 'deposit' || $transaction->type === 'opening' || $transaction->type === 'others') {
                        return $carry + $transaction->amount;
                    }
                    return $carry;
                }, 0);

            $ledger = new Transaction();
            $ledger->exists = true; // Simulate existing record
            $ledger->wasRecentlyCreated = false;
            $ledger->bank_id = $this->bank_id;
            $ledger->type = 'prev';
            $ledger->remarks = 'Prevoius';
            $ledger->amount = $prev_balance;

            $ledger->created_at = now();
            $ledger->updated_at = now();
            $merged->push($ledger);
        }

        $merged = $merged->merge($data);

        $bank_statements = $merged;

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

        $html = view(
            'admin.download.bank.ledger',
            [
                'bank_statements' => $bank_statements,
                'bank' => $bank,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'search_query' => $this->search_query
            ]
        )->render();

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

        $cs_name = preg_replace('/[^\w\s]/', '', $bank->name . ' - ' . $bank->branch . ' - ' . $bank->ac_no . ' - ' . $bank->ac_mode);
        $cs_name = preg_replace('/\s+/', ' ', $cs_name);
        $cs_name = str_replace(' ', '_', trim($cs_name));
        $file_name = "BL_" . $cs_name . '.pdf';
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $file_name);
    }

    public function render()
    {
        $bank = Bank::where('id', $this->bank_id)->first();

        $query = Transaction::query()
            ->where('bank_id', $this->bank_id)
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [
                date('Y-m-d', strtotime($this->start_date)),
                date('Y-m-d', strtotime($this->end_date))
            ]);
        }

        if ($this->search_query) {

            $query->where(function ($q) {
                $q->orWhere('type', 'like', '%' . $this->search_query . '%')
                    ->orWhere('bank_name', 'like', '%' . $this->search_query . '%')
                    ->orWhere('bank_branch_name', 'like', '%' . $this->search_query . '%')
                    ->orWhere('payment_by_bank', 'like', '%' . $this->search_query . '%')
                    ->orWhere('remarks', 'like', '%' . $this->search_query . '%')
                    ->orWhere('bank_account_no', 'like', '%' . $this->search_query . '%');
            });
        }

        $data = $query->get();

        $merged = collect();
        if ($this->start_date && $this->end_date && empty($this->search_query) && empty($this->payment_method)) {
            $prev_balance = Transaction::query()
                ->where('bank_id', $this->bank_id)
                ->where('date', '<', date('Y-m-d', strtotime($this->start_date)))
                ->orderBy('date', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->reduce(function ($carry, $transaction) {
                    if ($transaction->type === 'withdraw') {
                        return $carry - $transaction->amount;
                    } elseif ($transaction->type === 'deposit' || $transaction->type === 'opening' || $transaction->type === 'others') {
                        return $carry + $transaction->amount;
                    }
                    return $carry;
                }, 0);

            $ledger = new Transaction();
            $ledger->exists = true; // Simulate existing record
            $ledger->wasRecentlyCreated = false;
            $ledger->bank_id = $this->bank_id;
            $ledger->type = 'prev';
            $ledger->remarks = 'Prevoius';
            $ledger->amount = $prev_balance;

            $ledger->created_at = now();
            $ledger->updated_at = now();
            $merged->push($ledger);
        }

        $merged = $merged->merge($data);

        if ($this->perPage == 'all') {
            $bank_statements = $merged;
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
            $bank_statements = $paginated;
        }

        return view('livewire.bank.ledger', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
