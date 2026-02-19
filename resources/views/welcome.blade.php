<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page-title') - {{ $setting->website_name ?? 'Firoz Enterprise' }}</title>

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

        .nav-link:hover, .nav-link.active {
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

        .nav-link:hover::after, .nav-link.active::after {
            width: 70%;
        }

        .navbar-toggler {
            border: none;
            color: white;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Hero Slider */
        .slider-area {
            margin-top: 0;
            overflow: hidden;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .carousel-item {
            height: 85vh;
            min-height: 500px;
            position: relative;
        }

        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .carousel-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.6));
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 20px;
            transition: var(--transition);
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-indicators button {
            width: 12px !important;
            height: 12px !important;
            border-radius: 50%;
            margin: 0 8px !important;
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
        }

        .carousel-indicators button.active {
            background-color: var(--accent-color);
            transform: scale(1.2);
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin: 80px 0 50px;
            position: relative;
        }

        .section-header h2 {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--accent-color), var(--secondary-color));
            border-radius: 2px;
        }

        .section-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 20px auto 0;
        }

        /* Products Section */
        .products-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
            height: 250px;
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
            padding: 25px;
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

        /* Auto-scrolling products */
        .products-scroll-container {
            overflow: hidden;
            position: relative;
            padding: 20px 0;
        }

        .products-scroll-wrapper {
            display: flex;
            animation: scroll 40s linear infinite;
        }

        .product-item {
            flex: 0 0 350px;
            padding: 0 15px;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-350px * 6));
            }
        }

        /* Map & Gallery Section */
        .map-gallery-section {
            padding: 80px 0;
            background: white;
        }

        .map-container {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            height: 500px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .gallery-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .gallery-item {
            border-radius: var(--border-radius);
            overflow: hidden;
            position: relative;
            cursor: pointer;
            height: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.7));
            opacity: 0;
            transition: var(--transition);
        }

        .gallery-item:hover::after {
            opacity: 1;
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            color: white;
            padding: 70px 0 30px;
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
            margin-bottom: 20px;
            /* filter: brightness(0) invert(1); */ /* Temporarily removed to check visibility */
        }

        .contact-info a, .contact-info p {
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
            .carousel-item {
                height: 60vh;
            }

            .gallery-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-item {
                flex: 0 0 300px;
            }
        }

        @media (max-width: 768px) {
            .section-header h2 {
                font-size: 2.2rem;
            }

            .gallery-container {
                grid-template-columns: 1fr;
            }

            .carousel-item {
                height: 50vh;
            }

            .map-container {
                height: 400px;
            }
        }

        @media (max-width: 576px) {
            .product-item {
                flex: 0 0 280px;
            }

            .carousel-item {
                height: 40vh;
                min-height: 300px;
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

        /* Modal for Gallery Images */
        .gallery-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1100;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .gallery-modal.active {
            display: flex;
        }

        .modal-image {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            animation: zoomIn 0.3s ease;
        }

        .modal-close {
            position: absolute;
            top: 30px;
            right: 30px;
            color: white;
            font-size: 2.5rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--accent-color);
            transform: rotate(90deg);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
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
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link active">Home</a></li>
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link">Products</a></li>
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link">Gallery</a></li>
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact</a></li>
                            <li class="nav-item"><a href="{{ url('/about-us') }}" class="nav-link">About Us</a></li>
                        @else
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link active">Home</a></li>
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link">Products</a></li>
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
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle">
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

    <!-- Hero Slider -->
    <section class="slider-area">
        <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="4"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="5"></button>
            </div>

            <div class="carousel-inner">
                @foreach([1,3,4,5,6,7] as $index => $slide)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('assets/images/slider/' . $slide . '.jpg') }}" class="d-block w-100" alt="Slide {{ $index + 1 }}">
                </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <div class="container">
            <div class="section-header">
                <h2>Our Premium Products</h2>
                <p>High-quality animal feed solutions for optimal growth and health</p>
            </div>

            <div class="products-scroll-container">
                <div class="products-scroll-wrapper">
                    @php
                        $products = [
                            [
                                'image' => 'broiler.jpg',
                                'title' => 'BROILER & SONALI FEED',
                                'description' => 'Specialized nutrition for rapid growth and healthy meat production. Our premium broiler feed ensures optimal development.'
                            ],
                            [
                                'image' => 'layer.jpg',
                                'title' => 'LAYER FEED',
                                'description' => 'Formulated to maximize egg production, enhance shell quality, and maintain hen health throughout the laying cycle.'
                            ],
                            [
                                'image' => 'fish.jpg',
                                'title' => 'FISH FEED',
                                'description' => 'Balanced nutritional formula with essential proteins, vitamins, and minerals for sustainable aquaculture success.'
                            ],
                            [
                                'image' => 'cattle.jpg',
                                'title' => 'CATTLE FEED',
                                'description' => 'Nutritionally rich blend supporting growth, milk production, and overall cattle health in farming operations.'
                            ],
                            [
                                'image' => 'shrimp.jpg',
                                'title' => 'SHRIMP FEED',
                                'description' => 'Specialized diet enhancing growth, health, and reproductive performance in shrimp aquaculture.'
                            ],
                            [
                                'image' => 'aquarium.jpg',
                                'title' => 'AQUARIUM FEED',
                                'description' => 'Promotes vibrant colors, growth, and vitality for your aquatic pets with balanced nutrition.'
                            ]
                        ];
                    @endphp

                    <!-- First Set -->
                    @foreach($products as $product)
                    <div class="product-item">
                        <div class="product-card wow fadeInUp">
                            <div class="product-img">
                                <img src="{{ asset('assets/images/' . $product['image']) }}" alt="{{ $product['title'] }}">
                            </div>
                            <div class="product-content">
                                <h3>{{ $product['title'] }}</h3>
                                <p>{{ $product['description'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Duplicate Set for Seamless Loop -->
                    @foreach($products as $product)
                    <div class="product-item">
                        <div class="product-card wow fadeInUp">
                            <div class="product-img">
                                <img src="{{ asset('assets/images/' . $product['image']) }}" alt="{{ $product['title'] }}">
                            </div>
                            <div class="product-content">
                                <h3>{{ $product['title'] }}</h3>
                                <p>{{ $product['description'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Map & Gallery Section -->
    <section class="map-gallery-section" id="gallery">
        <div class="container">
            <div class="row g-5">
                <!-- Map Column -->
                <div class="col-lg-6">
                    <div class="section-header text-start">
                        <h2>Our Location</h2>
                        <p>Visit us at our convenient location</p>
                    </div>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29058.739715061314!2d88.65737015442699!3d24.438900930618047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fbf3e92c9cc499%3A0x3b06c8e93deec6a8!2sParila!5e0!3m2!1sen!2sbd!4v1706678541529!5m2!1sen!2sbd" allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>

                <!-- Gallery Column -->
                <div class="col-lg-6">
                    <div class="section-header text-start">
                        <h2>Photo Gallery</h2>
                        <p>Explore our products and facilities</p>
                    </div>
                    <div class="gallery-container">
                        @php
                            $galleryImages = [
                                'broiler.jpg', 'layer.jpg', 'cattle.jpg',
                                'fish.jpg', 'aquarium.jpg', 'shrimp.jpg',
                                'slider/1.jpg', 'slider/3.jpg', 'slider/4.jpg',
                                'slider/5.jpg', 'slider/6.jpg', 'slider/7.jpg'
                            ];
                        @endphp

                        @foreach($galleryImages as $image)
                        <div class="gallery-item" onclick="openModal('{{ asset('assets/images/' . $image) }}')">
                            <img src="{{ asset('assets/images/' . $image) }}" alt="Gallery Image">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div class="gallery-modal" id="galleryModal" onclick="closeModal()">
        <span class="modal-close">&times;</span>
        <img class="modal-image" id="modalImage" src="" alt="Full Size">
    </div>

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
                    <p>We specialize in providing high-quality, nutritionally balanced feed for all types of animals. Our commitment to excellence ensures healthier livestock and better yields for farmers across the region.</p>
                </div>

                <!-- Contact Column -->
                <div class="col-lg-4 col-md-12 footer-column">
                    <h4>Contact Us</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> Parila Bazar, Paba, Rajshahi, Bangladesh</p>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=firozenterprise772@gmail.com" target="_blank">
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
                        <p>&copy; {{ date('Y') }} {{ $setting->website_name ?? 'Firoz Enterprise' }}. All rights reserved.</p>
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

        // Gallery Modal Functions
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('galleryModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('galleryModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.padding = '10px 0';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
            } else {
                navbar.style.padding = '15px 0';
                navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            }
        });

        // Pause animation on hover for product scroll
        const scrollWrapper = document.querySelector('.products-scroll-wrapper');
        if (scrollWrapper) {
            scrollWrapper.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });

            scrollWrapper.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
        }

        // Initialize carousel
        document.addEventListener('DOMContentLoaded', function() {
            var carouselElement = document.querySelector('#mainCarousel');
            if (carouselElement) {
                var carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 3000,
                    ride: 'carousel'
                });

                // Make control buttons functional
                document.querySelector('.carousel-control-prev').addEventListener('click', function() {
                    carousel.prev();
                });

                document.querySelector('.carousel-control-next').addEventListener('click', function() {
                    carousel.next();
                });

                // Make indicators functional
                var indicators = document.querySelectorAll('.carousel-indicators button');
                indicators.forEach(function(indicator, index) {
                    indicator.addEventListener('click', function() {
                        carousel.to(index);
                    });
                });
            }
        });
    </script>
</body>
</html>
