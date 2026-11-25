{{--
|--------------------------------------------------------------------------
| SECCIÓN 1 · HERO (portada)
|--------------------------------------------------------------------------
| Primera pantalla: qué es el estudio, para quién y un dato concreto.
| Edita el array $hero. La foto es provisional (Unsplash): sustituir por
| una foto real del estudio en public/images/ y usar asset('images/...').
| La entrada animada al cargar la da el atributo data-enter (ver _hero.scss).
--}}

@php
    $hero = [
        'etiqueta' => 'Estudio de artes plásticas',
        'titulo' => 'El color no entiende de normas,',
        'titulo_destacado' => 'solo de expresión.',
        'texto' => 'Clases de óleo, acuarela, acrílico y técnica mixta para personas adultas e infancias. Grupos de 8 personas como máximo y materiales incluidos.',
        'boton_principal' => ['texto' => 'Ver talleres', 'href' => '#talleres'],
        'boton_secundario' => ['texto' => 'Consultar horarios', 'href' => '#visita'],
        'imagen' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=1100&q=80',
        'imagen_alt' => 'Paleta con pintura y pinceles sobre la mesa del estudio',
        // Datos cortos bajo los botones
        'datos' => [
            ['valor' => '8', 'texto' => 'personas por grupo'],
            ['valor' => '5+', 'texto' => 'años, edad mínima'],
            ['valor' => '4', 'texto' => 'técnicas'],
        ],
    ];
@endphp

<section class="hero" id="inicio" aria-labelledby="hero-titulo">
    <div class="container hero__grid">

        {{-- Columna de texto --}}
        <div class="hero__content" data-enter>
            <span class="hero__eyebrow">{{ $hero['etiqueta'] }}</span>

            <h1 class="hero__title" id="hero-titulo">
                {{ $hero['titulo'] }} <em>{{ $hero['titulo_destacado'] }}</em>
            </h1>

            <p class="hero__subtitle">{{ $hero['texto'] }}</p>

            <div class="hero__actions">
                <a href="{{ $hero['boton_principal']['href'] }}" class="btn btn--primary">
                    {{ $hero['boton_principal']['texto'] }}
                </a>
                <a href="{{ $hero['boton_secundario']['href'] }}" class="btn btn--outline">
                    {{ $hero['boton_secundario']['texto'] }}
                </a>
            </div>

            <dl class="hero__facts">
                @foreach ($hero['datos'] as $dato)
                    <div>
                        <dt>{{ $dato['valor'] }}</dt>
                        <dd>{{ $dato['texto'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        {{-- Columna de imagen --}}
        <div class="hero__visual" data-enter>
            <img src="{{ $hero['imagen'] }}" alt="{{ $hero['imagen_alt'] }}" width="1100" height="1375"
                fetchpriority="high">
        </div>

    </div>
</section>
