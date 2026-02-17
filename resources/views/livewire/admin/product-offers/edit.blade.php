<div>
    <h4>Edit Product Offer</h4>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form wire:submit.prevent="update">
        <div class="form-group">
            <label>Product (leave empty for all products)</label>
            <select class="form-control" wire:model="product_id">
                <option value="">-- All Products --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" @if($p->id == $offer->product_id) selected @endif>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select class="form-control" wire:model="type">
                <option value="percentage">Percentage</option>
                <option value="amount">Fixed Amount</option>
            </select>
        </div>
        <div class="form-group">
            <label>Value</label>
            <input type="number" step="0.01" class="form-control" wire:model="value" />
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Start Date</label>
                <input type="date" class="form-control" wire:model="start_date" />
            </div>
            <div class="form-group col-md-6">
                <label>End Date</label>
                <input type="date" class="form-control" wire:model="end_date" />
            </div>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" wire:model="active" id="activeCheck">
            <label class="form-check-label" for="activeCheck">Active</label>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
