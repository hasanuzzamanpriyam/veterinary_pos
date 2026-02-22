<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">

        @role('Super Admin|Admin|Manager|Staff')
            <ul class="nav side-menu">
                
                {{-- MESSAGE SECTION --}}
                @role('Super Admin|Admin')
                    @can('message-view')
                        <li>
                            <a href="{{ route('admin.messages.index') }}">
                                <i class="fa fa-envelope"></i>
                                <span>Messages</span>
                            </a>
                        </li>
                    @endcan
                @endrole

                {{-- ROLE MANAGEMENT --}}
                @role('Super Admin')
                    <li>
                        <a><i class="fa fa-shield"></i> <span>Role Management</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('roles.index') }}">Roles</a></li>
                            <li><a href="{{ route('permissions.index') }}">Permissions</a></li>
                            <li><a href="{{ route('users.roles.index') }}">Assign User Roles</a></li>
                        </ul>
                    </li>
                @endrole

                {{-- CUSTOMER SECTION --}}
                @can('customer-view')
                    <li>
                        <a><i class="fa fa-user"></i> <span>Customer</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('customer-create')
                                <li><a href="{{ route('live.customer.create') }}">Customer Add</a></li>
                            @endcan
                            <li><a href="{{ route('customer.index') }}">Customer List</a></li>
                            <li><a href="{{ route('customer.index.due') }}">Due Customer List</a></li>
                            <li><a href="{{ route('sales.report') }}">Customer Sales Report</a></li>
                            @can('customer-ledger')
                                <li><a href="{{ route('customer.transactions.ledger') }}">Ledger</a></li>
                            @endcan
                            @can('customer-statement')
                                <li><a href="{{ route('customer.transactions.statement') }}">Statement</a></li>
                            @endcan
                            <li><a href="{{ route('customer.gallary') }}"> <i class="fa fa-users"></i> Customer Gallary</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- SUPPLIER SECTION --}}
                @can('supplier-view')
                    <li>
                        <a><i class="fa fa-truck"></i> <span>Supplier</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('supplier-create')
                                <li><a href="{{ route('live.supplier.create') }}">Supplier Add</a></li>
                            @endcan
                            <li><a href="{{ route('supplier.index') }}">Supplier List</a></li>
                            <li><a href="{{ route('supplier.index_dues') }}">Due Supplier List</a></li>
                            @can('supplier-ledger')
                                <li><a href="{{ route('supplier.transactions.ledger') }}">Ledger</a></li>
                            @endcan
                            @can('supplier-statement')
                                <li><a href="{{ route('supplier.transactions.statement') }}">Statement</a></li>
                            @endcan
                            <li><a href="{{ route('purchase.report') }}">Purchase Report</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- PRODUCT SECTION --}}
                @can('product-view')
                    <li>
                        <a><i class="fa fa-product-hunt"></i> <span>Product</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('product-create')
                                <li><a href="{{ route('live.product.create') }}">Product Add</a></li>
                            @endcan
                            <li><a href="{{ route('product.index') }}">Product List</a></li>
                            @can('product-stock')
                                <li><a href="{{ route('product.stock') }}">Product Stock</a></li>
                                <li><a href="{{ route('product.stock.manage') }}">Manage Stock</a></li>
                            @endcan
                            @can('product-gallery')
                                <li><a href="{{ route('product.gallery') }}">Product Gallery</a></li>
                            @endcan
                            @can('product-stock-adjustment')
                                <li><a href="{{ route('live.pstockadjustment') }}">Stock Adjustment</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- PURCHASE SECTION --}}
                @can('purchase-view')
                    <li>
                        <a><i class="fa fa-cart-plus"></i> <span>Purchase orders</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('purchase-create')
                                <li><a href="{{ route('live.purchase.create') }}">Purchase Entry </a></li>
                            @endcan
                            <li>
                                <a><span>Purchase List</span> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('purchase.index', ['view' => 'v1']) }}">Purchase List-1</a></li>
                                    <li><a href="{{ route('purchase.index', ['view' => 'v2']) }}">Purchase List-2</a></li>
                                </ul>
                            </li>
                            @can('purchase-return')
                                <li><a href="{{ route('live.purchase.return.create') }}">Purchase Return</a></li>
                                <li><a href="{{ route('purchase.return.index') }}">Purchase Return List</a></li>
                            @endcan
                            @can('purchase-report')
                                <li><a href="{{ route('purchase.report') }}">Purchase Report</a></li>
                            @endcan
                            <li><a href="#">Purchase Invoice</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- SALES SECTION --}}
                @can('sales-view')
                    <li>
                        <a><i class="fa fa-shopping-cart"></i> <span>Sales orders</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('sales-create')
                                <li><a href="{{ route('live.sales.create') }}">Sale Entry</a></li>
                            @endcan
                            <li>
                                <a><span>Sales List</span> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('sales.index', ['view' => 'v1']) }}">Sale List-1</a></li>
                                    <li><a href="{{ route('sales.index', ['view' => 'v2']) }}">Sale List-2</a></li>
                                </ul>
                            </li>
                            @can('sales-return')
                                <li><a href="{{ route('live.sales.return.create') }}">Sale Return</a></li>
                                <li><a href="{{ route('sales.return.index') }}">Sale Return List</a></li>
                            @endcan
                            @can('sales-report')
                                <li><a href="{{ route('sales.report') }}">Sale Report</a></li>
                            @endcan
                            @can('sales-invoice')
                                <li><a href="{{ route('sales.invoice.search') }}">Sale Invoice Search</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- ACCOUNTS SECTION --}}
                @canany(['collection-view', 'payment-view'])
                    <li>
                        <a><i class="fa fa-credit-card"></i> <span>Accounts</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('collection-view')
                                <li><a href="{{ route('collection.create') }}">Collection</a></li>
                                <li><a href="{{ route('collection.index') }}">Collection List</a></li>
                                @can('collection-report')
                                    <li><a href="{{ route('collection.report') }}">Collection Report</a></li>
                                @endcan
                                <li><a href="{{ route('collection.memo.search') }}">Collection Memo Search</a></li>
                            @endcan
                            @can('payment-view')
                                <li><a href="{{ route('payment.create') }}">Payment</a></li>
                                <li><a href="{{ route('payment.index') }}">Payment List</a></li>
                                @can('payment-report')
                                    <li><a href="{{ route('payment.report') }}">Payment Report</a></li>
                                @endcan
                                <li><a href="{{ route('payment.memo.search') }}">Payment Memo Search</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- CASH MAINTENANCE --}}
                @can('cash-maintenance-view')
                    <li>
                        <a><i class="fa fa-credit-card"></i> <span>Cash Maintenance</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('cash-maintenance-create')
                                <li><a href="{{ route('cash_maintenance.create') }}">Add</a></li>
                            @endcan
                            <li><a href="{{ route('cash_maintenance.index') }}">List</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- EMPLOYEE SECTION --}}
                @can('employee-view')
                    <li>
                        <a><i class="fa fa-users"></i> <span>Employee Management</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('employee-create')
                                <li><a href="{{ route('employee.create') }}">Employee Add </a></li>
                            @endcan
                            <li><a href="{{ route('employee.index') }}">Employee List</a></li>
                            @can('designation-manage')
                                <li><a href="{{ route('designation.create') }}">Designation Add </a></li>
                                <li><a href="{{ route('designation.index') }}">Designation List</a></li>
                            @endcan
                            @can('employee-ledger')
                                <li><a href="{{ route('employee.statement') }}">Ledger</a></li>
                            @endcan
                            @can('employee-payment')
                                <li><a href="{{ route('employee.payment.create') }}">Payment</a></li>
                                <li><a href="{{ route('employee.payment.list') }}">Payment List</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- BONUS SECTION --}}
                @can('bonus-view')
                    <li>
                        <a><i class="fa fa-gift"></i> <span>Bonus Counting</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('bonus-create')
                                <li><a href="{{ route('bonus.create') }}">Monthly Bonus Counting Add</a></li>
                            @endcan
                            <li><a href="{{ route('bonus.index') }}">Monthly Bonus Counting List</a></li>
                            <li><a href="{{ route('monthly.bonus.index') }}">Monthly Bonus (Single)</a></li>
                            <li><a href="{{ route('monthly.bonus.all') }}">Monthly Bonus (All)</a></li>
                            @can('bonus-create')
                                <li><a href="{{ route('yearly.bonus.create') }}">Yearly Bonus Counting Add</a></li>
                            @endcan
                            <li><a href="{{ route('yearly.bonus-count.index') }}">Yearly Bonus Counting List</a></li>
                            <li><a href="{{ route('yearly.bonus.index') }}">Yearly Bonus (Single)</a></li>
                            <li><a href="{{ route('yearly.bonus.all') }}">Yearly Bonus (All)</a></li>
                            <li><a href="{{ route('cash.offer.list') }}">Cash Offer</a></li>
                            <li><a href="{{ route('total.bonus') }}">Total Bonus</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- FOLLOW UP SECTION --}}
                @canany(['follow-up-customer', 'follow-up-supplier'])
                    <li>
                        <a><i class="fa fa-refresh"></i> <span>Follow Up Date</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('follow-up-customer')
                                <li><a href="{{ route('customer.follow.create') }}">Customer Follow Up Date</a></li>
                                <li><a href="{{ route('customer.follow.index') }}">Customer Follow Up List</a></li>
                            @endcan
                            @can('follow-up-supplier')
                                <li><a href="{{ route('supplier.follow.create') }}">Supplier Follow Up Date</a></li>
                                <li><a href="{{ route('supplier.follow.index') }}">Supplier Follow Up List</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- QUOTATIONS --}}
                <li>
                    <a><i class="fa fa-quote-right"></i> <span>Quotations</span> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="#">Quotation List</a></li>
                    </ul>
                </li>

                {{-- REPORTING --}}
                @canany(['reports-daily', 'reports-monthly', 'reports-yearly', 'reports-profit-loss'])
                    <li>
                        <a><i class="fa fa-list-alt"></i> <span>Reporting and Analytics</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('sales.report') }}">Sales Report</a></li>
                            <li><a href="{{ route('purchase.report') }}">Purchases Report</a></li>
                            @can('payment-report')
                                <li><a href="{{ route('payment.report') }}">Payment Report</a></li>
                            @endcan
                            @can('collection-report')
                                <li><a href="{{ route('collection.report') }}">Collection Report</a></li>
                            @endcan
                            @can('reports-daily')
                                <li><a href="{{ route('reports.daily_summary') }}">Daily Summary</a></li>
                            @endcan
                            @can('reports-monthly')
                                <li><a href="{{ route('reports.monthly_summary') }}">Monthly Summary</a></li>
                            @endcan
                            @can('reports-yearly')
                                <li><a href="{{ route('reports.yearly_summary') }}">Yearly Summary</a></li>
                            @endcan
                            @can('reports-profit-loss')
                                <li><a href="{{ route('reports.profit_loss') }}">Profit and Loss</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- EXPENSES --}}
                @can('expense-view')
                    <li>
                        <a><i class="fa fa-money"></i> <span>Expenses</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('expense-create')
                                <li><a href="{{ route('expense.create') }}">Add New Expense</a></li>
                            @endcan
                            <li><a href="{{ route('expense.index') }}">Expenses List</a></li>
                            @can('expense-category-manage')
                                <li><a href="{{ route('expense_category.create') }}">Add New Category</a></li>
                                <li><a href="{{ route('expense_category.index') }}">Category List</a></li>
                            @endcan
                            <li><a href="{{ route('salary.expense.create') }}">Salary Expenses Add</a></li>
                            <li><a href="{{ route('salary.expense.index') }}">Salary Expenses List</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- BANK DETAILS --}}
                @can('bank-view')
                    <li>
                        <a><i class="fa fa-calendar"></i> <span>Bank Details</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="#">Transactions <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @can('transaction-create')
                                        <li><a href="{{ route('transaction.create', ['view' => 'deposit']) }}">Deposit</a></li>
                                        <li><a href="{{ route('transaction.create', ['view' => 'withdraw']) }}">Withdraw</a></li>
                                    @endcan
                                </ul>
                            </li>
                            @can('transaction-view')
                                <li><a href="{{ route('transaction.index') }}">Transaction List</a></li>
                            @endcan
                            <li><a href="{{ route('bank.index') }}">Bank List</a></li>
                            <li><a href="{{ route('bank.ledger') }}">Ledger</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- DUE SHOW --}}
                <li>
                    <a><i class="fa fa-calendar"></i> <span>Due Show</span> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{ route('customer.due.show') }}">Customer Due</a></li>
                        <li><a href="{{ route('supplier.due.show') }}">Supplier Due</a></li>
                    </ul>
                </li>

                {{-- ADDITIONAL ADD --}}
                @role('Super Admin|Admin')
                    <li>
                        <a><i class="fa fa-plus"></i> <span>Additional Add</span> <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @can('store-manage')
                                <li><a href="{{ route('store.index') }}">Store</a></li>
                            @endcan
                            @can('payment-gateway-manage')
                                <li><a href="{{ route('payment-gateways.index') }}">Payment Gateways</a></li>
                            @endcan
                            @can('category-manage')
                                <li><a href="{{ route('category.index') }}">Category</a></li>
                            @endcan
                            @can('customer-type-manage')
                                <li><a href="{{ route('customer_type.index') }}">Customer Types</a></li>
                            @endcan
                            @can('subcategory-manage')
                                <li><a href="{{ route('subcategory.index') }}">Sub Category</a></li>
                            @endcan
                            @can('brand-manage')
                                <li><a href="{{ route('brand.index') }}">Brand</a></li>
                            @endcan
                            @can('price-group-manage')
                                <li><a href="{{ route('price_group.index') }}">Price Group</a></li>
                            @endcan
                            @can('warehouse-manage')
                                <li><a href="{{ route('warehouse.index') }}">Warehouse</a></li>
                            @endcan
                            @can('product-group-manage')
                                <li><a href="{{ route('product_group.index') }}">Product Group</a></li>
                            @endcan
                            @can('size-manage')
                                <li><a href="{{ route('size.index') }}">Size</a></li>
                            @endcan
                            @can('unit-manage')
                                <li><a href="{{ route('unit.index') }}">Unit</a></li>
                            @endcan
                        </ul>
                    </li>
                @endrole

            </ul>
        @else
            <h6> hi employee</h6>
        @endrole

    </div>
</div>
<div class="sidebar-footer hidden-small">
    <a data-toggle="tooltip" data-placement="top" title="Export Database" href="{{ route('export') }}">
        <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen" id="fullscreen-btn" class="sidebar-action-btn">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock" id="lock-btn" class="sidebar-action-btn">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Logout" id="logout-btn" class="sidebar-action-btn">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
</div>