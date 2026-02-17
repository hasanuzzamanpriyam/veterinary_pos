<?php

// app/Http/Livewire/ShoppingCart.php

namespace App\Livewire;

use Livewire\Component;

class ShoppingCart extends Component
{
    public $cart = [];

    public function mount()
    {
        // Load initial cart data (you may fetch it from a database)
        $this->cart = [
            ['id' => 1, 'name' => 'Product 1', 'quantity' => 2, 'price' => 20],
            ['id' => 2, 'name' => 'Product 2', 'quantity' => 1, 'price' => 15],
            // Add more items as needed
        ];
    }

    public function updateQuantity($productId, $quantity)
    {
        // Find the product in the cart and update its quantity
        foreach ($this->cart as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $quantity;
            }
        }

        // Update the cart in the session or database
        // Example: session(['cart' => $this->cart]);

        // Refresh the Livewire component to reflect the changes
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
