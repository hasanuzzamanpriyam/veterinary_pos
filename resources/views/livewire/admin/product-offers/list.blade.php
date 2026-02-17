<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Product Offers</h4>
        <a href="{{ route('product.offers.create') }}" class="btn btn-primary">Create Offer</a>
    </div>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Type</th>
                <th>Value</th>
                <th>Active</th>
                <th>Start</th>
                <th>End</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($offers as $offer)
            <tr>
                <td>{{ $offer->id }}</td>
                <td>{{ optional($offer->product)->name ?? 'All Products' }}</td>
                <td>{{ ucfirst($offer->type) }}</td>
                <td>{{ $offer->value }}</td>
                <td>{{ $offer->active ? 'Yes' : 'No' }}</td>
                <td>{{ optional($offer->start_date)->format('Y-m-d') ?? '' }}</td>
                <td>{{ optional($offer->end_date)->format('Y-m-d') ?? '' }}</td>
                <td><a href="{{ route('product.offers.edit', $offer->id) }}" class="btn btn-sm btn-secondary">Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
