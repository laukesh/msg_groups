@extends('layouts.auth')

@section('title','Forgot Password')

@section('content')
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
    <h1>Forgot Password</h1>
    @if(session('status'))<div>{{ session('status') }}</div>@endif
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    @endif
    <form method="POST" action="{{ route('auth.forgot-password') }}">
        @csrf
        <label>Email <input type="email" name="email" required></label><br>
        <button type="submit">Send Reset Link</button>
    </form>
    </div>
</div>

@endsection
