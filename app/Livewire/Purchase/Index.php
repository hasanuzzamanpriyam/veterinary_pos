<?php

namespace App\Livewire\Purchase;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\Warehouse;
use App\Models\HeldPurchase;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $supplier_search;
    public $supplier_name;
    public $supplier_id;
    public $search, $brand_id;
    public $new_search;
    public $searches;
    public $customer_id;
    public $balance;
    public $date;
    public $full_date;
    public $warehouse_id;
    public $product_store_id;
    public $transport_no;
    public $delivery_man;
    public $address;
    public $mobile;
    public $warehouse_name;
    public $supplier_remarks;
    public $showSidebar = false;
    public $purchase_date;
    public $production_date;
    public $expire_date;
    public $held_start_date;
    public $held_end_date;
    public $item_vat_mode = []; // indexed by rowId
    public $item_discount_mode = []; // indexed by rowId
    public $showHeldPurchases = false;



    public function rules()
    {
        return
            [
                'purchase_date' => ['nullable'],
                'warehouse_id' => ['required'],
                'product_store_id' => ['required'],
                'transport_no' => ['nullable'],
                'delivery_man' => ['nullable']
            ];
    }

    public function mount()
    {
        $this->full_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime(now()))));
        $this->date = date('d-m-Y', strtotime($this->full_date));
        $this->showSidebar = session()->has('showSidebar') ? session()->get('showSidebar') : false;
    }

    public function toggleSidebar()
    {
        $this->showSidebar = !$this->showSidebar;
        session()->put('showSidebar', $this->showSidebar);
    }

    public function updateQuantity($id, $quantities)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->id == $id) {
                app('cart')->instance('purchase')->update($item->rowId, [
                    'qty' => (float) $quantities,
                ]);
                break;
            }
        }
    }

    //Update quantity based on Purchase(Q)
    public function updatePurchaseQty($id, $purchaseQty)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->id == $id) {
                $newQty = (float) $purchaseQty + (float) $item->options->discount;
                app('cart')->instance('purchase')->update($item->rowId, [
                    'qty' => $newQty,
                    'options' => $item->options->toArray(),
                ]);
                break;
            }
        }
    }

    //Update discount and recalculate quantity to keep Purchase(Q) constant
    public function updateDiscount($id, $discounts)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->id == $id) {
                $newDiscount = (float) $discounts;
                $newOptions = array_merge($item->options->toArray(), ['discount' => $newDiscount]);
                app('cart')->instance('purchase')->update($item->rowId, [
                    'options' => $newOptions,
                ]);
                break;
            }
        }
    }

    //Increment cart product
    public function updatePrice($id, $update_price)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->id == $id) {
                app('cart')->instance('purchase')->update($item->rowId, [
                    'price' => (float) $update_price,
                    'qty' => $item->qty,
                    'options' => $item->options->toArray(),
                ]);
                break;
            }
        }
    }

    // Update item discount (monetary)
    public function updateItemDiscount($rowId, $discount)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->rowId == $rowId) {
                $newOptions = array_merge($item->options->toArray(), ['item_discount' => (float) $discount]);
                app('cart')->instance('purchase')->update($item->rowId, [
                    'options' => $newOptions,
                ]);
                break;
            }
        }
    }

    // Set Discount mode for an item (Manual or Percentage)
    public function setItemDiscountMode($rowId, $mode)
    {
        $this->item_discount_mode[$rowId] = $mode;
    }

    // Update item Discount by percentage
    public function updateItemDiscountPercent($rowId, $percent)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->rowId == $rowId) {
                $line_value = ($item->qty - $item->options->discount) * $item->price;
                $discount_amount = $line_value * (float) $percent / 100;
                $newOptions = array_merge($item->options->toArray(), ['item_discount' => (float) $discount_amount, 'item_discount_percent' => (float) $percent]);
                app('cart')->instance('purchase')->update($item->rowId, [
                    'options' => $newOptions,
                ]);
                break;
            }
        }
    }

    // Update item VAT
    public function updateItemVat($rowId, $vat)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->rowId == $rowId) {
                $newOptions = array_merge($item->options->toArray(), ['item_vat' => (float) $vat]);
                app('cart')->instance('purchase')->update($item->rowId, [
                    'options' => $newOptions,
                ]);
                break;
            }
        }
    }

    // Set VAT mode for an item (Manual or Percentage)
    public function setItemVatMode($rowId, $mode)
    {
        $this->item_vat_mode[$rowId] = $mode;
    }

    // Update item VAT by percentage
    public function updateItemVatPercent($rowId, $percent)
    {
        foreach (app('cart')->instance('purchase')->content() as $item) {
            if ($item->rowId == $rowId) {
                $line_value = ($item->qty - $item->options->discount) * $item->price;
                $vat_amount = $line_value * (float) $percent / 100;
                $newOptions = array_merge($item->options->toArray(), ['item_vat' => (float) $vat_amount, 'item_vat_percent' => (float) $percent]);
                app('cart')->instance('purchase')->update($item->rowId, [
                    'options' => $newOptions,
                ]);
                break;
            }
        }
    }

    // Update require date for a cart item
    public function updateExpireDate($rowId, $expireDate)
    {
        $cart = app('cart')->instance('purchase')->content();
        foreach ($cart as $item) {
            if ($item->rowId == $rowId) {
                app('cart')->instance('purchase')->update($rowId, [
                    'options' => array_merge($item->options->toArray(), [
                        'expire_date' => $expireDate,
                    ]),
                ]);
                break;
            }
        }
    }

    // Update batch type (regular/discount)
    public function updateBatchType($rowId, $type)
    {
        $cart = app('cart')->instance('purchase')->content();
        foreach ($cart as $item) {
            if ($item->rowId == $rowId) {
                app('cart')->instance('purchase')->update($rowId, [
                    'options' => array_merge($item->options->toArray(), [
                        'batch_type' => $type,
                    ]),
                ]);
                break;
            }
        }
    }

    // Update production date for a cart item
    public function updateProductionDate($rowId, $productionDate)
    {
        $cart = app('cart')->instance('purchase')->content();
        foreach ($cart as $item) {
            if ($item->rowId == $rowId) {
                app('cart')->instance('purchase')->update($rowId, [
                    'options' => array_merge($item->options->toArray(), [
                        'production_date' => $productionDate,
                    ]),
                ]);
                break;
            }
        }
    }

    //remove product from cart
    public function itemRemove($rowId)
    {

        $cart = app('cart')->instance('purchase')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            app('cart')->instance('purchase')->remove($rowId);
        }
    }

    // add product to purchase cart
    public function sessionStore($id)
    {
        $products = Product::where('id', $id)->first();
        app('cart')->instance('purchase')->add([
            'id' => $products->id,
            'name' => $products->name,
            'qty' => 1,
            'price' => $products->purchase_rate,
            'options' => [
                'barcode' => $products->barcode,
                'discount' => 0,
                'item_discount' => 0,
                'item_vat' => 0,
                'weight' => $products->size->name,
                'brand_id' => $products->brand_id,
                'type' => $products->size->name ?? $products->type,
                'code' => $products->code,
                'sort_index' => microtime(true)
            ]
        ]);
    }

    // hold purchase
    public function hold()
    {
        $cartContent = app('cart')->instance('purchase')->content();
        if ($cartContent->count() == 0) {
            return;
        }

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

        HeldPurchase::create([
            'user_id' => auth()->id(),
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'warehouse_id' => $this->warehouse_id,
            'product_store_id' => $this->product_store_id,
            'transport_no' => $this->transport_no,
            'delivery_man' => $this->delivery_man,
            'purchase_date' => $this->purchase_date,
            'supplier_remarks' => $this->supplier_remarks,
            'cart_data' => $cartData,
        ]);

        $this->cancel();
    }

    public function resumeHold($holdId)
    {
        $hold = HeldPurchase::where('id', $holdId)->where('user_id', auth()->id())->first();
        if (!$hold) return;

        app('cart')->instance('purchase')->destroy();

        $this->supplier_id = $hold->supplier_id;
        $this->supplier_name = $hold->supplier_name;
        $this->warehouse_id = $hold->warehouse_id;
        $this->product_store_id = $hold->product_store_id;
        $this->transport_no = $hold->transport_no;
        $this->delivery_man = $hold->delivery_man;
        $this->purchase_date = $hold->purchase_date;
        $this->supplier_remarks = $hold->supplier_remarks;

        if ($this->supplier_id) {
            $this->supplier_search = $this->supplier_id;
            $this->balance = $this->get_previous_balance($this->supplier_id, $this->full_date);
            session()->put('balance', $this->balance);
        }

        if (is_array($hold->cart_data)) {
            foreach ($hold->cart_data as $item) {
                app('cart')->instance('purchase')->add([
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'options' => $item['options']
                ]);
            }
        }

        $hold->delete();

        // Automatically trigger the proper checkout procedure instead of bypassing it
        return $this->supplierInfo();
    }

    public function editHold($holdId)
    {
        $hold = HeldPurchase::where('id', $holdId)->where('user_id', auth()->id())->first();
        if (!$hold) return;

        app('cart')->instance('purchase')->destroy();

        $this->supplier_id = $hold->supplier_id;
        $this->supplier_name = $hold->supplier_name;
        $this->warehouse_id = $hold->warehouse_id;
        $this->product_store_id = $hold->product_store_id;
        $this->transport_no = $hold->transport_no;
        $this->delivery_man = $hold->delivery_man;
        $this->purchase_date = $hold->purchase_date;
        $this->supplier_remarks = $hold->supplier_remarks;

        if ($this->warehouse_id) {
            $warehouse = Warehouse::find($this->warehouse_id);
            if ($warehouse) {
                $this->warehouse_name = $warehouse->name;
            }
        }

        if ($this->supplier_id) {
            $this->supplier_search = $this->supplier_id;
            $this->balance = $this->get_previous_balance($this->supplier_id, $this->full_date);
            session()->put('balance', $this->balance);

            // Dispatch event for Select2 and Datepicker
            $this->dispatch('update-supplier-id', $this->supplier_id);
        }

        if ($this->purchase_date) {
            $this->dispatch('update-purchase-date', date('d-m-Y', strtotime($this->purchase_date)));
        }

        if (is_array($hold->cart_data)) {
            foreach ($hold->cart_data as $item) {
                app('cart')->instance('purchase')->add([
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
        HeldPurchase::where('id', $holdId)->where('user_id', auth()->id())->delete();
    }

    //cancel order
    public function cancel()
    {
        app('cart')->instance('purchase')->destroy();
        session()->flash('supplier');
        session()->flash('pre_due');
        session()->flash('adv_pay');
        return redirect()->route('live.purchase.create');
    }

    //warehouse search
    public function warehouseSearch($value)
    {
        $this->warehouse_id = $value;
        $this->warehouse_name = Warehouse::find($this->warehouse_id)->name;
    }
    // store supplier info into session
    public function supplierInfo()
    {
        $validateData = $this->validate();
        // dd($validateData);
        $store_name = Store::find($validateData['product_store_id'])->name;
        $supplier = session()->get('supplier');
        if (!$supplier) {
            $supplier = [
                'supplier_id' => $this->supplier_id,
                'supplier_name' => $this->supplier_name,
                'address' => $this->address,
                'mobile' => $this->mobile,
                'balance' => $this->balance,
                'date' => $this->purchase_date ? date('Y-m-d', strtotime($this->purchase_date)) : $this->full_date,
                'warehouse_id' => $validateData['warehouse_id'],
                'product_store_id' => $validateData['product_store_id'],
                'product_store_name' => $store_name,
                'warehouse_name' => $this->warehouse_name,
                'supplier_remarks' => $this->supplier_remarks,
                'transport_no' => $validateData['transport_no'],
                'delivery_man' => $validateData['delivery_man'],
                'purchase_date' => $this->purchase_date ?: $this->full_date,
                'production_date' => $this->production_date,
                'expire_date' => $this->expire_date,
            ];

            session()->put('supplier', $supplier);
        } else {

            if (!$supplier) {
                $supplier = [
                    $this->supplier_id => [
                        'supplier_id' => $this->supplier_id,
                        'supplier_name' => $this->supplier_name,
                        'address' => $this->address,
                        'mobile' => $this->mobile,
                        'balance' => $this->balance,
                        'date' => $this->purchase_date ? date('Y-m-d', strtotime($this->purchase_date)) : $this->full_date,
                        'warehouse_id' => $validateData['warehouse_id'],
                        'product_store_id' => $validateData['product_store_id'],
                        'product_store_name' => $store_name,
                        'warehouse_name' => $this->warehouse_name,
                        'supplier_remarks' => $this->supplier_remarks,
                        'transport_no' => $validateData['transport_no'],
                        'delivery_man' => $validateData['delivery_man'],
                    ]
                ];

                session()->put('supplier', $supplier);
            }
        }
        return redirect()->route('live.purchase.checkout');
    }

    // brand wise search
    public function brandSearch($value)
    {
        $this->brand_id = $value;
    }

    public function get_previous_balance($supplier_id, $date)
    {
        session()->flash('balance');
        $data = SupplierLedger::where('supplier_id', $supplier_id)->where('date', '<=', $date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        return $data->balance ?? 0;
    }


    public function updatedSupplierSearch($value)
    {
        if ($value) {
            $supplier = Supplier::find($value);
            if ($supplier) {
                $this->supplier_name = $supplier->company_name;
                $this->address = $supplier->address;
                $this->mobile = $supplier->mobile;
                $this->supplier_id = $supplier->id;
                $this->balance = $this->get_previous_balance($supplier->id, $this->full_date);
                session()->put('balance', $supplier->balance);
            }
        } else {
            $this->supplier_name = '';
            $this->address = '';
            $this->mobile = '';
            $this->supplier_id = '';
            $this->balance = 0;
            session()->forget('balance');
        }
    }


    public function render()
    {
        if (!empty($this->brand_id)) {

            $products_grid = Product::where('brand_id', $this->brand_id)
                ->orderBy('sku', 'asc')
                ->get();
            if ($this->brand_id == '0') {
                $products_grid = Product::latest()->orderBy('sku', 'asc')->limit(18)->get();
            }
        } else {
            if (!empty($this->new_search)) {
                $this->searches = Product::where('name', 'Like', "%{$this->new_search}%")
                    ->orWhere('sku', 'Like', "%{$this->new_search}%")
                    ->orderBy('sku', 'asc')
                    ->limit(9)->get();
            } else {
                $this->searches = 0;
            }
            $products_grid = Product::where('name', 'Like', "%{$this->search}%")
                ->orWhere('name', 'Like', "%{$this->search}%")
                ->limit(18)
                ->orderBy('name', 'asc')
                ->get();
        }
        $products = Product::latest()
            ->orderBy('name', 'asc')
            ->get();

        $all_stocks = ProductStore::get();
        $store_stocks = $all_stocks->groupBy('product_id')->map(function ($items) {
            return [
                'name' => $items->first()->product->name,
                'code' => $items->first()->product->sku,
                'qty' => $items->sum('product_quantity'),
                'type' => $items->first()->product->size->name ?? $items->first()->product->type,
                'price' => $items->last()->purchase_price
            ];
        });
        // dd($store_stocks);
        // if ($this->source_store_id) {
        // }


        $stores = Store::where('status', 1)->get();
        $suppliers = Supplier::get();
        $warehouses = Warehouse::where('status', 1)->get();
        $brands = Brand::get();
        
        $total_held_purchases_count = HeldPurchase::where('user_id', auth()->id())->count();
        
        $held_query = HeldPurchase::where('user_id', auth()->id())->latest();
        
        if ($this->held_start_date) {
            $held_query->whereDate('created_at', '>=', date('Y-m-d', strtotime($this->held_start_date)));
        }

        if ($this->held_end_date) {
            $held_query->whereDate('created_at', '<=', date('Y-m-d', strtotime($this->held_end_date)));
        }
        
        $held_purchases = $held_query->get();

        $held_purchases = $held_query->get();

        $discount_stocks = ProductStore::where('discount_quantity', '>', 0)->with('product')->get();

        return view('livewire.purchase.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
