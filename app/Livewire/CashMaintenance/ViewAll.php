<?php

namespace App\Livewire\CashMaintenance;

use App\Models\CashManager;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class ViewAll extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;

    protected $f_start_date;
    protected $f_end_date;

    #[Url(as: 'perpage')]
    public $perPage;

    public function filterData()
    {
        $this->f_start_date = date('Y-m-d', strtotime($this->start_date));
        $this->f_end_date = date('Y-m-d', strtotime($this->end_date));
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function resetData()
    {
        $this->f_start_date = null;
        $this->f_end_date = null;
    }

    public function downloadPdf()
    {
        $query = CashManager::query()
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [
                date('Y-m-d', strtotime($this->start_date)),
                date('Y-m-d', strtotime($this->end_date))
            ]);
        }

        $cash_data = $query->get();

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
            'admin.download.cash.ledger',
            [
                'cash_data' => $cash_data,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
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

        $file_name = "CML_Daily_Cash_Ledger.pdf";
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $file_name);
    }

    public function render()
    {
        // $cash_data = [];
        $query = CashManager::query()
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [
                date('Y-m-d', strtotime($this->start_date)),
                date('Y-m-d', strtotime($this->end_date))
            ]);
        }

        if ($this->perPage === 'all') {
            $cash_data = $query->get();
        } else {
            $cash_data = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        return view('livewire.cash-maintenance.view-all', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
