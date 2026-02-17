<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class CustomersExport implements FromView
{
    use Exportable;
    public $customers;
    public $group_by_customer;
    public $columns;
    public $type;

    public function __construct($customers, $group_by_customer, $columns, $type)
    {
        $this->customers = $customers;
        $this->group_by_customer = $group_by_customer;
        $this->columns = $columns;
        $this->type = $type;
    }

    public function view(): View
    {
        $customers = $this->customers;
        $group_by_customer = $this->group_by_customer;
        $columns = $this->columns;
        $type = $this->type;

        return view('admin.customer.excel.all', get_defined_vars());
    }

}


