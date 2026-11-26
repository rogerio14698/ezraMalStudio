{{--
|==========================================================================
| LAYOUT PRINCIPAL
|==========================================================================
| Estructura de la web:
|   app.blade.php                      -> este archivo: <head>, cabecera, contenido y pie
|   layouts/header.blade.php           -> navegación superior
|   layouts/footer.blade.php           -> cierre + formulario de contacto + redes
|   ezraStudio/home.blade.php          -> página de inicio (solo ordena las secciones)
|   ezraStudio/sections/*.blade.php    -> una sección por archivo
|
| Variables globales ($estudio, $menu, $meta, $tracking): las prepara
| app/View/Composers/SiteComposer.php con lo guardado en el panel
| (Pie de página, Menús, Ajustes SEO y Google Ads, SEO por página).
|
| Estilos: resources/sass (un parcial por sección). JS: resources/js/studio.js
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#F9F6F0">

    {{-- ===== SEO (panel → SEO y marketing). Una vista puede sobrescribirlo con @section ===== --}}
    <title>@yield('title', $meta['title'])</title>
    <meta name="description" content="@yield('meta-description', $meta['description'])">
    @if ($meta['keywords'])
        <meta name="keywords" content="{{ $meta['keywords'] }}">
    @endif
    <meta name="robots" content="{{ $meta['robots'] }}">
    <link rel="canonical" href="{{ $meta['canonical'] }}">
    @if ($tracking['verification'])
        <meta name="google-site-verification" content="{{ $tracking['verification'] }}">
    @endif

    {{-- ===== Open Graph / Twitter ===== --}}
    <meta property="og:title" content="@yield('title', $meta['title'])">
    <meta property="og:description" content="@yield('meta-description', $meta['description'])">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    <meta property="og:image" content="{{ $meta['og_image'] }}">
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

    {{--
        ===== Google Analytics 4 / Google Ads (gtag.js) =====
        Los IDs llegan ya validados (G-XXXX / AW-XXXX) desde SeoSetting y se
        imprimen con @js para que no puedan inyectar código.
        Tras enviar el formulario de contacto se lanza la conversión "contacto_enviado".
    --}}
    @php($gtagIds = array_values(array_filter([$tracking['ga_id'], $tracking['ads_id']])))
    @if ($gtagIds)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtagIds[0] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            @foreach ($gtagIds as $gtagId)
                gtag('config', @js($gtagId));
            @endforeach
            @if ($tracking['conversion'])
                gtag('event', 'conversion', { send_to: @js($tracking['conversion']) });
            @endif
        </script>
    @endif

    {{-- Scripts personalizados del panel (solo administradores pueden editarlos) --}}
    {!! $tracking['head'] !!}
</head>

<body class="studio-body">

    {{-- Enlace para saltar al contenido (accesibilidad con teclado) --}}
    <a href="#contenido" class="skip-link">Saltar al contenido</a>

    @include('layouts.header')

    <main id="contenido">
        @yield('content')
    </main>

    @include('layouts.footer')

    {!! $tracking['body'] !!}
</body>

</html>
