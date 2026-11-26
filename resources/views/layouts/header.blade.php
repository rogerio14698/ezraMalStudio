{{--
|--------------------------------------------------------------------------
| CABECERA / NAVEGACIÓN
|--------------------------------------------------------------------------
| - $estudio y $menu llegan de SiteComposer (panel → Pie de página y Menús).
| - Si no hay menús en el panel se usan los enlaces de ejemplo (#visita, #talleres…).
| - El botón hamburguesa se controla en resources/js/studio.js
--}}
<header class="navbar" id="navbar">
    <div class="container navbar__inner">

        {{-- Logo / nombre --}}
        <a href="{{ route('home') }}#inicio" class="navbar__brand" aria-label="{{ $estudio['nombre'] }}, ir al inicio">
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
                <a href="{{ $item['href'] }}" class="navbar__link"
                    @if ($item['nueva']) target="_blank" rel="noopener" @endif>{{ $item['texto'] }}</a>
            @endforeach
            <a href="#contacto" class="btn btn--primary navbar__cta">Reservar clase de prueba</a>
        </nav>

    </div>
</header>
