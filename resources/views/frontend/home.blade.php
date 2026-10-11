@extends('frontend.layouts.app')

@section('title', 'MSG Group | Property & Project Management')

@section('content')


{{-- =========================================================
     HERO
========================================================= --}}

<section class="hero">

    <div class="container hero-grid">

        <div class="hero-left">

            <span class="badge-sub">
                Smarter Property & Project Management
            </span>

            <h1 class="hero-title">
                Manage Every Asset,
                Space &amp; Opportunity
            </h1>

            <p class="hero-desc">
                A complete platform to manage property development,
                projects, construction, contracts, procurement,
                assets and operations — all in one place.
            </p>


            <div class="hero-buttons">

                <a href="{{ url('/login') }}"
                   class="btn-primary">

                    Get Started

                    <i class="ri-arrow-right-line"></i>

                </a>


                <a href="#features"
                   class="btn-watch">

                    <i class="ri-play-circle-line"></i>

                    Explore Platform

                </a>

            </div>


            <div class="feature-list">


                {{-- Feature 1 --}}
                <div class="feature-item">

                    <div class="feature-icon">

                        <i class="bi bi-briefcase"></i>

                    </div>

                    <div class="feature-text">

                        <span>Project</span>

                        <span>Management</span>

                    </div>

                </div>


                {{-- Feature 2 --}}
                <div class="feature-item">

                    <div class="feature-icon">

                        <i class="bi bi-graph-up-arrow"></i>

                    </div>

                    <div class="feature-text">

                        <span>Operational</span>

                        <span>Efficiency</span>

                    </div>

                </div>


                {{-- Feature 3 --}}
                <div class="feature-item">

                    <div class="feature-icon">

                        <i class="bi bi-bezier2"></i>

                    </div>

                    <div class="feature-text">

                        <span>Asset</span>

                        <span>Optimization</span>

                    </div>

                </div>


            </div>

        </div>


        {{-- Dashboard Mockup --}}

        <div class="hero-right hero-mockup-wrap">

            <div class="mockup-wrap">

                <img
                    src="{{ asset('public/frontend/images/mockup.png') }}"
                    alt="Project Management Dashboard">

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     STATISTICS
========================================================= --}}

<section class="mall-stats-section">

    <div class="container px-4 px-lg-5">

        <div class="mall-stats-container">


            <div class="mall-stat">

                <div class="mall-stat-icon building">

                    <i class="bi bi-building"></i>

                </div>

                <div>

                    <p class="mall-stat-value">
                        79
                    </p>

                    <p class="mall-stat-label">
                        Total Projects
                    </p>

                </div>

            </div>


            <div class="mall-stat">

                <div class="mall-stat-icon tenants">

                    <i class="bi bi-people"></i>

                </div>

                <div>

                    <p class="mall-stat-value">
                        181
                    </p>

                    <p class="mall-stat-label">
                        Active Stakeholders
                    </p>

                </div>

            </div>


            <div class="mall-stat">

                <div class="mall-stat-icon contract">

                    <i class="bi bi-currency-rupee"></i>

                </div>

                <div>

                    <p class="mall-stat-value">
                        ₹59.8Cr
                    </p>

                    <p class="mall-stat-label">
                        Project Value
                    </p>

                </div>

            </div>


            <div class="mall-stat">

                <div class="mall-stat-icon update">

                    <i class="bi bi-clock"></i>

                </div>

                <div>

                    <p class="mall-stat-value">
                        98%
                    </p>

                    <p class="mall-stat-label">
                        Operational Visibility
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- =========================================================
     MODULES
========================================================= --}}

<section
    class="modules-section"
    id="features">

    <div class="container">


        <div class="modules-header">

            <p class="modules-eyebrow">
                CORE MODULES
            </p>

            <h1 class="modules-title">
                Everything You Need to Manage Modern Projects
            </h1>

            <p class="modules-description">
                A unified platform for property development,
                project management, construction, contracts,
                procurement and asset operations.
            </p>

        </div>


        <div class="modules-list">


            {{-- Asset Management --}}
            <div class="module-item">

                <div class="module-card">

                    <div class="module-icon asset">

                        <i class="bi bi-buildings"></i>

                    </div>

                    <div class="module-content">

                        <h2 class="module-title">
                            Asset Management
                        </h2>

                        <p class="module-text">
                            Manage properties, buildings,
                            floors, zones, units and assets.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Land & Feasibility --}}
            <div class="module-item">

                <div class="module-card">

                    <div class="module-icon tenant">

                        <i class="bi bi-map"></i>

                    </div>

                    <div class="module-content">

                        <h2 class="module-title">
                            Land &amp; Feasibility
                        </h2>

                        <p class="module-text">
                            Manage land acquisition,
                            feasibility studies and investment decisions.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Design Management --}}
            <div class="module-item">

                <div class="module-card">

                    <div class="module-icon billing">

                        <i class="bi bi-pencil-ruler"></i>

                    </div>

                    <div class="module-content">

                        <h2 class="module-title">
                            Design Management
                        </h2>

                        <p class="module-text">
                            Coordinate consultants, drawings,
                            designs, submissions and approvals.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Construction --}}
            <div class="module-item">

                <div class="module-card">

                    <div class="module-icon maintenance">

                        <i class="bi bi-hard-hat"></i>

                    </div>

                    <div class="module-content">

                        <h2 class="module-title">
                            Construction Management
                        </h2>

                        <p class="module-text">
                            Track site progress, quality,
                            HSE, manpower, materials and equipment.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Project Tracking --}}
            <div class="module-item">

                <div class="module-card">

                    <div class="module-icon project">

                        <i class="bi bi-bar-chart-fill"></i>

                    </div>

                    <div class="module-content">

                        <h2 class="module-title">
                            Project Tracking
                        </h2>

                        <p class="module-text">
                            Monitor project schedules,
                            milestones, costs, risks and progress.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Contract Management --}}
            <div class="module-item">

                <div class="module-card">

                    <div class="module-icon reports">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>

                    <div class="module-content">

                        <h2 class="module-title">
                            Contract Management
                        </h2>

                        <p class="module-text">
                            Manage contracts, variations,
                            claims, payments and obligations.
                        </p>

                    </div>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- =========================================================
     WHY CHOOSE
