<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products - {{ $setting->website_name ?? 'Firoz Enterprise' }}</title>

    @if($setting && $setting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @endif

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />

    <style>
        :root {
            --primary-color: #2A3F54;
            --secondary-color: #4A6FA5;
            --accent-color: #FF6B35;
            --light-color: #F8F9FA;
            --dark-color: #233546;
            --text-color: #333333;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
            background-color: var(--light-color);
        }

        html {
            scroll-behavior: smooth;
        }

        /* Header & Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            transition: var(--transition);
        }

        .navbar-brand:hover img {
            transform: rotate(10deg);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 8px 20px !important;
            margin: 0 5px;
            border-radius: 30px;
            transition: var(--transition);
            position: relative;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: var(--transition);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 70%;
        }

        .navbar-toggler {
            border: none;
            color: white;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(rgba(42, 63, 84, 0.9), rgba(35, 53, 70, 0.9)),
                url('{{ asset("assets/images/slider/1.jpg") }}');
            background-size: cover;
            background-position: center;
            padding: 100px 0 60px;
            text-align: center;
            margin-bottom: 60px;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        .page-header h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .breadcrumb {
            background: transparent;
            justify-content: center;
            margin-top: 30px;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: var(--accent-color);
        }

        .breadcrumb-item.active {
            color: white;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Products Grid */
        .products-grid {
            padding: 80px 0;
        }

        .product-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            border: none;
            margin-bottom: 30px;
        }

        .product-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            height: 280px;
            overflow: hidden;
        }

        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img img {
            transform: scale(1.1);
        }

        .product-content {
            padding: 30px;
        }

        .product-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            min-height: 60px;
        }

        .product-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .product-features {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #666;
            font-size: 0.9rem;
        }

        .feature-item i {
            color: var(--accent-color);
            margin-right: 10px;
            font-size: 0.8rem;
        }

        /* Product Categories */
        .categories-filter {
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 40px;
            position: sticky;
            top: 100px;
        }

        .categories-filter h4 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .category-btn {
            display: block;
            width: 100%;
            padding: 12px 20px;
            margin-bottom: 10px;
            background: transparent;
            border: 2px solid #eee;
            border-radius: 8px;
            color: var(--text-color);
            text-align: left;
            transition: var(--transition);
            font-weight: 500;
        }

        .category-btn:hover,
        .category-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateX(5px);
        }

        .category-btn i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            color: white;
            padding: 70px 0 30px;
            margin-top: 100px;
        }

        .footer-column {
            margin-bottom: 40px;
        }

        .footer-column h4 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
            color: white;
        }

        .footer-column h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
        }

        .footer-logo {
            max-width: 250px;
            height: auto;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .footer-logo:hover {
            transform: scale(1.05);
        }

        .footer-column p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
        }

        .contact-info a,
        .contact-info p {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            transition: var(--transition);
        }

        .contact-info a:hover {
            color: white;
            transform: translateX(5px);
        }

        .contact-info i {
            width: 30px;
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.2);
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom p {
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .page-header h1 {
                font-size: 2.8rem;
            }

            .categories-filter {
                position: static;
                margin-bottom: 40px;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 80px 0 40px;
            }

            .page-header h1 {
                font-size: 2.2rem;
            }

            .product-img {
                height: 250px;
            }

            .footer-column:first-child {
                text-align: center;
            }

            .footer-column h4::after {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .product-content {
                padding: 20px;
            }

            .product-content h3 {
                font-size: 1.3rem;
            }
        }

        /* Utility Classes */
        .btn-primary-custom {
            background: linear-gradient(to right, var(--accent-color), #FF8B4D);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .text-gradient {
            background: linear-gradient(to right, var(--accent-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* User Profile in Nav */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile img {
            width: 35px;
            height: 35px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: var(--transition);
        }

        .user-profile:hover img {
            border-color: var(--accent-color);
        }

        /* Product Details Modal */
        .product-modal .modal-content {
            border-radius: var(--border-radius);
            border: none;
        }

        .product-modal .modal-header {
            background: var(--primary-color);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            border: none;
        }

        .product-modal .modal-body img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body id="myPage">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/brand_logo.png') }}" width="45" height="45" alt="Logo">
                <span>{{ $setting->website_name ?? 'Firoz Enterprise' }}</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link active">Products</a></li>
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link">Gallery</a></li>
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact</a></li>
                            <li class="nav-item"><a href="{{ url('/about-us') }}" class="nav-link">About Us</a></li>
                        @else
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link active">Products</a></li>
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link">Gallery</a></li>
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact</a></li>
                            <li class="nav-item"><a href="{{ url('/about-us') }}" class="nav-link">About Us</a></li>
                        @endauth
                    @endif
                </ul>

                <ul class="navbar-nav">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link user-profile">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                        class="rounded-circle">
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link btn-primary-custom">Login</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="wow fadeInDown">Our Products</h1>
            <p class="wow fadeInUp" data-wow-delay="0.3s">Discover our premium range of animal feed solutions designed
                for optimal health and growth</p>

            <nav aria-label="breadcrumb" class="wow fadeInUp" data-wow-delay="0.6s">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-grid">
        <div class="container">
            <div class="row">
                <!-- Categories Filter -->
                <div class="col-lg-3 col-md-4">
                    <div class="categories-filter wow fadeInLeft">
                        <h4>Featured Categories</h4>
                        <button class="category-btn active" data-category="all">
                            <i class="fas fa-th"></i> All Products
                        </button>
                        <button class="category-btn" data-category="poultry">
                            <i class="fas fa-kiwi-bird"></i> Poultry Feed
                        </button>
                        <button class="category-btn" data-category="aquatic">
                            <i class="fas fa-fish"></i> Aquatic Feed
                        </button>
                        <button class="category-btn" data-category="cattle">
                            <i class="fas fa-cow"></i> Cattle Feed
                        </button>
                        <button class="category-btn" data-category="specialty">
                            <i class="fas fa-star"></i> Specialty Feed
                        </button>

                        @if($categories && $categories->count() > 0)
                            <div class="mt-4">
                                <h4>Product Categories</h4>
                                @foreach($categories as $category)
                                    <button class="category-btn db-category" data-category="{{ $category->id }}"
                                        data-category-type="db">
                                        <i class="fas fa-box"></i> {{ $category->name ?? 'Category' }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-5">
                            <h4>Why Choose Us?</h4>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Premium Quality</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Expert Formulation</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Affordable Prices</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Fast Delivery</li>
                                <li class="mb-2"><i class="fas fa-check text-success"></i> Technical Support</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="col-lg-9 col-md-8">
                    <!-- Recent Products (Static) -->
                    <div class="mb-5">
                        <h3 class="mb-4 text-gradient">Featured Products</h3>
                        <div class="row">
                            @php
                                $featuredProducts = [
                                    [
                                        'image' => 'broiler.jpg',
                                        'title' => 'BROILER & SONALI FEED',
                                        'description' => 'Specialized nutrition for rapid growth and healthy meat production. Our premium broiler feed ensures optimal development.',
                                        'category' => 'poultry',
                                        'features' => ['Rapid growth formula', 'Enhanced immunity', 'Improved feed conversion']
                                    ],
                                    [
                                        'image' => 'layer.jpg',
                                        'title' => 'LAYER FEED',
                                        'description' => 'Formulated to maximize egg production, enhance shell quality, and maintain hen health throughout the laying cycle.',
                                        'category' => 'poultry',
                                        'features' => ['Increased egg production', 'Stronger egg shells', 'Better hen health']
                                    ],
                                    [
                                        'image' => 'fish.jpg',
                                        'title' => 'FISH FEED',
                                        'description' => 'Balanced nutritional formula with essential proteins, vitamins, and minerals for sustainable aquaculture success.',
                                        'category' => 'aquatic',
                                        'features' => ['High protein content', 'Floating pellets', 'Growth enhancement']
                                    ],
                                    [
                                        'image' => 'cattle.jpg',
                                        'title' => 'CATTLE FEED',
                                        'description' => 'Nutritionally rich blend supporting growth, milk production, and overall cattle health in farming operations.',
                                        'category' => 'cattle',
                                        'features' => ['Milk yield booster', 'Digestive health', 'Energy supplement']
                                    ]
                                ];
                            @endphp

                            @foreach($featuredProducts as $product)
                                <div class="col-lg-6 col-md-6 mb-4 product-item" data-category="{{ $product['category'] }}"
                                    data-product-type="featured">
                                    <div class="product-card wow fadeInUp">
                                        <div class="product-img">
                                            <img src="{{ asset('assets/images/' . $product['image']) }}"
                                                alt="{{ $product['title'] }}">
                                        </div>
                                        <div class="product-content">
                                            <h3>{{ $product['title'] }}</h3>
                                            <p>{{ $product['description'] }}</p>

                                            <div class="product-features">
                                                @foreach($product['features'] as $feature)
                                                    <div class="feature-item">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>{{ $feature }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- All Products from Database -->
                    @if(isset($products) && count($products) > 0)
                        <div>
                            <h3 class="mb-4 text-gradient">All Products</h3>
                            <div class="row" id="products-container">
                                @foreach($products as $product)
                                    <div class="col-lg-6 col-md-6 mb-4 product-item"
                                        data-category="{{ $product->category_id ?? '' }}" data-product-type="database">
                                        <div class="product-card wow fadeInUp">
                                            <div class="product-img">
                                                <img src="{{ $product->image_path }}" alt="{{ $product->name }}">
                                                @if($product->has_offer)
                                                    <span class="badge bg-danger position-absolute top-0 end-0 m-3">Offer</span>
                                                @endif
                                            </div>
                                            <div class="product-content">
                                                <h3>{{ $product->name }}</h3>

                                                @if($product->brand)
                                                    <p class="text-muted mb-1">
                                                        <i class="fas fa-truck me-2"></i>{{ $product->brand->name ?? '' }}
                                                    </p>
                                                @endif

                                                @if($product->remarks)
                                                    <p>{{ Str::limit($product->remarks, 100) }}</p>
                                                @else
                                                    <p>Premium quality feed for optimal results.</p>
                                                @endif

                                                <div class="mb-3">
                                                    @if($product->has_offer)
                                                        <span class="text-decoration-line-through text-muted me-2">
                                                            ৳{{ number_format($product->original_price, 2) }}
                                                        </span>
                                                        <span class="fw-bold text-success">
                                                            ৳{{ number_format($product->display_price, 2) }}
                                                        </span>
                                                    @else
                                                        <span class="fw-bold text-success">
                                                            ৳{{ number_format($product->display_price, 2) }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mb-2">
                                                    <span
                                                        class="badge {{ $product->stock_status == 'In Stock' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $product->stock_status }}
                                                    </span>
                                                    @if($product->total_stock > 0)
                                                        <span class="text-muted small ms-2">Qty:
                                                            {{ number_format($product->total_stock) }}</span>
                                                    @endif
                                                </div>

                                                @if($product->size)
                                                    <p class="text-muted small mb-0">
                                                        <i class="fas fa-weight me-1"></i> {{ $product->size->name ?? '' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Load More Button -->
                            @if(is_object($products) && method_exists($products, 'hasMorePages') && $products->hasMorePages())
                                <div class="text-center mt-4 mb-5">
                                    <button id="load-more-btn" class="btn btn-primary btn-lg" data-next-page="2">
                                        <i class="fas fa-spinner fa-spin d-none" id="loading-spinner"></i>
                                        <span id="load-more-text">Load More Products</span>
                                    </button>
                                </div>
                            @endif

                            @if(count($products) === 0)
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>No products available at the moment.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>No products available in the database.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <!-- Logo Column -->
                <div class="col-lg-4 col-md-6 footer-column">
                    <img src="{{ asset('assets/images/footer_logo.png') }}" alt="Logo" class="footer-logo">
                    <h4>{{ $setting->website_name ?? 'Firoz Enterprise' }}</h4>
                    <p>Your trusted partner for premium animal feed solutions since 2010.</p>
                </div>

                <!-- About Column -->
                <div class="col-lg-4 col-md-6 footer-column">
                    <h4>About Us</h4>
                    <p>We specialize in providing high-quality, nutritionally balanced feed for all types of animals.
                        Our commitment to excellence ensures healthier livestock and better yields for farmers across
                        the region.</p>
                </div>

                <!-- Contact Column -->
                <div class="col-lg-4 col-md-12 footer-column">
                    <h4>Contact Us</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> Parila Bazar, Paba, Rajshahi, Bangladesh</p>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=firozenterprise772@gmail.com"
                            target="_blank">
                            <i class="fas fa-envelope"></i> firozenterprise772@gmail.com
                        </a>
                        <a href="tel:+8801712203045">
                            <i class="fas fa-phone"></i> +880 1712 203045
                        </a>
                        <a href="tel:+8801740959772">
                            <i class="fas fa-phone"></i> +880 1740 959772
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p>&copy; {{ date('Y') }} {{ $setting->website_name ?? 'Firoz Enterprise' }}. All rights
                            reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p>Developed by: TIC Limited, Rajshahi</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>

    <script>
        // Initialize WOW.js
        new WOW().init();

        // Filter products by category
        document.addEventListener('DOMContentLoaded', function () {
            const categoryButtons = document.querySelectorAll('.category-btn');
            const productItems = document.querySelectorAll('.product-item');

            categoryButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Remove active class from all buttons
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');

                    const category = this.getAttribute('data-category');
                    const categoryType = this.getAttribute('data-category-type') || 'featured';

                    // Show/hide products based on category and type
                    productItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');
                        const itemType = item.getAttribute('data-product-type') || 'featured';

                        if (category === 'all') {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.classList.add('fadeInUp');
                            }, 50);
                        } else if (categoryType === 'featured' && itemType === 'featured' && itemCategory === category) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.classList.add('fadeInUp');
                            }, 50);
                        } else if (categoryType === 'db' && itemType === 'database' && itemCategory === category) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.classList.add('fadeInUp');
                            }, 50);
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            // Navbar scroll effect
            window.addEventListener('scroll', function () {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.padding = '10px 0';
                    navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
                } else {
                    navbar.style.padding = '15px 0';
                    navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
                }
            });

            // Load More Products Functionality
            const loadMoreBtn = document.getElementById('load-more-btn');
            const loadingSpinner = document.getElementById('loading-spinner');
            const loadMoreText = document.getElementById('load-more-text');
            const productsContainer = document.getElementById('products-container');
            let isLoading = false;

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    if (isLoading) return;

                    const nextPage = parseInt(this.getAttribute('data-next-page'));

                    loadMoreProducts(nextPage);
                });
            }

            // Infinite scroll functionality (optional - uncomment to enable)
            /*
            window.addEventListener('scroll', function() {
                if (isLoading) return;

                const loadMoreBtn = document.getElementById('load-more-btn');
                if (!loadMoreBtn) return;

                const rect = loadMoreBtn.getBoundingClientRect();
                const isVisible = rect.top >= 0 && rect.bottom <= window.innerHeight;

                if (isVisible) {
                    const nextPage = parseInt(loadMoreBtn.getAttribute('data-next-page'));
                    loadMoreProducts(nextPage);
                }
            });
            */

            function loadMoreProducts(page) {
                isLoading = true;
                loadingSpinner.classList.remove('d-none');
                loadMoreText.textContent = 'Loading...';

                fetch(`/products/load-more?page=${page}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Append new products to the container
                        data.products.forEach(product => {
                            const productHtml = createProductHtml(product);
                            productsContainer.insertAdjacentHTML('beforeend', productHtml);
                        });

                        // Update load more button
                        if (data.hasMore) {
                            loadMoreBtn.setAttribute('data-next-page', data.nextPage);
                            loadingSpinner.classList.add('d-none');
                            loadMoreText.textContent = 'Load More Products';
                        } else {
                            loadMoreBtn.style.display = 'none';
                        }

                        // Reinitialize WOW.js for new elements
                        new WOW().init();

                        isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error loading more products:', error);
                        loadingSpinner.classList.add('d-none');
                        loadMoreText.textContent = 'Load More Products';
                        isLoading = false;
                    });
            }

            function createProductHtml(product) {
                const offerBadge = product.has_offer ? '<span class="badge bg-danger position-absolute top-0 end-0 m-3">Offer</span>' : '';
                const brandHtml = product.brand ? `<p class="text-muted mb-1"><i class="fas fa-truck me-2"></i>${product.brand.name || ''}</p>` : '';
                const remarksHtml = product.remarks ? `<p>${product.remarks.length > 100 ? product.remarks.substring(0, 100) + '...' : product.remarks}</p>` : '<p>Premium quality feed for optimal results.</p>';

                const priceHtml = product.has_offer ?
                    `<span class="text-decoration-line-through text-muted me-2">৳${Number(product.original_price).toFixed(2)}</span><span class="fw-bold text-success">৳${Number(product.display_price).toFixed(2)}</span>` :
                    `<span class="fw-bold text-success">৳${Number(product.display_price).toFixed(2)}</span>`;

                const stockBadge = `<span class="badge ${product.stock_status == 'In Stock' ? 'bg-success' : 'bg-danger'}">${product.stock_status}</span>`;
                const stockQty = product.total_stock > 0 ? `<span class="text-muted small ms-2">Qty: ${Number(product.total_stock).toLocaleString()}</span>` : '';
                const sizeHtml = product.size ? `<p class="text-muted small mb-0"><i class="fas fa-weight me-1"></i> ${product.size.name || ''}</p>` : '';

                return `
                    <div class="col-lg-6 col-md-6 mb-4 product-item" data-category="${product.category_id || ''}" data-product-type="database">
                        <div class="product-card wow fadeInUp">
                            <div class="product-img">
                                <img src="${product.image_path}" alt="${product.name}">
                                ${offerBadge}
                            </div>
                            <div class="product-content">
                                <h3>${product.name}</h3>
                                ${brandHtml}
                                ${remarksHtml}
                                <div class="mb-3">
                                    ${priceHtml}
                                </div>
                                <div class="mb-2">
                                    ${stockBadge}
                                    ${stockQty}
                                </div>
                                ${sizeHtml}
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    </script>
</body>

</html>