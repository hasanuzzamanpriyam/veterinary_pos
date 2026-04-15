<?php

namespace App\Livewire\PurchaseReturn;

use App\Models\Brand;
use App\Models\ProductStore;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use App\Models\Warehouse;
use App\Models\HeldPurchaseReturn;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Index extends Component
{
    public $search, $brand_id;
    public $new_search;
    public $searches;
    public $supplier_id;
    public $previous_due;
    public $advance_pay;
    public $date;
    public $return_date;
    public $full_return_date;
    public $product_store_id;
    public $delivery_man;
    public $supplier_search;
    public $supplier_name;
    public $address;
    public $mobile;
    public $invoice_no;
    public $product_store_name;
    public $purchase_invoice_no;
    public $price_group_id;
    public $stock_out;
    public $products;
    public $warehouse_id;
    public $warehouse_name;
    public $remarks;
    public $balance;
    public $showHeldPurchaseReturns = false;
    public $held_start_date;
    public $held_end_date;
    public $full_date;

    public function mount()
    {
        $this->full_date = date('Y-m-d');
    }

    public function rules()
    {
        return
            [
                'remarks' => ['nullable'],
                'date' => ['required'],
                'return_date' => ['required'],
                'product_store_name' => ['required'],
                'warehouse_name' => ['required'],
                'supplier_search' => ['required'],
                'supplier_id' => ['required'],
                'delivery_man' => ['nullable'],
            ];
    }

    public function validationAttributes()
    {
        return [
            'supplier_search' => 'Supplier',
            'date' => 'Purchase Date',
            'return_date' => 'Return Date',
            'product_store_name' => 'Store',
            'warehouse_name' => 'Warehouse',
        ];
    }

    public function toggleHeldPurchaseReturns()
    {
        $this->showHeldPurchaseReturns = !$this->showHeldPurchaseReturns;
    }

    //Increment cart product
    public function updateQuantity($id, $invoice_no, $quantities)
    {
        $purchase_data = SupplierTransactionDetails::where('transaction_id', $invoice_no)->where('product_id', $id)->first();
        // dd($purchase_data->quantity, $quantities);
        foreach (app('cart')->instance('purchase_return')->content() as $item) {
            if ($quantities <= $purchase_data->quantity ) {
                if ($item->id == $id) {
                    $item->qty = $quantities;
                    $item->options->stock = $quantities;
                }
            } else {
                if ($item->id == $id) {
                    $item->options->stock = 0;
                }
            }
        }
    }

    public function updatedReturnDate($date){
        $this->return_date = $date;
        // Convert dd-mm-yyyy to Y-m-d format for proper date parsing
        if ($date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $date);
            $this->full_return_date = $dateObj ? $dateObj->format('Y-m-d') : null;
        } else {
            $this->full_return_date = null;
        }
    }

    //Increment cart product
    public function updateDiscount($id, $discounts)
    {
        foreach (app('cart')->instance('purchase_return')->content() as $item) {
            if ($item->id == $id) {
                $item->options->discount = $discounts;
            }
        }
    }

    //Increment cart product
    public function updatePrice($id, $update_price)
    {
        foreach (app('cart')->instance('purchase_return')->content() as $item) {
            if ($item->id == $id) {
                $item->price = $update_price;
            }
        }
    }


    //remove product from cart
    public function itemRemove($rowId)
    {
        $cart = app('cart')->instance('purchase_return')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            app('cart')->instance('purchase_return')->remove($rowId);
        }
    }

    // public function dateSearch($date)
    // {
    //     $this->formatted_date = $date;
    //     $this->date = date('Y-m-d', strtotime($date));
    // }

    // public function returnDateSet($date)
    // {
    //     $this->formatted_return_date = $date;
    //     $this->return_date = date('Y-m-d', strtotime($date));
    // }

    // add product to sales cart
    public function sessionStore($id)
    {
        $products = ProductStore::where('product_id', $id)->first();
        $purchase = SupplierTransactionDetails::where('supplier_id', $this->supplier_search)->where('transaction_id', $this->purchase_invoice_no)->where('product_id', $id)->first();
        app('cart')->instance('purchase_return')->add([
            'id' =>  $products->product_id,
            'name' => $products->product_name,
            'qty' => 1,
            'price' => $purchase->unit_price ?? $products->product->price_rate,
            'options' => [
                'transaction_id' => $purchase->id,
                'barcode' => $products->product->barcode,
                'weight' => $purchase->weight,
                'product_store_id' => $products->product_store_id,
                'stock' => $purchase->quantity,
                'type' => $products->product->size->name ?? $products->product->type,
                'purchased_qty' => $purchase->quantity,
            ]
        ]);
    }


    //cancel order
    public function cancel()
    {
        app('cart')->instance('purchase_return')->destroy();
        session()->flash('return_supplier');
        session()->flash('supplier_balance');
        session()->flash('purchase_invoice_no');
        return redirect()->route('live.purchase.return.create');
    }

    //warehouse search
    // public function productSearch($value)
    // {
    //     if ($value == 0) {
    //     } else {
    //         $this->product_store_id = $value;
    //         $this->product_store_name = Store::find($value)->name;
    //     }
    // }
    public function updatedProductStoreName($name)
    {
        $store = Store::where('name', $name)->first();
        if ($store) {
            $this->product_store_id = $store->id;
        }
    }

    public function updatedWarehouseName($name)
    {
        $warehouse = Warehouse::where('name', $name)->first();
        if ($warehouse) {
            $this->warehouse_id = $warehouse->id;
        }
    }

    // store supplier info into session
    public function supplierInfo()
    {
        $this->validate();
        session()->put('supplier_balance',  $this->balance);
        session()->put('purchase_invoice_no',  $this->purchase_invoice_no);

        // Convert dates from dd-mm-yyyy to Y-m-d format safely
        $purchase_date = null;
        if ($this->date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $this->date);
            $purchase_date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');
        } else {
            $purchase_date = date('Y-m-d');
        }
        
        $return_date = null;
        if ($this->full_return_date) {
            $return_date = $this->full_return_date;
        } elseif ($this->return_date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $this->return_date);
            $return_date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');
        } else {
            $return_date = date('Y-m-d');
        }

        $return_supplier = [
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'balance' => $this->balance,
            'address' => $this->address,
            'mobile' =>  $this->mobile,
            'purchase_date' => $purchase_date,
            'return_date' => $return_date,
            'warehouse_id' => $this->warehouse_id,
            'warehouse_name' => $this->warehouse_name,
            'product_store_id' => $this->product_store_id,
            'product_store_name' => $this->product_store_name,
            'purchase_invoice_no' => $this->purchase_invoice_no,
            'delivery_man' => $this->delivery_man,
            'remarks' => $this->remarks,
        ];

        session()->put('return_supplier',  $return_supplier);
        return redirect()->route('live.purchase.return.checkout');
    }

    // brand wise search
    public function brandSearch($value)
    {
        $this->brand_id = $value;
    }

    // hold purchase return
    public function hold()
    {
        $this->validate();

        $cartContent = app('cart')->instance('purchase_return')->content();
        if ($cartContent->count() == 0) {
            session()->flash('error', 'Cart is empty. Please add items before holding.');
            return;
        }

        // Convert dates from dd-mm-yyyy to Y-m-d format safely
        $purchase_date = null;
        if ($this->date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $this->date);
            $purchase_date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');
        } else {
            $purchase_date = date('Y-m-d');
        }
        
        $return_date = null;
        if ($this->full_return_date) {
            $return_date = $this->full_return_date;
        } elseif ($this->return_date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $this->return_date);
            $return_date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');
        } else {
            $return_date = date('Y-m-d');
        }

        // Ensure IDs are synced from names just in case
        $this->updatedProductStoreName($this->product_store_name);
        $this->updatedWarehouseName($this->warehouse_name);

        $cartData = [];
        foreach ($cartContent as $item) {
            $cartData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'options' => $item->options->toArray()
            ];
        }

        HeldPurchaseReturn::create([
            'user_id' => auth()->id(),
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'balance' => $this->balance,
            'address' => $this->address,
            'mobile' =>  $this->mobile,
            'purchase_date' => $purchase_date,
            'return_date' => $return_date,
            'warehouse_id' => $this->warehouse_id,
            'warehouse_name' => $this->warehouse_name,
            'product_store_id' => $this->product_store_id,
            'product_store_name' => $this->product_store_name,
            'purchase_invoice_no' => $this->purchase_invoice_no,
            'delivery_man' => $this->delivery_man,
            'remarks' => $this->remarks,
            'cart_data' => $cartData,
        ]);

        $this->cancel();
    }

    public function resumeHold($holdId)
    {
        $hold = HeldPurchaseReturn::where('id', $holdId)->where('user_id', auth()->id())->first();
        if (!$hold) return;

        app('cart')->instance('purchase_return')->destroy();

        $this->supplier_id = $hold->supplier_id;
        $this->supplier_name = $hold->supplier_name;
        $this->balance = $hold->balance;
        $this->address = $hold->address;
        $this->mobile = $hold->mobile;
        $this->date = $hold->purchase_date;
        $this->return_date = $hold->return_date;
        $this->warehouse_id = $hold->warehouse_id;
        $this->warehouse_name = $hold->warehouse_name;
        $this->product_store_id = $hold->product_store_id;
        $this->product_store_name = $hold->product_store_name;
        $this->purchase_invoice_no = $hold->purchase_invoice_no;
        $this->delivery_man = $hold->delivery_man;
        $this->remarks = $hold->remarks;

        if ($this->supplier_id) {
            $this->supplier_search = $this->supplier_id;
        }

        if (is_array($hold->cart_data)) {
            foreach ($hold->cart_data as $item) {
                app('cart')->instance('purchase_return')->add([
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'options' => $item['options']
                ]);
            }
        }

        $hold->delete();

        return $this->supplierInfo();
    }

    public function editHold($holdId)
    {
        $hold = HeldPurchaseReturn::where('id', $holdId)->where('user_id', auth()->id())->first();
        if (!$hold) return;

        app('cart')->instance('purchase_return')->destroy();

        $this->supplier_id = $hold->supplier_id;
        $this->supplier_name = $hold->supplier_name;
        $this->balance = $hold->balance;
        $this->address = $hold->address;
        $this->mobile = $hold->mobile;
        
        // Convert Y-m-d to d-m-Y for datepickers
        $this->date = $hold->purchase_date ? date('d-m-Y', strtotime($hold->purchase_date)) : null;
        $this->return_date = $hold->return_date ? date('d-m-Y', strtotime($hold->return_date)) : null;
        
        $this->warehouse_id = $hold->warehouse_id;
        $this->warehouse_name = $hold->warehouse_name;
        $this->product_store_id = $hold->product_store_id;
        $this->product_store_name = $hold->product_store_name;
        $this->purchase_invoice_no = $hold->purchase_invoice_no;
        $this->delivery_man = $hold->delivery_man;
        $this->remarks = $hold->remarks;

        if ($this->supplier_id) {
            $this->supplier_search = $this->supplier_id;
            $this->dispatch('update-supplier-id', $this->supplier_id);
        }

        if ($this->date) {
            $this->dispatch('update-purchase-date', $this->date);
        }

        if ($this->return_date) {
            $this->dispatch('update-return-date', $this->return_date);
        }

        if (is_array($hold->cart_data)) {
            foreach ($hold->cart_data as $item) {
                app('cart')->instance('purchase_return')->add([
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'options' => $item['options']
                ]);
            }
        }

        $hold->delete();
    }

    public function deleteHold($holdId)
    {
        HeldPurchaseReturn::where('id', $holdId)->where('user_id', auth()->id())->delete();
    }

    public function get_previous_balance($supplier_id, $date){
        session()->flash('balance');
        $data = SupplierLedger::where('supplier_id', $supplier_id)->where('date', '<=', $date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        return $data->balance ?? 0;
    }

    public function updatedSupplierSearch($value)
    {
        if ($value) {
            $suppliers = Supplier::find($value);
            if ($suppliers) {
                $this->supplier_name = $suppliers->company_name;
                $this->address = $suppliers->address;
                $this->mobile = $suppliers->mobile;
                $this->supplier_id = $suppliers->id;
                
                if ($this->full_return_date) {
                    $this->balance = $this->get_previous_balance($this->supplier_id, $this->full_return_date);
                }

                $this->searchPurchaseInvoice();
            }
        } else {
            $this->supplier_name = null;
            $this->address = null;
            $this->mobile = null;
            $this->supplier_id = null;
            $this->balance = 0;
            $this->purchase_invoice_no = null;
        }
    }

    public function updatedDate($value)
    {
        if ($this->supplier_id) {
            $this->searchPurchaseInvoice();
        }
    }

    protected function searchPurchaseInvoice()
    {
        // First, try to find purchase invoice based on date
        if ($this->date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $this->date);
            $target_date = $dateObj ? $dateObj->format('Y-m-d') : null;

            if ($target_date) {
                $value = SupplierTransactionDetails::where('supplier_id', $this->supplier_id)
                    ->whereRaw("DATE(date) = ?", [$target_date])
                    ->where('transaction_type', 'purchase')
                    ->first();

                if ($value) {
                    $this->purchase_invoice_no = $value->transaction_id;
                    $this->warehouse_id = $value->warehouse_id;
                    $this->product_store_id = $value->product_store_id;
                    $this->product_store_name = Store::where('id', $this->product_store_id)->value('name');
                    $this->warehouse_name = Warehouse::where('id', $this->warehouse_id)->value('name');
                    return;
                }
            }
        }

        // If no invoice found by date, get the latest purchase transaction for this supplier
        $value = SupplierTransactionDetails::where('supplier_id', $this->supplier_id)
            ->where('transaction_type', 'purchase')
            ->orderBy('date', 'desc')
            ->first();

        if ($value) {
            $this->purchase_invoice_no = $value->transaction_id;
            // Only update the date from transaction if the user hasn't selected one
            if (!$this->date) {
                $this->date = date('d-m-Y', strtotime($value->date));
            }
            $this->warehouse_id = $value->warehouse_id;
            $this->product_store_id = $value->product_store_id;
            $this->product_store_name = Store::where('id', $this->product_store_id)->value('name');
            $this->warehouse_name = Warehouse::where('id', $this->warehouse_id)->value('name');
        }
    }

    public function render()
    {
        if ($this->supplier_id && $this->purchase_invoice_no) {
            // brand wise product search
            $this->products = SupplierTransactionDetails::where('supplier_id', $this->supplier_id)
                ->where('transaction_id', $this->purchase_invoice_no)
                ->get();
            $this->dispatch('dataUpdated');
        }


        $suppliers = Supplier::get();
        $stores = Store::get();
        $brands = Brand::get();

        $total_held_purchase_returns_count = HeldPurchaseReturn::where('user_id', auth()->id())->count();
        $held_query = HeldPurchaseReturn::where('user_id', auth()->id())->latest();

        if ($this->held_start_date) {
            $held_query->whereDate('created_at', '>=', date('Y-m-d', strtotime($this->held_start_date)));
        }

        if ($this->held_end_date) {
            $held_query->whereDate('created_at', '<=', date('Y-m-d', strtotime($this->held_end_date)));
        }

        $held_purchase_returns = $held_query->get();

        return view('livewire.purchase-return.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}

