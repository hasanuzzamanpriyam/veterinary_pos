<?php

namespace App\Livewire;

use App\Models\ProductStore;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class StockAdjustmentCheckout extends Component
{
    public $products;
    public $store_data;

    public function mount() {
        if(!session()->has('store_stock_adjust')) {
            return redirect()->route('live.pstockadjustment');
        }else{
            $this->store_data = session()->get('store_stock_adjust');
        }
    }

    public function cancel()
    {
        Cart::instance('stock_adjust')->destroy();
        session()->flash('store_stock_adjust');
        return redirect()->route('live.pstockadjustment');
    }

    public function back()
    {
        session()->flash('product_store_data');
        return redirect()->route('live.pstockadjustment');
    }

    public function stockStore(){
        if (Cart::instance('stock_adjust')->count() > 0) {
            foreach (Cart::instance('stock_adjust')->content() as $product) {
                // dd($product);

                $productId = $product->id; // Product ID
                $sourceStoreId = $this->store_data['source_store']['id']; // Source store ID
                $destinationStoreId = $this->store_data['destination_store']['id']; // Destination store ID
                $quantityToMove = $product->qty; // Desired quantity to move

                $productGroups = ProductStore::where('product_id', $productId)
                ->where('product_store_id', $sourceStoreId)
                ->get()
                ->groupBy('purchase_price');

                $totalMovedQuantity = 0;

                foreach ($productGroups as $purchasePrice => $products) {
                    foreach ($products as $product) {
                        if ($totalMovedQuantity >= $quantityToMove) {
                            break; // Exit loop if we've moved enough quantity
                        }

                        $availableQuantity = $product->product_quantity;

                        // Determine how much to move from this group
                        $quantityToTransfer = min($quantityToMove - $totalMovedQuantity, $availableQuantity);

                        // Update the source store's quantity
                        $product->product_quantity -= $quantityToTransfer;
                        $product->save();

                        // Update or create a record in the destination store
                        $destinationStore = ProductStore::firstOrNew([
                            'product_id' => $productId,
                            'product_store_id' => $destinationStoreId,
                            'purchase_price' => $purchasePrice,
                        ]);

                        $destinationStore->product_quantity += $quantityToTransfer;
                        $destinationStore->save();

                        $totalMovedQuantity += $quantityToTransfer;
                    }
                }
                if ($totalMovedQuantity < $quantityToMove) {
                    echo "Only $totalMovedQuantity units were available to move out of $quantityToMove requested.\n";
                }

            }

            Cart::instance('stock_adjust')->destroy();
            session()->flash('store_stock_adjust');

            $notification = array('msg' => 'Stock adjustment successful!', 'alert-type' => 'success');

            return redirect()->route('live.pstockadjustment')->with($notification);
            }
    }
    public function render()
    {

        if (Cart::instance('stock_adjust')->count() > 0) {
            $this->products = json_decode(Cart::instance('stock_adjust')->content());
        }
        // dd($this->products);
        return view('livewire.stock-adjustment-checkout')
        ->extends('layouts.admin')
        ->section('main-content');;
    }
}
