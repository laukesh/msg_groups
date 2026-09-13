@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="portal-card">

    <!-- Left Section: Details & Features -->
    <div class="portal-info">
      <div>
        <div class="top-bar">
          <div class="brand-logo">
            <img alt="Company logo" src="{{ asset('public/assets/img/logo-color.png') }}" class="header-logo" />
          </div>

          <div class="status-badge">
            <span class="status-dot"></span>
            SECURE CLOUD v4.8
          </div>
        </div>

        <div class="suite-tag">Infrastructure Management Suite</div>
        <h1 class="auth-bigt">Engineering the Future of <span>Commercial Infrastructure.</span></h1>
        <p class="description">
          An integrated executive portal designed for real-time project governance, financial ledgers, and site compliance.
        </p>

        <div class="features-list">
          <div class="feature-item">
            <div class="feature-icon">
              <!-- Shield Icon -->
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              </svg>
            </div>
            <div class="feature-content">
              <h4>Real-Time Financial Ledgers</h4>
              <p>Live accounts integration across 88 active infrastructure contractors.</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <!-- Dollar Icon -->
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
            </div>
            <div class="feature-content">
              <h4>12-Department Progress Funnels</h4>
              <p>Track Civil Works, MEP, HVAC, and facade glazing milestones simultaneously.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="info-footer">
        <span>ISO 27001 Certified Architecture</span>
        <span>&copy; {{ date('Y') }} Hargeisa Inc.</span>
      </div>
    </div>

    <!-- Right Section: Sign In Form -->
    <div class="portal-form-wrap">
      <h2>Sign In to Workspace</h2>
      <p class="sub-text">Enter your authorized work email and password to continue.</p>

      @if ($errors->any())
        <ul class="form-errors">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <div class="label-row">
            <label for="email">Enterprise Work Email</label>
          </div>
          <div class="input-wrapper">
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              autocomplete="username"
              required
              autofocus
            />
          </div>
        </div>

        <div class="form-group">
          <div class="label-row">
            <label for="password">Password</label>
            <a href="" class="forgot-link">Forgot password?</a>
          </div>
          <div class="input-wrapper">
            <input
              type="password"
              id="password"
              name="password"
              autocomplete="current-password"
              required
            />
            <button type="button" class="eye-toggle" aria-label="Toggle password visibility" data-target="password">
              <!-- Eye Icon -->
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="remember" name="remember" />
          <label for="remember">Remember me</label>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="auth-device" name="auth_device" checked />
          <label for="auth-device">Keep device authorized (30 days)</label>
        </div>

        <button type="submit" class="btn-submit">
          <span>Sign In to Dashboard</span>
          <i class="ri-arrow-right-long-line"></i>
        </button>

        <div class="divider">OR CONTINUE WITH</div>

        <div class="social-links">
          <a href="#" class="social-icon fb" aria-label="Sign in with Facebook"><i class="ri-facebook-fill"></i></a>
          <a href="#" class="social-icon x" aria-label="Sign in with X"><i class="ri-twitter-x-fill"></i></a>
          <a href="#" class="social-icon lk" aria-label="Sign in with LinkedIn"><i class="ri-linkedin-fill"></i></a>
          <a href="#" class="social-icon gl" aria-label="Sign in with Google"><i class="ri-google-fill"></i></a>
        </div>
      </form>

      <div class="support-footer">
        Trouble accessing your portal? <a href="#">Contact IT Service Desk</a>
      </div>
    </div>

</div>

@endsection