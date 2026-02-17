<div>
    <h2>Shopping Cart</h2>

    @foreach ($cart as $item)
        <div>
            <p>{{ $item['name'] }}</p>
            <input type="number" wire:model="quantity" wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)">
            <p>Total: ${{ $item['quantity'] * $item['price'] }}</p>
        </div>
    @endforeach
</div>