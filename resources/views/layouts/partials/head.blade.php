<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', config('app.name', 'Mall Management System'))</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/assets/css/app.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/css/components.css') }}">
<link rel="icon" type="image/png" href="{{ asset('public/assets/img/favicon.png') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('public/assets/css/custom.css') }}">

<style>
    .sidebar-brand {
        height: 66px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        background: #fff;
    }
    .sidebar-logo {
        width: 100%;
        height: 62px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
    }
    .sidebar-logo img {
        width: 78px;
        height: 62px;
        object-fit: contain;
        display: block;
    }
</style>

@stack('styles')
