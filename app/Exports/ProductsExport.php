<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductsExport implements FromView, WithDrawings, WithColumnWidths
{
    use Exportable;
    public $products;
    public $stockList;

    public function __construct($products, $stockList)
    {
        $this->products = $products;
        $this->stockList = $stockList;
    }

    public function view(): View
    {
        return view('admin.product.export.excel', [
            'products' => $this->products,
            'stockList' => $this->stockList
        ]);
    }

    public function drawings()
    {
        $drawings = [];
        foreach ($this->products as $index => $product) {
            $row = $index + 2;

            // Image Drawing
            if ($product->photo && file_exists(public_path($product->photo))) {
                $drawing = new Drawing();
                $drawing->setName($product->name);
                $drawing->setDescription($product->name);
                $drawing->setPath(public_path($product->photo));
                $drawing->setHeight(50);
                $drawing->setCoordinates('A' . $row);
                $drawings[] = $drawing;
            }
        }
        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Image column
        ];
    }
}
