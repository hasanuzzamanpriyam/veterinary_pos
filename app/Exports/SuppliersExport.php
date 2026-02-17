<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SuppliersExport implements FromView
{
    use Exportable;
    public $suppliers;
    public $group_by_supplier;
    public $supplierWeights;
    public $columns;
    public $type;

    public function __construct($suppliers, $group_by_supplier, $supplierWeights, $columns, $type)
    {
        $this->suppliers = $suppliers;
        $this->group_by_supplier = $group_by_supplier;
        $this->supplierWeights = $supplierWeights;
        $this->columns = $columns;
        $this->type = $type;
    }

    public function view(): View
    {
        $suppliers = $this->suppliers;
        $group_by_supplier = $this->group_by_supplier;
        $supplierWeights = $this->supplierWeights;
        $columns = $this->columns;
        $type = $this->type;

        return view('admin.supplier.excel.all', get_defined_vars());
    }
}