========================================================= --}}

<section
    class="why-section"
    id="about">

    <div class="container">

        <div class="why-wrapper">


            {{-- LEFT IMAGE --}}

            <div class="action-video">

                <img
                    src="https://images.unsplash.com/photo-1519567241046-7f570eee3ce6?auto=format&fit=crop&w=800&q=80"
                    alt="Property Development"
                    class="action-image">

                <div class="action-overlay"></div>


                <div class="textonvid">

                    <div class="play-button">

                        <i class="bi bi-play-fill"></i>

                    </div>


                    <div class="action-content">

                        <h3>
                            See the Platform in Action
                        </h3>

                        <p>
                            See how our platform helps teams
                            manage every project, asset and opportunity.
                        </p>

                    </div>

                </div>

            </div>


            {{-- RIGHT CONTENT --}}

            <div class="why-content">

                <div class="why-main">

                    <span class="why-label">
                        WHY CHOOSE OUR PLATFORM
                    </span>


                    <h2>
                        Built for Efficiency,
                        Designed for Growth.
                    </h2>


                    <div class="benefits-list">


                        <div class="benefit-item">

                            <span class="check-icon">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <span>
                                All-in-one property and project platform
                            </span>

                        </div>


                        <div class="benefit-item">

                            <span class="check-icon">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <span>
                                Real-time project data and analytics
                            </span>

                        </div>


                        <div class="benefit-item">

                            <span class="check-icon">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <span>
                                Secure and scalable infrastructure
                            </span>

                        </div>


                        <div class="benefit-item">

                            <span class="check-icon">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <span>
                                Simple and modern interface
                            </span>

                        </div>


                        <div class="benefit-item">

                            <span class="check-icon">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <span>
                                Centralized document and workflow management
                            </span>

                        </div>


                        <div class="benefit-item">

                            <span class="check-icon">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <span>
                                Complete project lifecycle visibility
                            </span>

                        </div>


                    </div>

                </div>


                {{-- QUOTE --}}

                <div class="quote-card">

                    <div class="quote-mark">
                        “
                    </div>

                    <p>
                        “Building<br>
                        Better<br>
                        Projects<br>
                        Together”
                    </p>

                    <div class="quote-line"></div>

                </div>


            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     TESTIMONIALS
========================================================= --}}

<section class="testimonials-section">

    <div class="container">


        <div class="center-heading-block">

            <span class="section-kicker">
                REAL RESULTS
            </span>

            <h2 class="section-main-title">
                Trusted by Developers &amp; Project Teams
            </h2>

            <p class="section-desc">
                See what project teams say about their
                experience using the platform.
            </p>

        </div>


        <div class="testimonials-row">


            {{-- Testimonial 1 --}}

            <div class="testimonial-box">

                <div class="testi-top">

                    <img
                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80"
                        class="testi-avatar"
                        alt="Project Director">


                    <div class="stars-price-wrap">

                        <div class="testi-stars">

                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>

                        </div>


                        <p class="testi-quote">
                            The platform has completely transformed
                            the way we manage our projects and properties.
                        </p>


                        <div class="testi-author-info">

                            <h5>
                                Ahmed Hassan
                            </h5>

                            <p>
                                Project Director
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Testimonial 2 --}}

            <div class="testimonial-box">

                <div class="testi-top">

                    <img
                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80"
                        class="testi-avatar"
                        alt="Operations Head">


                    <div class="stars-price-wrap">

                        <div class="testi-stars">

                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>

                        </div>


                        <p class="testi-quote">
                            From planning to construction,
                            everything is now organized in one place.
                        </p>


                        <div class="testi-author-info">

                            <h5>
                                Fatima Ali
                            </h5>

                            <p>
                                Operations Head
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Testimonial 3 --}}

            <div class="testimonial-box">

                <div class="testi-top">

                    <img
                        src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80"
                        class="testi-avatar"
                        alt="Technology Director">


                    <div class="stars-price-wrap">

                        <div class="testi-stars">

                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>

                        </div>


                        <p class="testi-quote">
                            The analytics and reporting provide
                            complete visibility across our projects.
                        </p>


                        <div class="testi-author-info">

                            <h5>
                                Omar Yusuf
                            </h5>

                            <p>
                                Technology Director
                            </p>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- =========================================================
     PRICING
