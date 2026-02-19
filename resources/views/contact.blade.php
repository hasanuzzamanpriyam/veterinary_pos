<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact - {{ $setting->website_name ?? 'Firoz Enterprise' }}</title>

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

        /* Contact Section */
        .contact-section {
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

        /* Contact Cards */
        .contact-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 40px 30px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            text-align: center;
            margin-bottom: 30px;
            border-top: 4px solid var(--accent-color);
        }

        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .contact-icon {
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

        .contact-card:hover .contact-icon {
            transform: scale(1.1) rotate(10deg);
            background: linear-gradient(135deg, var(--accent-color), #FF8B4D);
        }

        .contact-icon i {
            font-size: 2rem;
            color: white;
        }

        .contact-card h4 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .contact-card p {
            color: #666;
            margin-bottom: 0;
        }

        .contact-card a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .contact-card a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* Contact Form */
        .contact-form-wrapper {
            background: white;
            border-radius: var(--border-radius);
            padding: 40px;
            box-shadow: var(--box-shadow);
            margin-top: 30px;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 12px 15px;
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(42, 63, 84, 0.1);
        }

        .form-control::placeholder {
            color: #999;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .btn-submit {
            background: linear-gradient(to right, var(--accent-color), #FF8B4D);
            border: none;
            color: white;
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            width: 100%;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.3);
        }

        .btn-submit i {
            margin-right: 10px;
        }

        /* Map Section */
        .map-section {
            margin-top: 80px;
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

        .map-info {
            text-align: center;
            margin-top: 30px;
        }

        .map-info h4 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        /* Business Hours */
        .hours-card {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--box-shadow);
            margin-top: 30px;
        }

        .hours-card h4 {
            color: white;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .hour-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hour-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .day {
            font-weight: 500;
        }

        .time {
            font-weight: 600;
            color: var(--accent-color);
        }

        /* Social Media */
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .social-link:hover {
            background: var(--accent-color);
            transform: translateY(-5px);
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

            .contact-form-wrapper {
                padding: 30px;
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

            .contact-card {
                padding: 30px 20px;
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

            .section-header h2 {
                font-size: 1.8rem;
            }

            .contact-form-wrapper {
                padding: 20px;
            }

            .contact-icon {
                width: 70px;
                height: 70px;
            }

            .contact-icon i {
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

        /* Success Message */
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: none;
            border-left: 4px solid #28a745;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 30px;
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
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link active">Contact</a></li>
                            <li class="nav-item"><a href="{{ url('/about-us') }}" class="nav-link">About Us</a></li>
                        @else
                            <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                            <li class="nav-item"><a href="{{ url('/products') }}" class="nav-link">Products</a></li>
                            <li class="nav-item"><a href="{{ url('/gallery') }}" class="nav-link">Gallery</a></li>
                            <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link active">Contact</a></li>
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
            <h1 class="wow fadeInDown">Contact Us</h1>
            <p class="wow fadeInUp" data-wow-delay="0.3s">Get in touch with us for inquiries, orders, or any assistance
                you may need</p>

            <nav aria-label="breadcrumb" class="wow fadeInUp" data-wow-delay="0.6s">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="section-header wow fadeInUp">
                <h2>Get In Touch</h2>
                <p>We're here to help. Reach out to us through any of the following channels</p>
            </div>

            <!-- Contact Cards -->
            <div class="row mb-5">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4>Visit Our Store</h4>
                        <p>Parila Bazar, Paba, Rajshahi, Bangladesh</p>
                        <a href="https://maps.google.com/?q=Parila+Bazar,+Paba,+Rajshahi" target="_blank">
                            <i class="fas fa-directions me-1"></i> Get Directions
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h4>Call Us</h4>
                        <p>Available during business hours</p>
                        <a href="tel:+8801712203045">
                            <i class="fas fa-phone me-1"></i> +880 1712 203045
                        </a>
                        <br>
                        <a href="tel:+8801740959772">
                            <i class="fas fa-phone me-1"></i> +880 1740 959772
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h4>Email Us</h4>
                        <p>We'll respond within 24 hours</p>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=firozenterprise772@gmail.com">
                            <i class="fas fa-envelope me-1"></i> firozenterprise772@gmail.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form & Map -->
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-6 wow fadeInUp">
                    <div class="contact-form-wrapper">
                        <h3 class="mb-4">Send Us a Message</h3>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Your Name *</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter your name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="phone" id="phone" class="form-control"
                                        placeholder="Enter your phone number" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter your email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subject *</label>
                                <select name="subject" id="subject" class="form-select" required>
                                    <option value="">Select a subject</option>
                                    <option value="product">Product Inquiry</option>
                                    <option value="order">Order Information</option>
                                    <option value="support">Technical Support</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Your Message *</label>
                                <textarea name="message" id="message" class="form-control"
                                    placeholder="Type your message here..." rows="5" required></textarea>
                            </div>

                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>

                        <!-- Business Hours -->
                        <div class="hours-card">
                            <h4><i class="far fa-clock me-2"></i> Business Hours</h4>
                            <div class="hour-item">
                                <span class="day">Monday - Friday</span>
                                <span class="time">8:00 AM - 8:00 PM</span>
                            </div>
                            <div class="hour-item">
                                <span class="day">Saturday</span>
                                <span class="time">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="hour-item">
                                <span class="day">Sunday</span>
                                <span class="time">10:00 AM - 4:00 PM</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29058.739715061314!2d88.65737015442699!3d24.438900930618047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fbf3e92c9cc499%3A0x3b06c8e93deec6a8!2sParila!5e0!3m2!1sen!2sbd!4v1706678541529!5m2!1sen!2sbd"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <div class="map-info">
                        <h4 class="mt-4">Our Location</h4>
                        <p class="lead">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            Parila Bazar, Paba, Rajshahi, Bangladesh
                        </p>
                        <p>Easy to find with ample parking space available</p>

                        <!-- Social Media Links -->
                        <div class="social-links">
                            <a href="#" class="social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#" class="social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
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

        // Contact Form Submission
        document.getElementById('contactForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Get form data
            const formData = {
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                email: document.getElementById('email').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value
            };

            // Simple validation
            if (!formData.name || !formData.phone || !formData.email || !formData.subject || !formData.message) {
                alert('Please fill in all required fields.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                return;
            }

            // Send data to backend
            fetch('{{ route("contact.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success wow fadeIn';
                        alertDiv.innerHTML = `
                        <h5><i class="fas fa-check-circle me-2"></i>Message Sent Successfully!</h5>
                        <p class="mb-0">${data.message}</p>
                    `;

                        this.parentNode.insertBefore(alertDiv, this);
                        this.reset();

                        // Remove alert after 5 seconds
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 5000);
                    } else {
                        // Handle validation errors
                        let errorMessage = data.message;
                        if (data.errors) {
                            errorMessage += '<br><ul>';
                            for (const field in data.errors) {
                                errorMessage += `<li>${data.errors[field].join(', ')}</li>`;
                            }
                            errorMessage += '</ul>';
                        }
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });

        // Phone number click handler
        document.querySelectorAll('a[href^="tel:"]').forEach(link => {
            link.addEventListener('click', function (e) {
                const number = this.getAttribute('href').replace('tel:', '');
                console.log('Calling:', number);
                // In a real mobile device, this would initiate a phone call
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
    </script>
</body>

</html>