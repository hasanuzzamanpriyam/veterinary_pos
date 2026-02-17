<?php

namespace App\Livewire\SalesReport;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\Supplier;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class Index extends Component
{

    public $start_date;
    public $end_date;
    public $year;
    public $get_customer_id;
    public $total_amount;
    public $total_quantity;
    public $total_weight;
    public $total_ton;
    public $reports;
    public $all_reports;
    public $products;
    public $customer_info;
    public $customers;






    // public function mount()
    // {
    //     // Set the initial date when the component is mounted
    //     $this->date = now()->toDateString();

    // }

    public function resetSupplier()
    {
        $this->reset('reports');
        $this->reset('all_reports');
        $this->reset('customer_info');
        $this->reset('start_date');
        $this->reset('end_date');
        $this->reset('get_customer_id');
    }


    public function getCustomer($key, $value)
    {
        // dd($key, $value);
        $this->$key = $value;
        if ($this->get_customer_id) {
            $this->customer_info = customer::where('id', $this->get_customer_id)->first();
        }
        $this->dispatch('dataUpdated');
    }

    public function pdfDownload()
    {

        // $pdf = PDF::loadView('livewire.sales-report.pdf',['start_date'=>$this->start_date,'end_date'=>$this->end_date,'reports'=>$this->reports, 'products'=>$this->products,'customers'=> $this->customers,'customer_info'=> $this->customer_info]);
        $pdf = PDF::loadView('livewire.sales-report.pdf');
        $date = now()->format('d-m-Y');
        return $pdf->stream('invoice(' . $date . ').pdf');
    }



    public function salesReportSearch()
    {


        if ($this->get_customer_id && ($this->start_date && $this->end_date)) {

            $this->start_date = date('Y-m-d', strtotime($this->start_date));
            $this->end_date = date('Y-m-d', strtotime($this->end_date));

            $this->year = date('Y', strtotime($this->end_date));
            $this->reports = CustomerLedger::where('customer_id', $this->get_customer_id)->where('type', 'sale')->whereBetween('date', [$this->start_date, $this->end_date])->get();

            // dd($this->reports);


            $this->products = CustomerTransactionDetails::where('customer_id', $this->get_customer_id)
                ->where('transaction_type', 'sale')
                ->whereBetween('date', [$this->start_date, $this->end_date])
                ->get();
        } elseif ($this->start_date && $this->end_date) {

            $this->start_date = date('Y-m-d', strtotime($this->start_date));
            $this->end_date = date('Y-m-d', strtotime($this->end_date));

            // dd( $this->start_date, $this->end_date);

            $this->all_reports = CustomerLedger::where('type', 'sale')->whereBetween('date', [$this->start_date, $this->end_date])->orderBy('id', 'ASC')->get();

            // dd($this->all_reports);

            $this->products = CustomerTransactionDetails::where('transaction_type', 'sale')
                ->whereBetween('date', [$this->start_date, $this->end_date])
                ->get();
        } else if ($this->get_customer_id) {

            $this->reports = CustomerLedger::where('customer_id', $this->get_customer_id)->where('type', 'sale')->orderBy('id', 'ASC')->get();
            $this->products = CustomerTransactionDetails::where('customer_id', $this->get_customer_id)
                ->where('transaction_type', 'sale')
                ->orderBy('id', 'ASC')
                ->get();
        }

        $this->dispatch('dataUpdated');
    }


    public function render()
    {

        $this->customers = customer::get();
        return view('livewire.sales-report.index', get_defined_vars());
    }
}
