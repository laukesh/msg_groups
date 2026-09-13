<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'MSG Group')
    </title>

    <meta name="description"
          content="Integrated property and project management platform for MSG Group.">

    <link rel="icon"
          href="{{ asset('public/frontend/images/logo-color.png') }}">

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Remix Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"
        rel="stylesheet">

    {{-- Frontend CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('public/frontend/css/style.css') }}">

    @stack('styles')

</head>

<body>

    @include('frontend.partials.header')

    @yield('content')

    @include('frontend.partials.footer')


    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    {{-- Frontend JS --}}
    <script
        src="{{ asset('public/frontend/js/main.js') }}">
    </script>

    @stack('scripts')

</body>

</html>