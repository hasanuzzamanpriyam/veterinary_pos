<div class="top_nav">
    <div class="nav_menu d-flex align-items-center justify-content-between px-3">
        <div class="nav toggle align-items-center">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>

            <div class="top-menu float-left d-flex">
                {{-- <li class="nav-item">
                    <a href="#" class="btn btn-primary btn-sm p-2" onclick="location.reload()">
                        <i class="fa fa-refresh"></i> Reload page </a>
                </li> --}}

                <li class="nav-item"> <a href="{{ route('live.sales.create') }}" class="btn btn-primary btn-sm"><i
                            class="fa fa-shopping-cart"></i> Sales </a>
                </li>
                <li class="nav-item"> <a href="{{ route('live.purchase.create') }}" class="btn btn-primary btn-sm"> <i
                            class="fa fa-cart-plus"></i> Purchase</a>
                </li>

                <li class="nav-item"> <a href="{{ route('payment.create') }}" class="btn btn-primary btn-sm"><i
                            class="fa fa-credit-card"></i> Payment</a>
                </li>

                <li class="nav-item"> <a href="{{ route('collection.create') }}" class="btn btn-primary btn-sm"><i
                            class="fa fa-credit-card"></i> Collection</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="dropdown-toggle btn btn-primary btn-sm" href="#" role="button" data-toggle="dropdown"
                        aria-expanded="false">
                        Due
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('customer.due.show') }}">Customer</a>
                        <a class="dropdown-item" href="{{ route('supplier.due.show') }}">Supplier</a>
                    </div>
                </li>



            </div>

        </div>
        <nav class="nav navbar-nav">
            <ul class="navbar-right d-flex align-items-center flex-row-reverse">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown"
                        data-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                        {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('user/profile') }}"> Profile Settings</a>
                        {{-- <a class="dropdown-item" href="javascript:;">
                            <span class="badge bg-red pull-right">50%</span>
                            <span>Settings</span>
                        </a> --}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                            Logout<i class="fa fa-sign-out pull-right"></i>
                        </a>
                        @role('Super Admin')
                        <a href="{{ route('users.create') }}" class="dropdown-item">Register New User</a>
                        @endrole
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>

                <li>
                    <a href="#" class="btn btn-primary btn-sm" onclick="location.reload()">
                        <i class="fa fa-refresh"></i> Reload </a>
                </li>


            </ul>
        </nav>

    </div>
    <div class="header-logo">
        {{-- Dynamic Banner (falls back to static if no dynamic banner exists) --}}
        @php
        $adminUser = \App\Models\User::role('Super Admin')->first();
        @endphp

        @if($adminUser && $adminUser->banner_photo_url)
        <div style="width: 100%; height: 200px; overflow: hidden; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <img src="{{ $adminUser->banner_photo_url }}"
                style="width:100%; height: 200px; object-fit: cover; display: block;"
                alt="Dynamic Banner">
        </div>
        @else
        <div style="margin-bottom: 20px;">
            <img src="{{ asset('assets/images/firoz_header.jpg') }}" width="100%" height="auto" alt="Firoz Enterprise" style="border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        </div>
        @endif
    </div>
</div>