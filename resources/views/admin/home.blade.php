@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="container-fluid px-2 px-lg-2">
    <style>






/* =========================================================
   HOME WELCOME HERO
========================================================= */

.home-welcome-card {
    position: relative;
    width: 100%;
    min-height: 265px;

    background:
        linear-gradient(
            110deg,
            #f4f9ff 0%,
            #eaf4ff 55%,
            #dceeff 100%
        );

    border: 1px solid #e2ebf5;

    border-radius: 14px;

    overflow: hidden;

    box-shadow:
        0 3px 12px rgba(15, 45, 75, 0.08);

    margin-bottom: 14px;
}


/* Main layout */

.home-welcome-content {
    display: flex;

    width: 100%;
    min-height: 265px;
}


/* =========================================================
   LEFT SIDE
========================================================= */

.home-welcome-left {

    width: 58%;

    padding:
        25px
        20px
        20px
        48px;

    position: relative;

    z-index: 2;
}


.home-welcome-eyebrow {

    color: #536dff;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 4px;

    margin-bottom: 7px;

    text-transform: uppercase;
}


.home-welcome-title {

    margin: 0;

    color: #102a43;

    font-size: 30px;

    line-height: 1.08;

    font-weight: 800;

    letter-spacing: -0.5px;
}


.home-welcome-description {

    margin:

        9px

        0

        12px;

    max-width: 610px;

    color: #64748b;

    font-size: 12px;

    line-height: 1.65;

}


/* =========================================================
   FEATURES
========================================================= */

.home-welcome-features {

    display: flex;

    align-items: center;

    gap: 28px;

    margin-top: 10px;
}


.home-welcome-feature {

    display: flex;

    align-items: center;

    gap: 9px;

    min-width: 105px;
}


.home-welcome-feature-icon {

    width: 39px;

    height: 39px;

    min-width: 39px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 18px;
}


.home-welcome-feature-icon.green {

    background: #d8f3e7;

    color: #25a875;
}


.home-welcome-feature-icon.blue {

    background: #dceaff;

    color: #397df6;
}


.home-welcome-feature-icon.orange {

    background: #fff0cf;

    color: #f2a51a;
}


.home-welcome-feature-icon.purple {

    background: #e9ddff;

    color: #7b4bd8;
}


.home-welcome-feature strong {

    display: block;

    color: #162b44;

    font-size: 10px;

    font-weight: 700;

    line-height: 1.3;
}


.home-welcome-feature span {

    display: block;

    color: #8795a7;

    font-size: 9px;

    line-height: 1.3;
}


/* =========================================================
   RIGHT IMAGE
========================================================= */

.home-welcome-right {

    position: relative;

    width: 42%;

    min-height: 265px;

    overflow: hidden;
}


.home-welcome-right img {

    position: absolute;

    inset: 0;

    width: 100%;

    height: 100%;

    object-fit: cover;

    object-position: center;

    display: block;
}


/* Image fade into banner */

.home-welcome-image-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            90deg,
            rgba(230, 242, 255, 0.95) 0%,
            rgba(230, 242, 255, 0.25) 25%,
            rgba(0, 0, 0, 0.02) 55%,
            rgba(0, 0, 0, 0.12) 100%
        );
}


/* Image text */

.home-welcome-image-text {

    position: absolute;

    top: 24px;

    right: 28px;

    text-align: right;

    z-index: 3;
}


.home-welcome-image-text span {

    display: block;

    color: #102a43;

    font-size: 11px;

    font-weight: 600;

    font-style: italic;
}


.home-welcome-image-text strong {

    display: block;

    margin-top: 2px;

    color: #102a43;

    font-size: 14px;

    line-height: 1.25;

    font-weight: 700;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1199px) {

    .home-welcome-left {

        padding-left: 32px;

    }

    .home-welcome-title {

        font-size: 27px;

    }

    .home-welcome-features {

        gap: 16px;

    }

}


@media (max-width: 991px) {

    .home-welcome-content {

        flex-direction: column;

    }


    .home-welcome-left {

        width: 100%;

        padding: 28px;

    }


    .home-welcome-right {

        width: 100%;

        height: 250px;

        min-height: 250px;

    }


    .home-welcome-features {

        flex-wrap: wrap;

    }

}


