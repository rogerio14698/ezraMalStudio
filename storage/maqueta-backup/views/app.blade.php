<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover, interactive-widget=resizes-content">

    <title>@yield('title', 'Estudio Cromático - Taller de Arte & Creación')</title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="Estudio Cromático - Taller de Arte & Creación">
    <meta name="keywords" content="arte, creación, taller, estudio">
    <meta name="author" content="Estudio Cromático">
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Estudio Cromático - Taller de Arte & Creación">
    <meta property="og:description" content="Estudio Cromático - Taller de Arte & Creación">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ config('app.url') }}/images/logo.png }}">
    <!-- Replace with your actual image path -->
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Estudio Cromático - Taller de Arte & Creación">
    <meta name="twitter:description" content="Estudio Cromático - Taller de Arte & Creación">
    <meta name="twitter:image" content="{{ config('app.url') }}/images/logo.png }}">
    <!-- Replace with your actual image path -->

    <!--Tipografía Editoriales -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap"
        rel="stylesheet">


    <link rel="shortcut icon" href="#" type="image/x-icon">
    <link rel="manifest" href="#">
    <!--Enlace a la carpeta de public/css -->
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body data-theme="dark" class="studio-body">

    @yield('content')

    @include('layouts.footer')

</body>

</html>
