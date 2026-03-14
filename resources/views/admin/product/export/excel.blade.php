<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>S.N.</th>
            <th>Code</th>
            <th>Name</th>
            <th>Company</th>
            <th>Category</th>
            <th>Group</th>
            <th>Size</th>
            <th>Type</th>
            <th>Stock</th>
            <th>TP Rate</th>
            <th>MRP Rate</th>
            <th>Sales Rate</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            @php
                $stock_qty = isset($stockList[$product->id]) ? $stockList[$product->id]['qty'] : 0;
            @endphp
            <tr>
                <td style="height: 50px; width: 50px;"></td> <!-- Image placeholder for Drawings -->
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->barcode }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->brand->name ?? '' }}</td>
                <td>{{ $product->category->name ?? '' }}</td>
                <td>{{ $product->productGroup->name ?? '' }}</td>
                <td>{{ $product->size->name ?? '' }}</td>
                <td>{{ ucfirst($product->type) }}</td>
                <td>{{ $stock_qty }}</td>
                <td>{{ $product->purchase_rate }}</td>
                <td>{{ $product->mrp_rate }}</td>
                <td>{{ $product->price_rate }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