@media (max-width: 575px) {

    .home-welcome-left {

        padding: 22px;

    }


    .home-welcome-title {

        font-size: 25px;

    }


    .home-welcome-description {

        font-size: 11px;

    }


    .home-welcome-features {

        gap: 15px;

    }


    .home-welcome-right {

        min-height: 200px;

        height: 200px;

    }

}

    .home-video-box {
        position: relative;
        height: 100%;
        min-height: 300px;
        overflow: hidden;
        border-radius: 10px;
    }

    .home-video-box img {
        object-fit: cover;
    }

    .home-video-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to top,
            rgba(0,0,0,.75),
            rgba(0,0,0,.1)
        );
    }

    .home-video-content {
        position: absolute;
        left: 20px;
        bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .home-play-button {
        width: 58px;
        height: 58px;
        border: 2px solid #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 28px;
    }

    .home-message-box {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px;
    }

    .home-message-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #fff3cd;
        color: #f59f00;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .home-quick-card {
        border-radius: 12px;
        transition: .2s ease;
    }

    .home-quick-card:hover {
        transform: translateY(-3px);
    }

    .home-quick-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        flex-shrink: 0;
    }

    .home-quick-icon.green {
        background: #dff7ed;
        color: #159570;
    }

    .home-quick-icon.blue {
        background: #e2efff;
        color: #1877f2;
    }

    .home-quick-icon.purple {
        background: #eee5ff;
        color: #7137c8;
    }

    .home-support-btn {
        background: #7137c8;
        color: #fff;
    }

    .home-support-btn:hover {
        background: #5e2da8;
        color: #fff;
    }

    .home-lifecycle-item {
        border-radius: 12px;
        transition: .2s ease;
    }

    .home-lifecycle-item:hover {
        background: #f8fafc;
        transform: translateY(-3px);
    }

    .home-lifecycle-icon {
        width: 55px;
        height: 55px;
        margin: auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    @media (max-width: 991px) {

        .home-title {
            font-size: 32px;
        }

        .home-hero-image {
            min-height: 260px;
        }

    }

</style>

    {{-- =====================================================
        WELCOME HERO
    ====================================================== --}}

    {{-- =====================================================
    WELCOME HERO
====================================================== --}}

<div class="home-welcome-card">

    <div class="home-welcome-content">

        {{-- LEFT CONTENT --}}
        <div class="home-welcome-left">

            <div class="home-welcome-eyebrow">
                WELCOME TO THE
            </div>


            <h1 class="home-welcome-title">
                MSG Group
                <br>
                Property &amp; Project
                <br>
                Management System
            </h1>


            <p class="home-welcome-description">

                A smarter way to manage, monitor and grow your
                property and project portfolio. Manage development,
                construction, contracts, assets and operations
                from one centralized platform.

            </p>


            {{-- FEATURES --}}

            <div class="home-welcome-features">


                {{-- Feature 1 --}}

                <div class="home-welcome-feature">

                    <div class="home-welcome-feature-icon green">

                        <i class="ri-building-line"></i>

                    </div>

                    <div>

                        <strong>
                            Efficient
                        </strong>

                        <span>
                            Management
                        </span>

                    </div>

                </div>


                {{-- Feature 2 --}}

                <div class="home-welcome-feature">

                    <div class="home-welcome-feature-icon blue">

                        <i class="ri-bar-chart-line"></i>

                    </div>

                    <div>

                        <strong>
                            Real-time
                        </strong>

                        <span>
                            Information
                        </span>

                    </div>

                </div>


                {{-- Feature 3 --}}

                <div class="home-welcome-feature">

                    <div class="home-welcome-feature-icon orange">

                        <i class="ri-team-line"></i>

                    </div>

                    <div>

                        <strong>
                            Better
                        </strong>

                        <span>
                            Collaboration
                        </span>

                    </div>

                </div>


                {{-- Feature 4 --}}

                <div class="home-welcome-feature">

                    <div class="home-welcome-feature-icon purple">

                        <i class="ri-shield-check-line"></i>

                    </div>

                    <div>

                        <strong>
                            Secure
                        </strong>

                        <span>
                            &amp; Reliable
                        </span>

                    </div>

                </div>


            </div>

        </div>


        {{-- RIGHT IMAGE --}}

        <div class="home-welcome-right">

            <img
                src="{{ asset('public/frontend/images/home-management.jpeg') }}"
                alt="MSG Group Property Management System">

            <div class="home-welcome-image-overlay"></div>

            <div class="home-welcome-image-text">

                <span>
                    More Than a Platform
                </span>

                <strong>
                    Smarter Projects.<br>
                    Better Management.
                </strong>

            </div>

        </div>

    </div>

</div>



    {{-- =====================================================
        INTRODUCTION + VIDEO
    ====================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Project / System Image --}}

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100 overflow-hidden">

                <div class="home-video-box">

                    <img src="{{ asset('public/frontend/images/project-introduction.jpeg') }}"
                         alt="Project Introduction"
                         class="w-100 h-100">

                    <div class="home-video-overlay"></div>

                    <div class="home-video-content">

                        <div class="home-play-button">

                            <i class="ri-play-fill"></i>

                        </div>

                        <div>

                            <h5 class="text-white mb-1">
                                MSG Group Project Platform
                            </h5>

                            <small class="text-white">
                                Project Management Introduction
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Welcome Message --}}

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <h4 class="fw-bold mb-2">

                        Welcome to MSG Group!

                    </h4>

                    <p class="text-muted">

                        The MSG Group Property &amp; Project Management
                        System brings together the complete project and
                        property lifecycle into one centralized platform.

                    </p>


                    <p class="text-muted">

                        From land acquisition and feasibility through design,
                        procurement, construction, commissioning, handover
                        and asset management — teams can manage information,
                        workflows, documents and decisions from one place.

                    </p>


                    <div class="home-message-box mt-3">

                        <div class="home-message-icon">

                            <i class="ri-lightbulb-line"></i>

                        </div>

                        <div>

                            <strong>
                                “Building Better Projects Together”
                            </strong>

                            <p class="small text-muted mb-0 mt-1">

                                One platform for development,
                                construction and asset management.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
        QUICK ACCESS
    ====================================================== --}}

    <div class="row g-3 mb-4"
         id="platform-features">


        {{-- User Manual --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100 home-quick-card">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="home-quick-icon green">

                            <i class="ri-file-text-line"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                User Manual
                            </h6>

                            <p class="text-muted small mb-0">

                                Learn how to use the system
                                and its modules.

                            </p>

                        </div>

                    </div>


                    <a href="#"
                       class="btn btn-success btn-sm mt-3 px-4">

                        <i class="ri-download-line me-1"></i>

                        Download Manual

                    </a>

                </div>

            </div>

        </div>


        {{-- Training --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100 home-quick-card">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="home-quick-icon blue">

                            <i class="ri-graduation-cap-line"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Training &amp; Guides
                            </h6>

                            <p class="text-muted small mb-0">

                                Learn the system through
                                guided training materials.

                            </p>

                        </div>

                    </div>


                    <a href="#"
                       class="btn btn-primary btn-sm mt-3 px-4">

                        <i class="ri-play-line me-1"></i>

                        Watch Training

                    </a>

                </div>

            </div>

        </div>


        {{-- Support --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100 home-quick-card">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="home-quick-icon purple">

                            <i class="ri-question-mark"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Need Help?
                            </h6>

                            <p class="text-muted small mb-0">

                                Contact your administrator
                                for system support.

                            </p>

                        </div>

                    </div>


                    <a href="#"
                       class="btn btn-sm mt-3 px-4 home-support-btn">

                        <i class="ri-mail-line me-1"></i>

                        Contact Support

                    </a>

                </div>

            </div>

        </div>


    </div>



    {{-- =====================================================
        LIFECYCLE
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="text-center mb-4">

                <div class="small text-uppercase text-primary fw-semibold"
                     style="letter-spacing: 2px;">

                    PROJECT LIFECYCLE

                </div>

                <h4 class="fw-bold mt-2">
                    Manage the Complete Project Journey
                </h4>

                <p class="text-muted mb-0">

                    From development planning through construction,
                    commissioning and asset operations.

                </p>

            </div>


            <div class="row g-3">


                @php

                    $lifecycle = [

                        [
                            'icon' => 'ri-map-pin-line',
                            'title' => 'Land',
                            'text' => 'Acquisition & Feasibility',
                            'color' => 'success'
                        ],

                        [
                            'icon' => 'ri-draft-line',
                            'title' => 'Design',
                            'text' => 'Design & Consultants',
                            'color' => 'primary'
                        ],

                        [
                            'icon' => 'ri-shopping-cart-line',
                            'title' => 'Procurement',
                            'text' => 'Packages & Purchasing',
                            'color' => 'warning'
                        ],

                        [
                            'icon' => 'ri-building-2-line',
                            'title' => 'Construction',
                            'text' => 'Projects & Site',
                            'color' => 'danger'
                        ],

                        [
                            'icon' => 'ri-settings-3-line',
                            'title' => 'Commissioning',
                            'text' => 'Testing & Certification',
                            'color' => 'info'
                        ],

                        [
                            'icon' => 'ri-checkbox-circle-line',
                            'title' => 'Handover',
                            'text' => 'Closeout & Acceptance',
                            'color' => 'secondary'
                        ],

                        [
                            'icon' => 'ri-building-line',
                            'title' => 'Asset',
                            'text' => 'Operations & Management',
                            'color' => 'dark'
                        ],

                    ];

                @endphp


                @foreach($lifecycle as $item)

                    <div class="col-xl col-lg-3 col-md-4 col-sm-6">

                        <div class="text-center p-3 home-lifecycle-item">

                            <div class="home-lifecycle-icon bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }}">

                                <i class="{{ $item['icon'] }}"></i>

                            </div>

                            <h6 class="fw-bold mt-3 mb-1">

                                {{ $item['title'] }}

                            </h6>

                            <small class="text-muted">

                                {{ $item['text'] }}

                            </small>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>



    {{-- =====================================================
        BOTTOM ACTION
    ====================================================== --}}

    <div class="text-center py-2 pb-4">

        <a href="{{ url('/admin/dashboard') }}"
           class="btn btn-primary px-5">

            <i class="ri-dashboard-line me-2"></i>

            Go to Dashboard

            <i class="ri-arrow-right-line ms-2"></i>

        </a>

    </div>

</div>

@endsection




