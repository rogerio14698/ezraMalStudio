{{--
|--------------------------------------------------------------------------
| CABECERA / NAVEGACIÓN
|--------------------------------------------------------------------------
| - $estudio viene de app.blade.php
| - Para añadir o quitar un enlace, edita el array $menu.
| - Los enlaces apuntan a los id de cada sección (#visita, #talleres...).
| - El botón hamburguesa se controla en resources/js/studio.js
--}}

@php
    $menu = [
        ['texto' => 'Visítanos', 'href' => '#visita'],
        ['texto' => 'Talleres', 'href' => '#talleres'],
        ['texto' => 'Galería', 'href' => '#galeria'],
        ['texto' => 'Manifiesto', 'href' => '#manifiesto'],
    ];
@endphp

<header class="navbar" id="navbar">
    <div class="container navbar__inner">

        {{-- Logo / nombre (sustituir el punto por el logo cuando exista) --}}
        <a href="#inicio" class="navbar__brand" aria-label="{{ $estudio['nombre'] }}, ir al inicio">
            <span class="navbar__brand-dot" aria-hidden="true"></span>
            <span>{{ $estudio['nombre'] }}</span>
        </a>

        {{-- Botón menú móvil --}}
        <button class="navbar__toggle" id="navToggle" type="button" aria-label="Abrir menú" aria-expanded="false"
            aria-controls="navMenu">
            <span></span>
            <span></span>
        </button>

        {{-- Menú --}}
        <nav class="navbar__menu" id="navMenu" aria-label="Principal">
            @foreach ($menu as $item)
                <a href="{{ $item['href'] }}" class="navbar__link">{{ $item['texto'] }}</a>
            @endforeach
            <a href="#contacto" class="btn btn--primary navbar__cta">Reservar clase de prueba</a>
        </nav>

    </div>
</header>
