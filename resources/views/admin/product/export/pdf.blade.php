<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <style>
        body { font-family: 'sans-serif'; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .barcode { font-family: 'monospace'; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Product List</h2>
        <p>Generated on: {{ date('d-m-Y H:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">S.N.</th>
                <th width="8%">Image</th>
                <th width="12%">Code</th>
                <th>Name</th>
                <th>Company</th>
                <th>Category</th>
                <th width="5%">Size</th>
                <th width="5%">Stock</th>
                <th width="8%">TP</th>
                <th width="8%">MRP</th>
                <th width="8%">Sales</th>
            </tr>
        </thead>
        <tbody>
            @php
                $barcodeGenerator = new Picqer\Barcode\BarcodeGeneratorSVG();
            @endphp
            @foreach($products as $product)
                @php
                    $stock_qty = isset($stockList[$product->id]) ? $stockList[$product->id]['qty'] : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        @if($product->photo && file_exists(public_path($product->photo)))
                            <img src="{{ public_path($product->photo) }}" width="40" height="40" style="object-fit: contain;">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($product->barcode)
                            <span class="barcode">{{ $product->barcode }}</span>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->brand->name ?? '' }}</td>
                    <td>{{ $product->category->name ?? '' }}</td>
                    <td class="text-center">{{ $product->size->name ?? '' }}</td>
                    <td class="text-center">{{ $stock_qty }}</td>
                    <td class="text-right">{{ number_format($product->purchase_rate, 2) }}</td>
                    <td class="text-right">{{ number_format($product->mrp_rate, 2) }}</td>
                    <td class="text-right">{{ number_format($product->price_rate, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
