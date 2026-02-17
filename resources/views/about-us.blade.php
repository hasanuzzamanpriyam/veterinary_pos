<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us - {{ $setting->website_name ?? 'Firoz Enterprise' }}</title>

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

        /* About Section */
        .about-section {
            padding: 80px 0;
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

        /* Company Story */
        .company-story {
            background: white;
            border-radius: var(--border-radius);
            padding: 50px;
            box-shadow: var(--box-shadow);
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
        }

        .company-story::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--accent-color), var(--secondary-color));
        }

        .company-story h3 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 2rem;
        }

        .company-story p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 20px;
        }

        .highlight-text {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(74, 111, 165, 0.1));
            border-left: 4px solid var(--accent-color);
            padding: 25px;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            margin: 30px 0;
            font-style: italic;
            font-size: 1.2rem;
            color: var(--dark-color);
        }

        /* Mission Vision Cards */
        .mission-vision {
            margin: 60px 0;
        }

        .card-custom {
            background: white;
            border-radius: var(--border-radius);
            padding: 40px 30px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            text-align: center;
            border-top: 4px solid var(--accent-color);
            position: relative;
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: var(--transition);
        }

        .card-custom:hover .card-icon {
            transform: scale(1.1) rotate(10deg);
            background: linear-gradient(135deg, var(--accent-color), #FF8B4D);
        }

        .card-icon i {
            font-size: 2rem;
            color: white;
        }

        .card-custom h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .card-custom p {
            color: #666;
            line-height: 1.7;
        }

        /* Values Section */
        .values-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
            border-radius: var(--border-radius);
            margin: 80px 0;
        }

        .value-item {
            text-align: center;
            margin-bottom: 40px;
        }

        .value-icon {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .value-item:hover .value-icon {
            transform: translateY(-5px);
            background: var(--accent-color);
        }

        .value-icon i {
            font-size: 1.8rem;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .value-item:hover .value-icon i {
            color: white;
        }

        .value-item h5 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .value-item p {
            color: #666;
            font-size: 0.95rem;
        }

        /* Timeline */
        .timeline-section {
            margin: 80px 0;
        }

        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
            border-radius: 3px;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
            margin-bottom: 40px;
        }

        .timeline-item:nth-child(odd) {
            left: 0;
        }

        .timeline-item:nth-child(even) {
            left: 50%;
        }

        .timeline-content {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            position: relative;
            transition: var(--transition);
        }

        .timeline-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .timeline-year {
            position: absolute;
            width: 100px;
            height: 100px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            top: 20px;
            z-index: 1;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .timeline-item:nth-child(odd) .timeline-year {
            right: -50px;
        }

        .timeline-item:nth-child(even) .timeline-year {
            left: -50px;
        }

        .timeline-content h4 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .timeline-content p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            color: white;
            padding: 80px 0;
            border-radius: var(--border-radius);
            margin: 80px 0;
        }

        .stat-item {
            text-align: center;
            margin-bottom: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 10px;
            line-height: 1;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Team Section */
        .team-section {
            padding: 80px 0;
        }

        .team-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            margin-bottom: 30px;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .team-img {
            height: 250px;
            overflow: hidden;
        }

        .team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .team-card:hover .team-img img {
            transform: scale(1.1);
        }

        .team-info {
            padding: 25px;
            text-align: center;
        }

        .team-info h4 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .team-info .position {
            color: var(--accent-color);
            font-weight: 500;
            margin-bottom: 10px;
        }

        .team-info p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
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

            .section-header h2 {
                font-size: 2.4rem;
            }

            .timeline::after {
                left: 31px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            .timeline-item:nth-child(even) {
                left: 0;
            }

            .timeline-item:nth-child(odd) .timeline-year,
            .timeline-item:nth-child(even) .timeline-year {
                left: 20px;
                right: auto;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 80px 0 40px;
            }

            .page-header h1 {
                font-size: 2.2rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .company-story {
                padding: 30px;
            }

            .card-custom {
                padding: 30px 20px;
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

            .section-header h2 {
                font-size: 1.8rem;
            }

            .company-story {
                padding: 20px;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .card-icon {
                width: 70px;
                height: 70px;
            }

            .card-icon i {
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
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link">Gallery</a></li>
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact</a></li>
                            <li class="nav-item"><a href="{{ url('/about-us') }}" class="nav-link active">About Us</a></li>
                        @else
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link">Products</a></li>
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link">Gallery</a></li>
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact</a></li>
                            <li class="nav-item"><a href="{{ url('/about-us') }}" class="nav-link active">About Us</a></li>
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
            <h1 class="wow fadeInDown">About Us</h1>
            <p class="wow fadeInUp" data-wow-delay="0.3s">Learn about our journey, values, and commitment to quality
                animal feed solutions</p>

            <nav aria-label="breadcrumb" class="wow fadeInUp" data-wow-delay="0.6s">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About Us</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <!-- Company Story -->
            <div class="company-story wow fadeInUp">
                <h3>Our Story</h3>
                <p>
                    Founded in 2010, Firoz Enterprise began with a simple vision: to provide high-quality,
                    nutritionally balanced animal feed to farmers and pet owners across Bangladesh. What started
                    as a small family business has grown into a trusted name in the animal feed industry.
                </p>

                <div class="highlight-text wow fadeInUp" data-wow-delay="0.3s">
                    <i class="fas fa-quote-left me-2"></i>
                    Quality isn't just a standard, it's our promise to every customer and their animals.
                    <i class="fas fa-quote-right ms-2"></i>
                </div>

                <p>
                    Over the years, we've expanded our product line to include specialized feeds for poultry,
                    aquatic animals, cattle, and specialty pets. Our commitment to research and development
                    ensures that we stay at the forefront of nutritional science, providing optimal solutions
                    for animal health and growth.
                </p>

                <p>
                    Today, with our state-of-the-art production facility and dedicated team, we serve customers
                    across the region, helping them achieve better yields and healthier livestock through our
                    premium feed products.
                </p>
            </div>

            <!-- Mission & Vision -->
            <div class="mission-vision">
                <div class="section-header wow fadeInUp">
                    <h2>Our Mission & Vision</h2>
                    <p>Guiding principles that drive our commitment to excellence</p>
                </div>

                <div class="row">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="card-custom">
                            <div class="card-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h4>Our Mission</h4>
                            <p>
                                To provide innovative, high-quality animal feed solutions that enhance livestock
                                health, improve farm productivity, and contribute to sustainable agriculture
                                practices across Bangladesh. We aim to be the most trusted partner for farmers
                                and pet owners by delivering exceptional value through our products and services.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="card-custom">
                            <div class="card-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h4>Our Vision</h4>
                            <p>
                                To be the leading animal feed provider in South Asia, recognized for our
                                commitment to quality, innovation, and customer satisfaction. We envision
                                a future where every farmer has access to premium feed solutions that
                                maximize their returns while ensuring animal welfare and environmental
                                sustainability.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Core Values -->
            <div class="values-section">
                <div class="section-header wow fadeInUp">
                    <h2>Our Core Values</h2>
                    <p>The principles that define who we are and how we work</p>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <h5>Quality Excellence</h5>
                            <p>Uncompromising quality in every product we manufacture</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h5>Customer Focus</h5>
                            <p>Building lasting relationships through exceptional service</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h5>Innovation</h5>
                            <p>Constantly improving through research and development</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h5>Integrity</h5>
                            <p>Transparent and ethical business practices</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline-section">
                <div class="section-header wow fadeInUp">
                    <h2>Our Journey</h2>
                    <p>Milestones in our growth and development</p>
                </div>

                <div class="timeline">
                    <div class="timeline-item wow fadeInUp">
                        <div class="timeline-year">2010</div>
                        <div class="timeline-content">
                            <h4>Foundation</h4>
                            <p>Started as a small family business with a focus on poultry feed</p>
                        </div>
                    </div>

                    <div class="timeline-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="timeline-year">2013</div>
                        <div class="timeline-content">
                            <h4>Expansion</h4>
                            <p>Expanded product line to include fish and cattle feed</p>
                        </div>
                    </div>

                    <div class="timeline-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="timeline-year">2016</div>
                        <div class="timeline-content">
                            <h4>Modern Facility</h4>
                            <p>Moved to a new, state-of-the-art production facility</p>
                        </div>
                    </div>

                    <div class="timeline-item wow fadeInUp" data-wow-delay="0.6s">
                        <div class="timeline-year">2019</div>
                        <div class="timeline-content">
                            <h4>Quality Certification</h4>
                            <p>Achieved ISO certification for quality management</p>
                        </div>
                    </div>

                    <div class="timeline-item wow fadeInUp" data-wow-delay="0.8s">
                        <div class="timeline-year">2023</div>
                        <div class="timeline-content">
                            <h4>Regional Expansion</h4>
                            <p>Expanded distribution network across South Asia</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="stats-section wow fadeInUp">
                <div class="section-header">
                    <h2 style="color: white;">By The Numbers</h2>
                    <p style="color: rgba(255, 255, 255, 0.9);">Our impact in numbers</p>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="17">0</div>
                            <div class="stat-label">Years Experience</div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="53">0</div>
                            <div class="stat-label">Products</div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="5125">0</div>
                            <div class="stat-label">Happy Customers</div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="101">0</div>
                            <div class="stat-label">Distributors</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="team-section">
                <div class="section-header wow fadeInUp">
                    <h2>Meet Our Leadership</h2>
                    <p>The dedicated team behind our success</p>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="team-card">
                            <div class="team-img">
                                {{-- <img src="{{ asset('assets/images/slider/3.jpg') }}" alt="Founder"> --}}
                            </div>
                            <div class="team-info">
                                <h4>Md. Firoz Alam</h4>
                                <div class="position">Founder & CEO</div>
                                <p>With over 15 years of experience in the animal feed industry, Firoz leads our company
                                    with vision and dedication.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="team-card">
                            <div class="team-img">
                                {{-- <img src="{{ asset('assets/images/slider/4.jpg') }}" alt="Operations Manager"> --}}
                            </div>
                            <div class="team-info">
                                <h4>Md. Rahman</h4>
                                <div class="position">Operations Manager</div>
                                <p>Ensuring smooth production and delivery operations with 10 years of manufacturing
                                    experience.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="team-card">
                            <div class="team-img">
                                {{-- <img src="{{ asset('assets/images/slider/5.jpg') }}" alt="Quality Control Head">
                                --}}
                            </div>
                            <div class="team-info">
                                <h4>Dr. Amina Khan</h4>
                                <div class="position">Quality Control Head</div>
                                <p>Ph.D. in Animal Nutrition, ensuring all products meet the highest quality standards.
                                </p>
                            </div>
                        </div>
                    </div>
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

        // Animated counters
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200; // The lower the slower

            counters.forEach(counter => {
                const animate = () => {
                    const value = +counter.getAttribute('data-count');
                    const data = +counter.innerText.replace('+', '');

                    const time = value / speed;
                    if (data < value) {
                        counter.innerText = Math.ceil(data + time) + (counter.getAttribute('data-count') >= 1000 ? '+' : '');
                        setTimeout(animate, 1);
                    } else {
                        counter.innerText = value + (counter.getAttribute('data-count') >= 1000 ? '+' : '');
                    }
                };

                animate();
            });
        }

        // Trigger counters when in view
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(animateCounters, 500);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe stats section
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }

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