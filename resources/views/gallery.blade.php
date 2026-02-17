<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gallery - {{ $setting->website_name ?? 'Firoz Enterprise' }}</title>

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

        /* Gallery Section */
        .gallery-section {
            padding: 80px 0;
        }

        .gallery-filters {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 40px;
            text-align: center;
        }

        .filter-btn {
            background: transparent;
            border: 2px solid #eee;
            color: var(--text-color);
            padding: 10px 25px;
            margin: 5px;
            border-radius: 30px;
            font-weight: 500;
            transition: var(--transition);
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .filter-btn i {
            margin-right: 8px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 60px;
        }

        .gallery-item {
            position: relative;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 280px;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            color: white;
            padding: 20px;
            transform: translateY(100%);
            transition: var(--transition);
        }

        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }

        .gallery-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .gallery-category {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Map Section */
        .map-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius);
            margin-bottom: 80px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
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

        .map-container {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            height: 450px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Gallery Modal */
        .gallery-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1100;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .gallery-modal.active {
            display: flex;
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            animation: zoomIn 0.3s ease;
        }

        .modal-image {
            width: 100%;
            height: auto;
            display: block;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.5rem;
            border: none;
            z-index: 1101;
        }

        .modal-close:hover {
            background: var(--accent-color);
            transform: rotate(90deg);
        }

        .modal-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            z-index: 1101;
        }

        .modal-nav:hover {
            background: var(--accent-color);
            transform: translateY(-50%) scale(1.1);
        }

        .modal-prev {
            left: 20px;
        }

        .modal-next {
            right: 20px;
        }

        .modal-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            color: white;
            padding: 20px;
            transform: translateY(100%);
            transition: var(--transition);
        }

        .modal-content:hover .modal-info {
            transform: translateY(0);
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .modal-category {
            font-size: 1rem;
            opacity: 0.9;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
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

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .section-header h2 {
                font-size: 2.4rem;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 80px 0 40px;
            }

            .page-header h1 {
                font-size: 2.2rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }

            .gallery-item {
                height: 250px;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .map-container {
                height: 350px;
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

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .gallery-item {
                height: 280px;
            }

            .filter-btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }

            .section-header h2 {
                font-size: 1.8rem;
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
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link">Products</a></li>
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link active">Gallery</a></li>
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact</a></li>
                            <li class="nav-item"><a href="{{ url('/about-us') }}" class="nav-link">About Us</a></li>
                        @else
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link">Products</a></li>
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link active">Gallery</a></li>
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
            <h1 class="wow fadeInDown">Photo Gallery</h1>
            <p class="wow fadeInUp" data-wow-delay="0.3s">Explore our products, facilities, and success stories through
                our visual collection</p>

            <nav aria-label="breadcrumb" class="wow fadeInUp" data-wow-delay="0.6s">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <!-- Gallery Filters -->
            <div class="gallery-filters wow fadeInUp">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-th"></i> All Photos
                </button>
                <button class="filter-btn" data-filter="products">
                    <i class="fas fa-box"></i> Products
                </button>
                <button class="filter-btn" data-filter="facility">
                    <i class="fas fa-warehouse"></i> Facility
                </button>
                <button class="filter-btn" data-filter="team">
                    <i class="fas fa-users"></i> Team
                </button>
                <button class="filter-btn" data-filter="events">
                    <i class="fas fa-calendar-alt"></i> Events
                </button>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid" id="galleryGrid">
                @php
                    $galleryItems = [
                        ['image' => 'broiler.jpg', 'title' => 'Broiler Feed Production', 'category' => 'products', 'desc' => 'High-quality broiler feed manufacturing'],
                        ['image' => 'layer.jpg', 'title' => 'Layer Feed Packaging', 'category' => 'products', 'desc' => 'Premium layer feed ready for distribution'],
                        ['image' => 'cattle.jpg', 'title' => 'Cattle Feed Storage', 'category' => 'facility', 'desc' => 'Our state-of-the-art storage facility'],
                        ['image' => 'fish.jpg', 'title' => 'Fish Feed Processing', 'category' => 'products', 'desc' => 'Advanced fish feed production line'],
                        ['image' => 'aquarium.jpg', 'title' => 'Aquarium Feed', 'category' => 'products', 'desc' => 'Specialized aquarium nutrition'],
                        ['image' => 'shrimp.jpg', 'title' => 'Shrimp Feed Quality Check', 'category' => 'team', 'desc' => 'Quality control team in action'],
                        ['image' => 'slider/1.jpg', 'title' => 'Factory Overview', 'category' => 'facility', 'desc' => 'Main production facility'],
                        ['image' => 'slider/3.jpg', 'title' => 'Team Meeting', 'category' => 'team', 'desc' => 'Strategic planning session'],
                        ['image' => 'slider/4.jpg', 'title' => 'Product Launch', 'category' => 'events', 'desc' => 'New product launch event'],
                        ['image' => 'slider/5.jpg', 'title' => 'Quality Laboratory', 'category' => 'facility', 'desc' => 'Advanced testing laboratory'],
                        ['image' => 'slider/6.jpg', 'title' => 'Distribution Center', 'category' => 'facility', 'desc' => 'Efficient distribution network'],
                        ['image' => 'slider/7.jpg', 'title' => 'Award Ceremony', 'category' => 'events', 'desc' => 'Industry recognition event']
                    ];
                @endphp

                @foreach($galleryItems as $index => $item)
                    <div class="gallery-item wow fadeInUp" data-category="{{ $item['category'] }}"
                        data-index="{{ $index }}">
                        <img src="{{ asset('assets/images/' . $item['image']) }}" alt="{{ $item['title'] }}">
                        <div class="gallery-overlay">
                            <div class="gallery-title">{{ $item['title'] }}</div>
                            <div class="gallery-category">{{ ucfirst($item['category']) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-header wow fadeInUp">
                <h2>Visit Our Location</h2>
                <p>Find us easily with the map below</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29058.739715061314!2d88.65737015442699!3d24.438900930618047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fbf3e92c9cc499%3A0x3b06c8e93deec6a8!2sParila!5e0!3m2!1sen!2sbd!4v1706678541529!5m2!1sen!2sbd"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <div class="text-center mt-4">
                        <p class="lead mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            Parila Bazar, Paba, Rajshahi, Bangladesh
                        </p>
                        <a href="https://maps.google.com/?q=Parila+Bazar,+Paba,+Rajshahi" target="_blank"
                            class="btn btn-primary btn-lg">
                            <i class="fas fa-directions me-2"></i> Get Directions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div class="gallery-modal" id="galleryModal">
        <button class="modal-close" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>

        <button class="modal-nav modal-prev" onclick="prevImage()">
            <i class="fas fa-chevron-left"></i>
        </button>

        <button class="modal-nav modal-next" onclick="nextImage()">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="modal-content">
            <img class="modal-image" id="modalImage" src="" alt="Gallery Image">
            <div class="modal-info">
                <div class="modal-title" id="modalTitle"></div>
                <div class="modal-category" id="modalCategory"></div>
                <div class="modal-desc" id="modalDesc"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <!-- Logo Column -->
                <div class="col-lg-4 col-md-6 footer-column">
                    <img src="{{ asset('assets/images/footer_logo.png') }}" alt="Logo" class="footer-logo">
                    <h4>Firoz Enterprise</h4>
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

        // Gallery Data
        const galleryItems = {!! json_encode($galleryItems) !!};
        let currentIndex = 0;

        // Filter Gallery
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                const items = document.querySelectorAll('.gallery-item');

                items.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
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

        // Open Modal
        document.querySelectorAll('.gallery-item').forEach(item => {
            item.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                openModal(index);
            });
        });

        // Modal Functions
        function openModal(index) {
            currentIndex = index;
            const item = galleryItems[index];

            document.getElementById('modalImage').src = "{{ asset('assets/images/') }}/" + item.image;
            document.getElementById('modalTitle').textContent = item.title;
            document.getElementById('modalCategory').textContent = item.category.charAt(0).toUpperCase() + item.category.slice(1);
            document.getElementById('modalDesc').textContent = item.desc;

            document.getElementById('galleryModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('galleryModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % galleryItems.length;
            openModal(currentIndex);
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
            openModal(currentIndex);
        }

        // Keyboard Navigation
        document.addEventListener('keydown', function (e) {
            if (!document.getElementById('galleryModal').classList.contains('active')) return;

            if (e.key === 'Escape') closeModal();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
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
    </script>
</body>

</html>