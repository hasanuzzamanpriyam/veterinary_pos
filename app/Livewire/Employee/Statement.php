<?php

namespace App\Livewire\Employee;

use App\Models\Employee;
use App\Models\EmployeeLedger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class Statement extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage = 10;

    public $employees;
    public $employee_id;
    public $start_date;
    public $end_date;
    public $search_query;
    public $payment_method = null;

    public function mount(Request $request)
    {
        $this->employee_id = $request->query('id');
        $this->start_date = $request->query('start_date');
        $this->end_date = $request->query('end_date');
        $this->employees = Employee::latest()->orderBy('id', 'asc')->get();
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
        redirect()->route('employee.statement'); // Reset to the first page when perPage changes
    }

    public function downloadPdf()
    {
        $employee = Employee::where('id', $this->employee_id)->first();

        $query = EmployeeLedger::query()
            ->where('employee_id', $this->employee_id)
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
                    ->orWhere('payment_method', 'like', '%' . $this->search_query . '%')
                    ->orWhere('remarks', 'like', '%' . $this->search_query . '%');
            });
        }

        if ($this->payment_method) {
            $query->where('payment_by', $this->payment_method);
        }

        $data = $query->get();

        $merged = collect();
        if ($this->start_date && $this->end_date && empty($this->search_query) && empty($this->payment_method)) {
            $prev_balance = EmployeeLedger::query()
                ->where('employee_id', $this->employee_id)
                ->where('date', '<', date('Y-m-d', strtotime($this->start_date)))
                ->orderBy('date', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->reduce(function ($carry, $transaction) {
                    if ($transaction->type === 'salary') {
                        return $carry + $transaction->amount;
                    } elseif ($transaction->type === 'payment') {
                        return $carry - $transaction->amount;
                    }
                    return $carry;
                }, 0);

            $prev_arr = array(
                "id" => 0,
                "expense_id" => null,
                "employee_id" => $this->employee_id,
                "type" => "prev",
                "payment_method" => null,
                "remarks" => "Prevoius",
                "amount" => $prev_balance,
                "date" => null,
            );
            $ledger = new EmployeeLedger();
            $ledger->fill($prev_arr);
            // Set additional properties to match your example
            $ledger->exists = true; // Simulate existing record
            $ledger->wasRecentlyCreated = false;
            $ledger->created_at = now();
            $ledger->updated_at = now();
            $merged->push($ledger);
        }

        $merged = $merged->merge($data);

        $employee_statement_data = $merged;

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
            'admin.download.employee.ledger',
            [
                'employee_statement_data' => $employee_statement_data,
                'employee' => $employee,
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

        $cs_name = preg_replace('/[^\w\s]/', '', $employee->name . ' - ' . $employee->designation);
        $cs_name = preg_replace('/\s+/', ' ', $cs_name);
        $cs_name = str_replace(' ', '_', trim($cs_name));
        $file_name = "EL_" . $cs_name . '.pdf';
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $file_name);
    }

    public function render()
    {
        $employee = Employee::where('id', $this->employee_id)->first();

        $query = EmployeeLedger::query()
            ->where('employee_id', $this->employee_id)
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
                    ->orWhere('payment_method', 'like', '%' . $this->search_query . '%')
                    ->orWhere('remarks', 'like', '%' . $this->search_query . '%');
            });
        }

        if ($this->payment_method) {
            $query->where('payment_by', $this->payment_method);
        }

        $data = $query->get();

        $merged = collect();
        if ($this->start_date && $this->end_date && empty($this->search_query) && empty($this->payment_method)) {
            $prev_balance = EmployeeLedger::query()
                ->where('employee_id', $this->employee_id)
                ->where('date', '<', date('Y-m-d', strtotime($this->start_date)))
                ->orderBy('date', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->reduce(function ($carry, $transaction) {
                    if ($transaction->type === 'salary') {
                        return $carry + $transaction->amount;
                    } elseif ($transaction->type === 'payment') {
                        return $carry - $transaction->amount;
                    }
                    return $carry;
                }, 0);

            $prev_arr = array(
                "id" => 0,
                "expense_id" => null,
                "employee_id" => $this->employee_id,
                "type" => "prev",
                "payment_method" => null,
                "remarks" => "Prevoius",
                "amount" => $prev_balance,
                "date" => null,
            );
            $ledger = new EmployeeLedger();
            $ledger->fill($prev_arr);
            // Set additional properties to match your example
            $ledger->exists = true; // Simulate existing record
            $ledger->wasRecentlyCreated = false;
            $ledger->created_at = now();
            $ledger->updated_at = now();
            $merged->push($ledger);
        }

        $merged = $merged->merge($data);

        // Step 4: Manual pagination

        if ($this->perPage == 'all') {
            $employee_statement_data = $merged;
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
            $employee_statement_data = $paginated;
        }
        // if ($this->perPage === 'all') {
        //     $employee_statement_data = $query->get(); // Fetch all records
        // } else {
        //     $employee_statement_data = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        // }

        return view('livewire.employee.statement', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
