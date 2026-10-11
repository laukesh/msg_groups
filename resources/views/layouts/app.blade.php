<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
</head>
<body>

    @include('layouts.partials.topbar')

    @include('layouts.partials.sidebar')

    <main class="app-main">
        <div class="app-content">
            @include('layouts.partials.alerts')
            @yield('content')
        </div>
    </main>

    @include('layouts.partials.footer')
    @include('layouts.partials.scripts')

    @stack('scripts')
</body>
</html>
