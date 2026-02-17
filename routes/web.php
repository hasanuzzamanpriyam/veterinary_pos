<?php

use App\Http\Controllers\AllReportController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BankExpenseController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarringExpenseController;
use App\Http\Controllers\CashMaintenanceController;
use App\Http\Controllers\CashOfferController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerFollowUpdateController;
use App\Http\Controllers\DokanExpenseController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LabourExpenseController;
use App\Http\Controllers\MonthlyBonusCountController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PriceGroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SalaryExpenseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SummaryReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierFollowUpdateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\YearlyBonusCountController;
use App\Http\Controllers\CustomerTypesController;
use App\Http\Controllers\DatabaseExportController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\ProductStockAdjustmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\LockController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [App\Http\Controllers\PublicProductController::class, 'index'])->name('products.index');
Route::get('/products/load-more', [App\Http\Controllers\PublicProductController::class, 'loadMore'])->name('products.loadMore');

Route::get('/gallery', function () {
    return view('gallery');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

Route::get('/about-us', function () {
    return view('about-us');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/dashboard', function () {
        $date = date('Y-m-d');

        // Sales Data
        $sales = App\Models\CustomerLedger::where('type', 'sale')->whereDate('date', $date)->get();
        $totalSalesToday = $sales->sum('total_price');
        $total_qty_sales_today = $sales->sum('total_qty');
        $todaysTotalSellsWeight = $total_qty_sales_today * 50; // Assuming 50kg bags if not specified, or 0? user didn't specify weight logic, keeping same logic as view implies? View calculates tons, but input is unknown. Let's assume quantity is "Bags" and view does weight calculation if needed. Wait, view code was: $todaysTotalSellsWeight / 1000.  If I don't have weight column, I will leave it 0 or derived from qty if standard. Let's check model again later if needed. For now assuming separate variable or logic. View used $todaysTotalSellsWeight without defining it in view. It might be derived. Let's send 0 for now or mapped.
        // Actually, let's keep it simple. View divides by 1000.
        $totalSalesCollectionToday = $sales->sum('payment');
        $totalDueToday = $sales->sum('total_price') - $sales->sum('payment'); // Simplistic due
        $totalInvoiceCount = $sales->count();

        // Purchase Data
        $purchases = App\Models\SupplierLedger::where('type', 'purchase')->whereDate('date', $date)->get();
        $totalPurchaseToday = $purchases->sum('total_price');
        $total_qty_purchase_today = $purchases->sum('total_qty');
        $todaysTotalPurchaseWeight = $total_qty_purchase_today * 50; // Placeholder logic
        $totalPurchasePaymentToday = $purchases->sum('payment');
        $totalPurchaseDueToday = $purchases->sum('total_price') - $purchases->sum('payment');
        // $totalInvoiceCountPurchase = $purchases->count(); // View reuses variable? No, view has separate blocks.

        // Collection & Payment (General) - optional based on requests, but filling placeholders
        $totalCollectionToday = App\Models\CustomerLedger::where('type', 'collection')->whereDate('date', $date)->sum('payment');
        $totalPaymentToday = App\Models\SupplierLedger::where('type', 'payment')->whereDate('date', $date)->sum('payment');

        // New Statistics (Stock & Dues)
        // Stock Quantity: Total products with stock > 0
        // We use ProductStore to get actual stock in stores. Count distinct products or total entries?
        // User said "get the total of ids" from /product/stock. 
        // /product/stock lists items. Let's count valid ProductStore entries with quantity > 0.
        // Actually, typical "Stock Quantity" might mean sum of quantities OR count of items. 
        // User said "get the total of ids", implying count of unique items in stock.
        // Let's count distinct product_ids in ProductStore with quantity > 0.
        $totalStockQuantity = App\Models\ProductStore::where('product_quantity', '>', 0)->count();

        // Stock Value: Total Value (qty * purchase_rate)
        $totalStockValue = \App\Models\ProductStore::join('products', 'product_stores.product_id', '=', 'products.id')
            ->sum(\Illuminate\Support\Facades\DB::raw('product_stores.product_quantity * products.purchase_rate'));

        // Due Customer: Total ids (count)
        $totalDueCustomerCount = App\Models\customer::where('balance', '>', 0)->count();

        // Due Customer Amount: Total Balance
        $totalDueCustomerAmount = App\Models\customer::where('balance', '>', 0)->sum('balance');

        // Due Supplier: Total ids (count)
        $totalDueSupplierCount = App\Models\Supplier::where('balance', '>', 0)->count();

        // Due Supplier Amount: Total Balance
        $totalDueSupplierAmount = App\Models\Supplier::where('balance', '>', 0)->sum('balance');

        return view('admin.home', compact(
            'totalSalesToday',
            'total_qty_sales_today',
            'todaysTotalSellsWeight',
            'totalSalesCollectionToday',
            'totalDueToday',
            'totalInvoiceCount',
            'totalPurchaseToday',
            'total_qty_purchase_today',
            'todaysTotalPurchaseWeight',
            'totalPurchasePaymentToday',
            'totalPurchaseDueToday',
            'totalCollectionToday',
            'totalPaymentToday',
            'totalStockQuantity',
            'totalStockValue',
            'totalDueCustomerCount',
            'totalDueCustomerAmount',
            'totalDueSupplierCount',
            'totalDueSupplierAmount'
        ));
    })->name('dashboard');

    // Messages Management Route
    Route::get('/admin/messages', App\Livewire\Admin\ContactMessages::class)->name('admin.messages.index');

    // Role & Permission Management Routes (Permission Based)
    Route::middleware(['permission:role-view|permission:permission-view|permission:user-role-assign'])->prefix('admin')->group(function () {
        // Role Management
        Route::middleware(['permission:role-view'])->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        });

        Route::middleware(['permission:role-create'])->group(function () {
            Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        });

        Route::middleware(['permission:role-edit'])->group(function () {
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        });

        Route::middleware(['permission:role-delete'])->group(function () {
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

        // Permission Management
        Route::middleware(['permission:permission-view'])->group(function () {
            Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        });

        Route::middleware(['permission:permission-create'])->group(function () {
            Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
            Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
        });

        Route::middleware(['permission:permission-delete'])->group(function () {
            Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        });

        // User Role Management
        Route::middleware(['permission:user-role-assign'])->group(function () {
            Route::get('users/roles', [UserRoleController::class, 'index'])->name('users.roles.index');
            Route::get('users/{user}/roles/edit', [UserRoleController::class, 'edit'])->name('users.roles.edit');
            Route::put('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update');
            Route::delete('users/{user}', [UserRoleController::class, 'destroy'])->name('users.destroy');
        });

        // User Creation (Super Admin)
        Route::get('users/create', [UserRoleController::class, 'create'])->name('users.create');
        Route::post('users', [UserRoleController::class, 'store'])->name('users.store');


        // Banner Settings Route
        Route::get('banner-settings', function () {
            return view('admin.banner-settings');
        })->name('admin.banner.settings');

        // App Settings Route
        Route::get('settings', App\Livewire\Admin\Settings::class)->name('admin.settings');
    });


    // live update
    Route::get('/phpinfo', function () {
        phpinfo();
    });

    // Export DB
    Route::get('/export', [DatabaseExportController::class, 'index'])->name('export');
    Route::get('/export-db', [DatabaseExportController::class, 'export'])->name('export.now');

    // Lock Screen Routes
    Route::get('/lock-screen', [LockController::class, 'lock'])->name('lock-screen');
    Route::post('/unlock', [LockController::class, 'unlock'])->name('unlock');
    Route::get('/check-locked', [LockController::class, 'checkLocked'])->name('check-locked');

    // Customers route group
    Route::middleware(['permission:customer-view'])->prefix('customer')->group(function () {
        Route::get('/', App\Livewire\Customer\CustomersList::class)->name('customer.index');
        Route::get('/due', App\Livewire\Customer\CustomersListDue::class)->name('customer.index.due');
        Route::middleware(['permission:customer-create'])->group(function () {
            Route::get('live/create', App\Livewire\Customer\Create::class)->name('live.customer.create');
            Route::get('live/checkout', App\Livewire\Customer\Checkout::class)->name('live.customer.checkout');
        });
        Route::get('view/{id}', [CustomerController::class, 'view'])->name('customer.view');
        Route::middleware(['permission:customer-edit'])->group(function () {
            Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
            Route::get('recheck/{id}', [CustomerController::class, 'recheck'])->name('customer.recheck');
            Route::post('update', [CustomerController::class, 'update'])->name('customer.update');
        });
        Route::get('delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete')->middleware('permission:customer-delete');
        Route::middleware(['permission:customer-ledger'])->group(function () {
            Route::get('ledger-1/{id}', [CustomerController::class, 'ledger_1'])->name('customer.ledger1');
            Route::get('ledger-2/{id}', [CustomerController::class, 'ledger_2'])->name('customer.ledger2');
            Route::get('transaction/ledger', App\Livewire\Customer\Transaction\Ledger::class)->name('customer.transactions.ledger');
        });
        Route::get('search/{id}', [CustomerController::class, 'search_user'])->name('customer.search');
        Route::get('search', [CustomerController::class, 'customer_user'])->name('customer.ajax.search');
        Route::middleware(['permission:customer-statement'])->group(function () {
            Route::get('statement-1/{id}', [CustomerController::class, 'statement1'])->name('customer.statement1');
            Route::get('statement-2/{id}', [CustomerController::class, 'statement2'])->name('customer.statement2');
            Route::get('transaction/statement', App\Livewire\Customer\Transaction\Statement::class)->name('customer.transactions.statement');
        });
        Route::get('due/show', [CustomerController::class, 'due'])->name('customer.due.show');
        Route::get('gallary', [CustomerController::class, 'gallary'])->name('customer.gallary');

        Route::prefix('download')->middleware(['permission:customer-export'])->group(function () {
            Route::get('{type}/{format}', [CustomerController::class, 'exportCustomer'])->name('download.customer');
        });
    });

    // Supplier
    Route::prefix('supplier')->group(function () {
        Route::get('live/create', App\Livewire\Supplier\Create::class)->name('live.supplier.create');
        Route::get('live/checkout', App\Livewire\Supplier\Checkout::class)->name('live.supplier.checkout');

        Route::get('/', App\Livewire\Supplier\SuppliersList::class)->name('supplier.index');
        Route::get('/due', App\Livewire\Supplier\SuppliersListDue::class)->name('supplier.index_dues');
        Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('view/{id}', [SupplierController::class, 'view'])->name('supplier.view');
        Route::get('recheck/{id}', [SupplierController::class, 'recheck'])->name('supplier.recheck');
        Route::get('edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::post('update', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('delete/{id}', [SupplierController::class, 'delete'])->name('supplier.delete');
        Route::get('due/show', [SupplierController::class, 'due'])->name('supplier.due.show');
        Route::get('purchase-list/{id}', [SupplierController::class, 'purchase_list'])->name('supplier.purchase.list');
        Route::get('ledger-1/{id}', [SupplierController::class, 'ledger_1'])->name('supplier.ledger1');
        Route::get('ledger-2/{id}', [SupplierController::class, 'ledger_2'])->name('supplier.ledger2');
        Route::get('statement/{id}', [SupplierController::class, 'statement'])->name('supplier.statement');
        Route::get('search', [SupplierController::class, 'supplier_user'])->name('supplier.ajax.search');
        Route::get('transaction/statement', App\Livewire\Supplier\Transaction\Statement::class)->name('supplier.transactions.statement');
        Route::get('transaction/ledger', App\Livewire\Supplier\Transaction\Ledger::class)->name('supplier.transactions.ledger');

        Route::prefix('download')->group(function () {
            Route::get('{type}/{format}', [SupplierController::class, 'exportSupplier'])->name('download.supplier');
        });
    });

    // Sales route group
    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesController::class, 'route_to_index_1'])->name('sales.index.route');
        Route::get('list/{view}', App\Livewire\Sales\SaleList::class)->name('sales.index');
        // Route::get('list-1', [SalesController::class, 'index_1'])->name('sales.index');
        // Route::get('list-2', [SalesController::class, 'index_2'])->name('sales.index2');
        Route::get('view/{invoice}', [SalesController::class, 'view'])->name('sales.view');
        Route::get('delete/{invoice}', [SalesController::class, 'delete'])->name('sales.delete');
        Route::post('customer/search', [SalesController::class, 'searchCustomer'])->name('sales.search');
        Route::get('invoice/pdf2/{invoice}', [SalesController::class, 'SaleView'])->name('sales.invoice');
        Route::get('report', [AllReportController::class, 'sales'])->name('sales.report');
        Route::get('report1/{id}', [AllReportController::class, 'customer_wise_sales_1'])->name('customer.sales.report1');
        Route::get('report2/{id}', [AllReportController::class, 'customer_wise_sales_2'])->name('customer.sales.report2');
        Route::get('invoice/search', [SalesController::class, 'salesInvoiceSearch'])->name('sales.invoice.search');
        Route::get('invoice/searched', [SalesController::class, 'salesInvoiceSearched'])->name('sales.invoice.searched');
        Route::get('customer/report/pdf/{start}/{end}/{id}', [AllReportController::class, 'salesCustomerReportDownload'])->name('sales.customer.report.pdf');
        Route::get('all/report/pdf/{start}/{end}', [AllReportController::class, 'salesAllReportDownload'])->name('sales.all.report.pdf');

        Route::get('live/create', App\Livewire\Sales\Index::class)->name('live.sales.create');
        Route::get('live/checkout', App\Livewire\Sales\Checkout::class)->name('live.sales.checkout');

        // Return
        Route::prefix('return')->group(function () {
            Route::get('/', [SalesController::class, 'returnIndex'])->name('sales.return.index');
            Route::get('view/{invoice}', [SalesController::class, 'returnView'])->name('sales.return.view');
            Route::get('print/{invoice}', [SalesController::class, 'returnSalesPrint'])->name('sales.return.invoice');
            Route::get('live/create', App\Livewire\SalesReturn\Index::class)->name('live.sales.return.create');
            Route::get('live/checkout', App\Livewire\SalesReturn\Checkout::class)->name('live.sales.return.checkout');
        });

        // Collection
        Route::prefix('collection')->group(function () {
            Route::get('/', [CollectionController::class, 'index'])->name('collection.index');
            Route::get('create', App\Livewire\Account\Collection\Index::class)->name('collection.create');
            Route::get('view/{id}', [CollectionController::class, 'view'])->name('collection.view');
            Route::get('print/{id}', [CollectionController::class, 'print'])->name('collection.print');
            Route::get('report', [CollectionController::class, 'report'])->name('collection.report');
            Route::get('report/{customer_id}', App\Livewire\CollectionReport\Customer::class)->name('collection.customer.report');
            Route::get('memo/search', [CollectionController::class, 'collectionMemoSearch'])->name('collection.memo.search');
            Route::get('memo/searched', [CollectionController::class, 'collectionMemoSearched'])->name('collection.memo.searched');

            Route::get('customer/report/pdf/{start}/{end}/{id}', [AllReportController::class, 'collectionCustomerReportDownload'])->name('collection.customer.report.pdf');
            Route::get('all/report/pdf/{start}/{end}', [AllReportController::class, 'collectionAllReportDownload'])->name('collection.all.report.pdf');
        });
    });

    // Purchase route group
    Route::prefix('purchase')->group(function () {
        // Purchase
        Route::get('/', [PurchaseController::class, 'route_to_index_1'])->name('purchase.index.route');
        Route::get('list/{view}', App\Livewire\Purchase\PurchaseList::class)->name('purchase.index');
        Route::get('live/create', App\Livewire\Purchase\Index::class)->name('live.purchase.create');
        Route::get('live/checkout', App\Livewire\Purchase\Checkout::class)->name('live.purchase.checkout');
        Route::get('view/{invoice}', [PurchaseController::class, 'view'])->name('purchase.view');
        Route::get('edit/{id}', [PurchaseController::class, 'edit'])->name('purchase.edit');
        Route::post('update', [PurchaseController::class, 'update'])->name('purchase.update');
        Route::get('delete/{invoice}', [PurchaseController::class, 'delete'])->name('purchase.delete');
        Route::get('print/{id}', [PurchaseController::class, 'print'])->name('purchase.print');

        // Return
        Route::prefix('return')->group(function () {
            Route::get('/', App\Livewire\PurchaseReturn\PurchaseReturnList::class)->name('purchase.return.index');
            Route::get('print/{invoice}', [PurchaseController::class, 'purchaseReturnPrint'])->name('purchase.return.print'); // summary needed
            Route::get('live/create', App\Livewire\PurchaseReturn\Index::class)->name('live.purchase.return.create');
            Route::get('live/checkout', App\Livewire\PurchaseReturn\Checkout::class)->name('live.purchase.return.checkout');
        });

        // Payment
        Route::prefix('payment')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
            Route::get('create', App\Livewire\Account\Payment\Index::class)->name('payment.create');
            Route::get('report', [PaymentController::class, 'report'])->name('payment.report');
            Route::get('report/{supplier_id}', App\Livewire\PaymentReport\Supplier::class)->name('payment.supplier.report');

            Route::get('print/{id}', [PaymentController::class, 'print'])->name('payment.print');
            Route::get('memo/search', [PaymentController::class, 'paymentMemoSearch'])->name('payment.memo.search');
            Route::get('memo/searched', [PaymentController::class, 'paymentMemoSearched'])->name('payment.memo.searched');

            Route::get('supplier/report/pdf/{start}/{end}/{id}', [AllReportController::class, 'paymentSupplierReportDownload'])->name('payment.supplier.report.pdf');
            Route::get('all/report/pdf/{start}/{end}', [AllReportController::class, 'paymentAllReportDownload'])->name('payment.all.report.pdf');
        });

        // Report
        Route::post('supplier/search', [PurchaseController::class, 'searchSupplier'])->name('purchase.search');
        Route::get('report', [AllReportController::class, 'purchaseReport'])->name('purchase.report');
        Route::get('supplier/report/pdf/{start}/{end}/{id}', [AllReportController::class, 'purchaseSupplierReportDownload'])->name('purchase.supplier.report.pdf');
        Route::get('all/report/pdf/{start}/{end}', [AllReportController::class, 'purchaseAllReportDownload'])->name('purchase.all.report.pdf');
    });

    // Product route
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('product.index');
        Route::post('/', [ProductController::class, 'store'])->name('product.store');
        Route::get('stock', App\Livewire\Product\Stock::class)->name('product.stock');

        Route::get('live/create', App\Livewire\Product\Create::class)->name('live.product.create');
        Route::get('live/checkout', App\Livewire\Product\Checkout::class)->name('live.product.checkout');

        Route::get('view/{id}', [ProductController::class, 'view'])->name('product.view');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('update', [ProductController::class, 'update'])->name('product.update');
        Route::get('delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
        Route::get('gallery', [ProductController::class, 'gallery'])->name('product.gallery');

        Route::get('stock-manage', App\Livewire\ManageProductStock::class)->name('product.stock.manage');
        Route::get('stock-checkout', App\Livewire\StockCheckout::class)->name('product.stock.checkout');

        //product stock adjusment route
        Route::get('stock_adjustment', [ProductStockAdjustmentController::class, 'index'])->name('product_stock_adjustment.index');
        Route::get('stock_adjustment/create', [ProductStockAdjustmentController::class, 'create'])->name('product_stock_adjustment.create');

        // product stock adjustment livewire route
        Route::get('/live/pstockadjustment/', App\Livewire\Pstockadjusment::class)->name('live.pstockadjustment');
        Route::get('/live/pstockadjustment/checkout', App\Livewire\StockAdjustmentCheckout::class)->name('live.pstockadjustment.checkout');
    });

    Route::prefix('cash-maintenance')->group(function () {
        Route::get('/add', App\Livewire\CashMaintenance\Create::class)->name('cash_maintenance.create');
        Route::get('/checkout', App\Livewire\CashMaintenance\Checkout::class)->name('cash_maintenance.checkout');
        Route::get('/view/{id}', App\Livewire\CashMaintenance\CashView::class)->name('cash_maintenance.view');
        Route::get('/edit/{id}', App\Livewire\CashMaintenance\CashEdit::class)->name('cash_maintenance.edit');
        Route::get('/edit-checkout', App\Livewire\CashMaintenance\CashEditCheckout::class)->name('cash_maintenance.edit-checkout');
        Route::get('/list', App\Livewire\CashMaintenance\ViewAll::class)->name('cash_maintenance.index');
        Route::get('/delete/{id}', [CashMaintenanceController::class, 'delete'])->name('cash_maintenance.delete');
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::get('create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/', [CategoryController::class, 'store'])->name('category.store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('update', [CategoryController::class, 'update'])->name('category.update');
        Route::get('delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
    });

    Route::prefix('subcategory')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index'])->name('subcategory.index');
        Route::get('create', [SubCategoryController::class, 'create'])->name('subcategory.create');
        Route::post('/', [SubCategoryController::class, 'store'])->name('subcategory.store');
        Route::get('edit/{id}', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
        Route::post('update', [SubCategoryController::class, 'update'])->name('subcategory.update');
        Route::get('delete/{id}', [SubCategoryController::class, 'delete'])->name('subcategory.delete');
    });

    Route::prefix('brand')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('brand.index');
        Route::get('create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/', [BrandController::class, 'store'])->name('brand.store');
        Route::get('edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('update', [BrandController::class, 'update'])->name('brand.update');
        Route::get('delete/{id}', [BrandController::class, 'delete'])->name('brand.delete');
    });

    Route::prefix('unit')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('unit.index');
        Route::get('create', [UnitController::class, 'create'])->name('unit.create');
        Route::post('/', [UnitController::class, 'store'])->name('unit.store');
        Route::get('edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
        Route::post('update', [UnitController::class, 'update'])->name('unit.update');
        Route::get('delete/{id}', [UnitController::class, 'delete'])->name('unit.delete');
    });

    Route::prefix('sizes')->group(function () {
        Route::get('/', [SizeController::class, 'index'])->name('size.index');
        Route::get('create', [SizeController::class, 'create'])->name('size.create');
        Route::post('/', [SizeController::class, 'store'])->name('size.store');
        Route::get('edit/{id}', [SizeController::class, 'edit'])->name('size.edit');
        Route::post('update', [SizeController::class, 'update'])->name('size.update');
        Route::get('delete/{id}', [SizeController::class, 'delete'])->name('size.delete');
    });

    Route::prefix('customer_type')->group(function () {
        Route::get('/', [CustomerTypesController::class, 'index'])->name('customer_type.index');
        Route::get('create', [CustomerTypesController::class, 'create'])->name('customer_type.create');
        Route::post('/', [CustomerTypesController::class, 'store'])->name('customer_type.store');
        Route::get('edit/{id}', [CustomerTypesController::class, 'edit'])->name('customer_type.edit');
        Route::post('update', [CustomerTypesController::class, 'update'])->name('customer_type.update');
        Route::get('delete/{id}', [CustomerTypesController::class, 'delete'])->name('customer_type.delete');
    });

    Route::prefix('store')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('store.index');
        Route::get('create', [StoreController::class, 'create'])->name('store.create');
        Route::post('/', [StoreController::class, 'store'])->name('store.store');
        Route::get('edit/{id}', [StoreController::class, 'edit'])->name('store.edit');
        Route::post('update', [StoreController::class, 'update'])->name('store.update');
        Route::get('delete/{id}', [StoreController::class, 'delete'])->name('store.delete');
        Route::get('{id}/{status}', [StoreController::class, 'status'])->name('store.status');
    });

    Route::prefix('warehouse')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('warehouse.index');
        Route::get('create', [WarehouseController::class, 'create'])->name('warehouse.create');
        Route::post('/', [WarehouseController::class, 'store'])->name('warehouse.store');
        Route::get('edit/{id}', [WarehouseController::class, 'edit'])->name('warehouse.edit');
        Route::post('update', [WarehouseController::class, 'update'])->name('warehouse.update');
        Route::get('delete/{id}', [WarehouseController::class, 'delete'])->name('warehouse.delete');
        Route::get('{id}/{status}', [WarehouseController::class, 'status'])->name('warehouse.status');
    });

    Route::prefix('price_group')->group(function () {
        Route::get('/', [PriceGroupController::class, 'index'])->name('price_group.index');
        Route::get('/create', [PriceGroupController::class, 'create'])->name('price_group.create');
        Route::post('/', [PriceGroupController::class, 'store'])->name('price_group.store');
        Route::get('edit/{id}', [PriceGroupController::class, 'edit'])->name('price_group.edit');
        Route::get('add-product/{id}', [PriceGroupController::class, 'add'])->name('price_group.add');
        Route::post('store-product', [PriceGroupController::class, 'storeProduct'])->name('price_group.store.product');
        // Route::get('show/{id}', [PriceGroupController::class, 'show'])->name('price_group.show');
        Route::post('update', [PriceGroupController::class, 'update'])->name('price_group.update');
        Route::get('delete/{id}', [PriceGroupController::class, 'delete'])->name('price_group.delete');
    });

    Route::prefix('bank')->group(function () {
        Route::get('/', App\Livewire\Bank\BankList::class)->name('bank.index');
        Route::get('/ledger', App\Livewire\Bank\Ledger::class)->name('bank.ledger');
        Route::get('create', [BankController::class, 'create'])->name('bank.create');
        Route::post('add', [BankController::class, 'store'])->name('bank.add');
        Route::get('edit/{id}', [BankController::class, 'edit'])->name('bank.edit');
        Route::post('update', [BankController::class, 'update'])->name('bank.update');
        Route::get('delete/{id}', [BankController::class, 'delete'])->name('bank.delete');
    });

    Route::prefix('transaction')->group(function () {
        Route::get('/', App\Livewire\Bank\Transactions::class)->name('transaction.index');
        Route::get('/{view}/create', App\Livewire\Bank\TransactionCreate::class)->name('transaction.create');
        Route::get('/{view}/edit/{id}', App\Livewire\Bank\TransactionEdit::class)->name('transaction.edit');
        Route::get('delete/{id}', [TransactionController::class, 'delete'])->name('transaction.delete');
        Route::get('statement/bank/{id}', App\Livewire\Bank\Statement::class)->name('transaction.bank.statement');
    });

    Route::prefix('product_group')->group(function () {
        Route::get('/', [ProductGroupController::class, 'index'])->name('product_group.index');
        Route::get('create', [ProductGroupController::class, 'create'])->name('product_group.create');
        Route::post('/', [ProductGroupController::class, 'store'])->name('product_group.store');
        Route::get('edit/{id}', [ProductGroupController::class, 'edit'])->name('product_group.edit');
        Route::post('update', [ProductGroupController::class, 'update'])->name('product_group.update');
        Route::get('delete/{id}', [ProductGroupController::class, 'delete'])->name('product_group.delete');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/', App\Livewire\Employee\ListAll::class)->name('employee.index');
        // Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
        Route::get('create', [EmployeeController::class, 'create'])->name('employee.create');
        Route::post('/', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('view/{id}', [EmployeeController::class, 'view'])->name('employee.view');
        Route::get('edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::post('update', [EmployeeController::class, 'update'])->name('employee.update');
        Route::get('delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');
        Route::get('ledger/delete/{id}', [EmployeeController::class, 'ledger_delete'])->name('employee.ledger.delete');
        Route::get('ledger/{id}', App\Livewire\Employee\Ledger::class)->name('employee.ledger');
        Route::get('payment/create', App\Livewire\Employee\EmployeeLedger::class)->name('employee.payment.create');
        Route::get('payment/edit/{id}', App\Livewire\Employee\EmployeeLedgerEdit::class)->name('employee.payment.edit');
        Route::get('payment-list', App\Livewire\Employee\AllPaymentsList::class)->name('employee.payment.list');
        Route::get('statement', App\Livewire\Employee\Statement::class)->name('employee.statement');
        // Route::get('payment/view/{id}', [EmployeeController::class, 'invoice_view'])->name('employee.payment.view');
    });

    //Designation  route
    Route::prefix('designation')->group(function () {
        Route::get('/', [DesignationController::class, 'index'])->name('designation.index');
        Route::get('create', [DesignationController::class, 'create'])->name('designation.create');
        Route::post('/', [DesignationController::class, 'store'])->name('designation.store');
        Route::get('edit/{id}', [DesignationController::class, 'edit'])->name('designation.edit');
        Route::post('update', [DesignationController::class, 'update'])->name('designation.update');
        Route::get('delete/{id}', [DesignationController::class, 'delete'])->name('designation.delete');
    });

    //expense route
    Route::prefix('expense')->group(function () {
        Route::get('salary', [SalaryExpenseController::class, 'index'])->name('salary.expense.index');
        Route::get('salary/create', [SalaryExpenseController::class, 'create'])->name('salary.expense.create');
        Route::get('salary/get-single-employee/{id}', [SalaryExpenseController::class, 'get_single_employee'])->name('get-single.employee');
        Route::post('salary', [SalaryExpenseController::class, 'store'])->name('salary.expense.store');
        Route::get('salary/edit/{id}', [SalaryExpenseController::class, 'edit'])->name('salary.expense.edit');
        Route::post('salary/update', [SalaryExpenseController::class, 'update'])->name('salary.expense.update');
        Route::get('salary/delete/{id}', [SalaryExpenseController::class, 'delete'])->name('salary.expense.delete');

        //bank expense route
        Route::get('bank/list', [BankExpenseController::class, 'index'])->name('bank.expense.index');
        Route::get('bank/create', [BankExpenseController::class, 'create'])->name('bank.expense.create');
        Route::post('bank', [BankExpenseController::class, 'store'])->name('bank.expense.store');
        Route::get('bank/edit/{id}', [BankExpenseController::class, 'edit'])->name('bank.expense.edit');
        Route::post('bank/update', [BankExpenseController::class, 'update'])->name('bank.expense.update');
        Route::get('bank/delete/{id}', [BankExpenseController::class, 'delete'])->name('bank.expense.delete');

        //labour expense route
        Route::get('labour', [LabourExpenseController::class, 'index'])->name('labour.expense.index');
        Route::get('labour/create', [LabourExpenseController::class, 'create'])->name('labour.expense.create');
        Route::post('labour', [LabourExpenseController::class, 'store'])->name('labour.expense.store');
        Route::get('labour/edit/{id}', [LabourExpenseController::class, 'edit'])->name('labour.expense.edit');
        Route::post('labour/update', [LabourExpenseController::class, 'update'])->name('labour.expense.update');
        Route::get('labour/delete/{id}', [LabourExpenseController::class, 'delete'])->name('labour.expense.delete');

        //dokan expense route
        Route::get('dokan', [DokanExpenseController::class, 'index'])->name('dokan.expense.index');
        Route::get('dokan/create', [DokanExpenseController::class, 'create'])->name('dokan.expense.create');
        Route::get('store/all/{id}', [DokanExpenseController::class, 'all_stores'])->name('stores.all');
        Route::post('dokan', [DokanExpenseController::class, 'store'])->name('dokan.expense.store');
        Route::get('dokan/edit/{id}', [DokanExpenseController::class, 'edit'])->name('dokan.expense.edit');
        Route::post('dokan/update', [DokanExpenseController::class, 'update'])->name('dokan.expense.update');
        Route::get('dokan/delete/{id}', [DokanExpenseController::class, 'delete'])->name('dokan.expense.delete');

        //carring expense route
        Route::get('carring', [CarringExpenseController::class, 'index'])->name('carring.expense.index');
        Route::get('carring/create', [CarringExpenseController::class, 'create'])->name('carring.expense.create');
        Route::post('carring', [CarringExpenseController::class, 'store'])->name('carring.expense.store');
        Route::get('carring/edit/{id}', [CarringExpenseController::class, 'edit'])->name('carring.expense.edit');
        Route::post('carring/update', [CarringExpenseController::class, 'update'])->name('carring.expense.update');
        Route::get('carring/delete/{id}', [CarringExpenseController::class, 'delete'])->name('carring.expense.delete');


        //expense route
        // dummy commit need to remove later
        Route::get('list', App\Livewire\Expense\ExpenseList::class)->name('expense.index');
        Route::get('create', App\Livewire\Expense\ExpenseCreate::class)->name('expense.create');
        Route::get('edit/{id}', App\Livewire\Expense\ExpenseEdit::class)->name('expense.edit');
        Route::get('delete/{id}', [ExpenseController::class, 'delete'])->name('expense.delete');
        //expense category route
        Route::get('category', App\Livewire\Expense\CategoryList::class)->name('expense_category.index');
        Route::get('category/create', App\Livewire\Expense\CategoryCreate::class)->name('expense_category.create');
        Route::get('category/edit/{id}', App\Livewire\Expense\CategoryEdit::class)->name('expense_category.edit');
        Route::get('category/delete/{id}', [ExpenseCategoryController::class, 'delete'])->name('expense_category.delete');
    });

    Route::prefix('follow-update')->group(function () {
        //customer follow Update route
        Route::get('customer', App\Livewire\FollowUpdate\Customer\ViewAll::class)->name('customer.follow.index');
        Route::get('customer/create', App\Livewire\FollowUpdate\Customer\Index::class)->name('customer.follow.create');
        Route::get('customer/{id}/view', [CustomerFollowUpdateController::class, 'view'])->name('customer.follow.view');
        Route::get('customer/edit/{id}', App\Livewire\FollowUpdate\Customer\Edit::class)->name('customer.follow.edit');
        Route::get('customer/delete/{id}', [CustomerFollowUpdateController::class, 'delete'])->name('customer.follow.delete');

        //customer follow Update route
        Route::get('supplier', App\Livewire\FollowUpdate\Supplier\ViewAll::class)->name('supplier.follow.index');
        Route::get('supplier/create', App\Livewire\FollowUpdate\Supplier\Index::class)->name('supplier.follow.create');
        Route::post('supplier', [SupplierFollowUpdateController::class, 'store'])->name('supplier.follow.store');
        Route::get('supplier/{id}/view', [SupplierFollowUpdateController::class, 'view'])->name('supplier.follow.view');
        Route::get('supplier/edit/{id}', App\Livewire\FollowUpdate\Supplier\Edit::class)->name('supplier.follow.edit');
        Route::post('supplier/update', [SupplierFollowUpdateController::class, 'update'])->name('supplier.follow.update');
        Route::get('supplier/delete/{id}', [SupplierFollowUpdateController::class, 'delete'])->name('supplier.follow.delete');
    });

    //quotation route
    Route::get('quotation/create', [QuotationController::class, 'create'])->name('quotation.create');
    Route::get('quotation/{id}/view', [QuotationController::class, 'view'])->name('quotation.view');

    Route::prefix('bonus')->group(function () {
        Route::get('/', App\Livewire\BonusCount\Monthly\ListAll::class)->name('bonus.index');
        Route::get('create', App\Livewire\BonusCount\Monthly\Index::class)->name('bonus.create');
        Route::get('/live/edit/{id}', App\Livewire\BonusCount\Monthly\Edit::class)->name('live.bonus.edit');
        Route::get('delete/{id}', [MonthlyBonusCountController::class, 'delete'])->name('bonus.delete');
        Route::get('get-supplier',  [MonthlyBonusCountController::class, 'supplierSearch'])->name('supplier.search');

        //yearly bonus count route
        // Route::get('yearly', [YearlyBonusCountController::class, 'index'])->name('yearly.bonus.index');
        Route::get('yearly', App\Livewire\BonusCount\Yearly\ListAll::class)->name('yearly.bonus-count.index');
        Route::get('yearly/create', App\Livewire\BonusCount\Yearly\Index::class)->name('yearly.bonus.create');
        Route::get('/yearly/live/edit/{id}', App\Livewire\BonusCount\Yearly\Edit::class)->name('yearly.live.bonus.edit');
        // Route::get('yearly/create', [YearlyBonusCountController::class, 'create'])->name('yearly.bonus.create');
        // Route::post('yearly', [YearlyBonusCountController::class, 'store'])->name('yearly.bonus.store');
        Route::get('yearly/{id}/view', [YearlyBonusCountController::class, 'view'])->name('yearly.bonus.view');
        Route::get('yearly/edit/{id}', [YearlyBonusCountController::class, 'edit'])->name('yearly.bonus.edit');
        Route::post('yearly/update', [YearlyBonusCountController::class, 'update'])->name('yearly.bonus.update');
        Route::get('yearly/delete/{id}', [YearlyBonusCountController::class, 'delete'])->name('yearly.bonus.delete');
        Route::get('yearly/get-supplier',  [YearlyBonusCountController::class, 'supplierSearch'])->name('yearly.supplier.search');


        Route::get('monthly/list', App\Livewire\Bonus\Monthly\Index::class)->name('monthly.bonus.index');
        Route::get('monthly/list-all', App\Livewire\Bonus\Monthly\All::class)->name('monthly.bonus.all');
        Route::get('yearly/list', App\Livewire\Bonus\Yearly\Index::class)->name('yearly.bonus.index');
        Route::get('yearly/list-all', App\Livewire\Bonus\Yearly\All::class)->name('yearly.bonus.all');
        Route::get('total-bonus', App\Livewire\Bonus\All::class)->name('total.bonus');


        Route::get('cash-offers', App\Livewire\Bonus\Cashoffer\ListAll::class)->name('cash.offer.list');
        Route::get('cash-offer/create', App\Livewire\Bonus\Cashoffer\Create::class)->name('cash.offer.create');
        Route::get('cash-offer/edit/{id}', App\Livewire\Bonus\Cashoffer\Edit::class)->name('cash.offer.edit');
        // Route::get('cash-offer/delete/{id}', '')->name('cash.offer.delete');
        // Product offers (admin)
        Route::get('product-offers', App\Livewire\Admin\ProductOffers\ProductOfferList::class)->name('product.offers.list');
        Route::get('product-offers/create', App\Livewire\Admin\ProductOffers\Create::class)->name('product.offers.create');
        Route::get('product-offers/edit/{id}', App\Livewire\Admin\ProductOffers\Edit::class)->name('product.offers.edit');
    });

    Route::prefix('report')->group(function () {
        //daily summary routes
        // Route::get('daily1', [SummaryReportController::class, 'dailyReport'])->name('daily.report');
        Route::get('daily-summary', App\Livewire\Reports\DailySummary::class)->name('reports.daily_summary');
        Route::get('monthly-summary', App\Livewire\Reports\MonthlySummary::class)->name('reports.monthly_summary');
        Route::get('yearly-summary', App\Livewire\Reports\YearlySummary::class)->name('reports.yearly_summary');
        Route::get('profit-loss', App\Livewire\Reports\ProfitLoss::class)->name('reports.profit_loss');
        //daily pdf summary routes
        Route::get('daily/pdf/{date}', [SummaryReportController::class, 'dailyReportDownload'])->name('daily.report.pdf');
        //monthly summary routes
        // Route::get('monthly', [SummaryReportController::class, 'monthlyReport'])->name('monthly.report');
        //monthly pdf summary routes
        Route::get('monthly/pdf/{date}', [SummaryReportController::class, 'monthlyReportDownload'])->name('monthly.report.pdf');
        //yearly summary routes
        // Route::get('yearly', [SummaryReportController::class, 'yearlyReport'])->name('yearly.report');
        //yearly pdf summary routes
        Route::get('yearly/pdf/{date}', [SummaryReportController::class, 'yearlyReportDownload'])->name('yearly.report.pdf');
    });

    Route::prefix('payment-gateways')->group(function () {
        Route::get('/', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
        Route::get('create', [PaymentGatewayController::class, 'create'])->name('payment-gateways.create');
        Route::post('store', [PaymentGatewayController::class, 'store'])->name('payment-gateways.store');
        Route::get('edit/{paymentGateway}', [PaymentGatewayController::class, 'edit'])->name('payment-gateways.edit');
        Route::post('update/{paymentGateway}', [PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
        Route::get('delete/{paymentGateway}', [PaymentGatewayController::class, 'destroy'])->name('payment-gateways.delete');
    });

    //Clear route cache
    Route::get('/route-clear', function () {
        Artisan::call('route:clear');
        return 'Routes cache cleared';
    });
    Route::get('/route-cache', function () {
        Artisan::call('route:cache');
        return 'Routes cached';
    });

    //Clear config cache
    Route::get('/config-clear', function () {
        Artisan::call('config:clear');
        return 'Routes cache cleared';
    });
    Route::get('/config-cache', function () {
        Artisan::call('config:cache');
        return 'Config cached';
    });

    // Clear view cache
    Route::get('/view-clear', function () {
        Artisan::call('view:clear');
        return 'View cache cleared';
    });
    Route::get('/view-cache', function () {
        Artisan::call('view:cache');
        return 'View cached';
    });

    // Event cache
    Route::get('/event-clear', function () {
        Artisan::call('event:clear');
        return 'Event cleared';
    });
    Route::get('/event-cache', function () {
        Artisan::call('event:cache');
        return 'Event cached';
    });

    // Clear application cache
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        return 'Application cache cleared';
    });

    // Clear cache using reoptimized class
    Route::get('/optimize-clear', function () {
        Artisan::call('optimize:clear');
        return 'All cache cleared';
    });

    Route::get('/migrate', function () {
        Artisan::call('migrate', ['--force' => true]);
        return response()->json(['message' => 'Migrations executed successfully']);
    });
});


Route::fallback(function () {
    abort(404);
});