========================================================= --}}

<section
    class="pricing-section"
    id="pricing">

    <div class="container">


        <div class="center-heading-block">

            <span class="section-kicker">
                PRICING PLANS
            </span>

            <h2 class="section-main-title">
                Simple, Transparent Pricing
            </h2>

            <p class="section-desc">
                Choose the plan that fits your business needs.
            </p>

        </div>


        <div class="pricing-switch-container">

            <div class="toggle-pills">

                <button
                    class="pill-btn active"
                    type="button">

                    Monthly

                </button>


                <button
                    class="pill-btn"
                    type="button">

                    Yearly

                    <span class="save-green-pill">
                        Save 20%
                    </span>

                </button>

            </div>

        </div>


        <div class="pricing-cards-grid">


            {{-- Starter --}}

            <div class="price-plan-card">

                <div>

                    <div class="plan-card-header">

                        <div class="plan-icon-pill">

                            <i class="ri-user-line"></i>

                        </div>


                        <div class="plan-titles">

                            <h4>
                                Starter
                            </h4>

                            <p>
                                Perfect for small projects
                                and growing businesses
                            </p>

                        </div>

                    </div>


                    <div class="plan-price-num">

                        ₹15,999

                        <span>
                            / month
                        </span>

                    </div>


                    <ul class="plan-bullets">

                        <li>
                            <i class="ri-check-line"></i>
                            Up to 5 projects
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            Basic features
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            Email support
                        </li>

                    </ul>

                </div>


                <a
                    href="{{ url('/login') }}"
                    class="btn-plan-action">

                    Get Started

                </a>

            </div>



            {{-- Professional --}}

            <div class="price-plan-card highlighted">

                <div class="popular-ribbon">
                    Most Popular
                </div>


                <div>

                    <div class="plan-card-header">

                        <div class="plan-icon-pill">

                            <i class="ri-vip-crown-line"></i>

                        </div>


                        <div class="plan-titles">

                            <h4>
                                Professional
                            </h4>

                            <p>
                                Ideal for growing project
                                portfolios
                            </p>

                        </div>

                    </div>


                    <div class="plan-price-num">

                        ₹39,999

                        <span>
                            / month
                        </span>

                    </div>


                    <ul class="plan-bullets">

                        <li>
                            <i class="ri-check-line"></i>
                            Up to 25 projects
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            Advanced features
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            Priority support
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            Detailed reports
                        </li>

                    </ul>

                </div>


                <a
                    href="{{ url('/login') }}"
                    class="btn-plan-action">

                    Get Started

                </a>

            </div>



            {{-- Enterprise --}}

            <div class="price-plan-card">

                <div>

                    <div class="plan-card-header">

                        <div class="plan-icon-pill">

                            <i class="ri-building-line"></i>

                        </div>


                        <div class="plan-titles">

                            <h4>
                                Enterprise
                            </h4>

                            <p>
                                For large organizations
                                and custom requirements
                            </p>

                        </div>

                    </div>


                    <div class="plan-price-num">
                        Custom
                    </div>


                    <ul class="plan-bullets">

                        <li>
                            <i class="ri-check-line"></i>
                            Unlimited projects
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            All features included
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            Dedicated account manager
                        </li>

                        <li>
                            <i class="ri-check-line"></i>
                            Custom integrations
                        </li>

                    </ul>

                </div>


                <a
                    href="#contact"
                    class="btn-plan-action">

                    Contact Sales

                </a>

            </div>


        </div>

    </div>

</section>



{{-- =========================================================
     CTA
========================================================= --}}

<section
    class="cta-section"
    id="contact">

    <div class="cta-background"></div>


    <div class="container">

        <div class="cta-content">

            <span class="cta-eyebrow">
                READY TO GET STARTED?
            </span>


            <h1 class="cta-title">
                Let’s Build Smarter Projects Together
            </h1>


            <p class="cta-description">
                Bring your property development,
                construction and asset operations
                together on one platform.
            </p>


            <div class="cta-actions">

                <a
                    href="{{ url('/login') }}"
                    class="cta-btn cta-btn-primary">

                    <span>
                        Get Started
                    </span>

                    <i class="ri-arrow-right-line"></i>

                </a>


                <a
                    href="#contact"
                    class="cta-btn cta-btn-secondary">

                    Contact Us

                </a>

            </div>

        </div>


        <div class="cta-brand">

            <div class="cta-brand-name">

                <img
                    src="{{ asset('public/frontend/images/logo.png') }}"
                    alt="MSG Group">

            </div>

        </div>

    </div>

</section>



@endsection