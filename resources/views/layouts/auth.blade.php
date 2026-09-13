<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Mall Management System'))</title>
        <link href='https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css' rel='stylesheet'>	
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
         <link rel="icon" type="image/png" href="{{ asset('public/assets/img/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('public/auth.css') }}">
        <link rel="stylesheet" href="{{ asset('public/assets/css/app.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('public/assets/css/components.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('public/assets/img/favicon.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
        <link rel="stylesheet" href="{{ asset('public/assets/css/custom.css') }}">
</head>
<body>


 @yield('content')
 
<script > document.querySelectorAll('.eye-toggle').forEach(function (toggle) {

    toggle.addEventListener('click', function () {

        const input = this.previousElementSibling;

        if (input.type === 'password') {
            input.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }

    });

});
</script>
</body>
</html>