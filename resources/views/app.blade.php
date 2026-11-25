{{--
|==========================================================================
| LAYOUT PRINCIPAL (maqueta)
|==========================================================================
| Estructura de la web:
|   app.blade.php                      -> este archivo: <head>, cabecera, contenido y pie
|   layouts/header.blade.php           -> navegación superior
|   layouts/footer.blade.php           -> cierre + contacto + redes
|   ezraStudio/home.blade.php          -> página de inicio (solo ordena las secciones)
|   ezraStudio/sections/*.blade.php    -> una sección por archivo, con su contenido arriba
|
| Estilos: resources/sass (un parcial por sección). JS: resources/js/studio.js
--}}

@php
    // ===== Datos globales del estudio (se usan en la cabecera y el pie) =====
    // Cambia aquí el nombre, email o redes y se actualiza en toda la web.
    $estudio = [
        'nombre' => 'ExitStudio',
        'lema' => 'Taller de arte y creación',
        'email' => 'ezra.mal.mal.mal@gmail.com', // Provisional: poner el email real
        'redes' => [
            ['nombre' => 'Instagram', 'url' => '#'],
            ['nombre' => 'TikTok', 'url' => '#'],
            ['nombre' => 'Behance', 'url' => '#'],
            ['nombre' => 'YouTube', 'url' => '#'],
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#F9F6F0">

    {{-- ===== SEO: cada página puede sobrescribir título y descripción con @section ===== --}}
    <title>@yield('title', $estudio['nombre'] . ' · ' . $estudio['lema'])</title>
    <meta name="description" content="@yield('meta-description', 'Estudio de artes plásticas: óleo, acuarela, acrílico y técnica mixta para adultos e infancias.')">

    {{-- ===== Open Graph / Twitter (imagen provisional: public/images/og.jpg) ===== --}}
    <meta property="og:title" content="@yield('title', $estudio['nombre'])">
    <meta property="og:description" content="@yield('meta-description', $estudio['lema'])">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">

    {{-- ===== Tipografías (Fraunces para títulos, Plus Jakarta Sans para texto) ===== --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap"
        rel="stylesheet">

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- ===== Assets compilados por Vite (Tailwind + SCSS + JS) ===== --}}
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="studio-body">

    {{-- Enlace para saltar al contenido (accesibilidad con teclado) --}}
    <a href="#contenido" class="skip-link">Saltar al contenido</a>

    @include('layouts.header')

    <main id="contenido">
        @yield('content')
    </main>

    @include('layouts.footer')

</body>

</html>
