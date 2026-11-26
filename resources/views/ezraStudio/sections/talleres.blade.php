{{--
|--------------------------------------------------------------------------
| SECCIÓN 3 · TALLERES
|--------------------------------------------------------------------------
| Datos: panel → Contenido → Talleres publicados ($talleresPublicados).
| Se reparten en dos pestañas según "Público"; los de "Todos los públicos"
| aparecen en ambas. Mientras no haya talleres publicados, se usa $grupos
| de ejemplo.
| El cambio de pestaña está en resources/js/studio.js (data-tab / data-panel).
--}}
@php
    $grupos = [
        [
            'id' => 'adultos',
            'pestana' => 'Adultos (+16 años)',
            'items' => [
                [
                    'etiqueta' => 'Técnica clásica y contemporánea',
                    'titulo' => 'Óleo y veladuras',
                    'texto' => 'Construye atmósferas, trabaja el retrato expresivo y domina los tiempos de secado sobre lienzo.',
                    'puntos' => ['Teoría del color aplicada', 'Retrato y figura humana', 'Soportes rígidos y tela'],
                    'imagen' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&w=800&q=80',
                    'imagen_alt' => 'Retrato expresionista pintado al óleo',
                ],
                [
                    'etiqueta' => 'Agua y mancha',
                    'titulo' => 'Acuarela y gouache',
                    'texto' => 'Pigmento, agua e ilustración narrativa. Para soltar el trazo y perder el miedo al papel en blanco.',
                    'puntos' => ['Húmedo sobre húmedo', 'Cuaderno de artista', 'Ilustración experimental'],
                    'imagen' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?auto=format&fit=crop&w=800&q=80',
                    'imagen_alt' => 'Composición abstracta en acuarela',
                ],
                [
                    'etiqueta' => 'Textura y volumen',
                    'titulo' => 'Acrílico y técnica mixta',
                    'texto' => 'Collage, pasteles grasos, tintas y acrílico en gran formato para mezclar lenguajes.',
                    'puntos' => ['Transferencia de imágenes', 'Texturas con cargas minerales', 'Proyecto personal'],
                    'imagen' => 'https://images.unsplash.com/photo-1547826039-bfc35e0f1ea8?auto=format&fit=crop&w=800&q=80',
                    'imagen_alt' => 'Pintura acrílica con texturas y capas de color',
                ],
            ],
        ],
        [
            'id' => 'infancias',
            'pestana' => 'Infancias (5 a 15 años)',
            'items' => [
                [
                    'etiqueta' => '5 a 9 años',
                    'titulo' => 'Exploradores del color',
                    'texto' => 'Iniciación a la pintura con juego, pigmentos naturales, estampación y personajes propios.',
                    'puntos' => ['Psicomotricidad fina', 'Cuentos ilustrados', 'Materiales no tóxicos'],
                    'imagen' => 'https://images.unsplash.com/photo-1452802447250-470a88ac82bc?auto=format&fit=crop&w=800&q=80',
                    'imagen_alt' => 'Cuenco con mezclas de pintura de colores',
                ],
                [
                    'etiqueta' => '10 a 15 años',
                    'titulo' => 'Laboratorio juvenil',
                    'texto' => 'Cómic, acrílico, acuarela y fanzines en un espacio para probar sin miedo a equivocarse.',
                    'puntos' => ['Narrativa gráfica', 'Iniciación al lienzo', 'Fanzine colectivo'],
                    'imagen' => 'https://images.unsplash.com/photo-1456086272160-b28b0645b729?auto=format&fit=crop&w=800&q=80',
                    'imagen_alt' => 'Botes de pintura abiertos y una mano mojando el pincel',
                ],
                [
                    'etiqueta' => 'Sábados',
                    'titulo' => 'Talleres en familia',
                    'texto' => 'Sesiones de sábado por la mañana para pintar a cuatro manos y llevaros el proyecto a casa.',
                    'puntos' => ['Murales colaborativos', 'Cerámica fría y pintura', 'Materiales incluidos'],
                    'imagen' => 'https://images.unsplash.com/photo-1515405295579-ba7b45403062?auto=format&fit=crop&w=800&q=80',
                    'imagen_alt' => 'Collage y pintura experimental sobre papel',
                ],
            ],
        ],
    ];

    // Talleres del panel: mismas pestañas, tarjetas desde la base de datos.
    if (isset($talleresPublicados) && $talleresPublicados->isNotEmpty()) {
        $grupos = collect(['adultos' => 'Adultos (+16 años)', 'infancias' => 'Infancias (5 a 15 años)'])
            ->map(fn ($pestana, $id) => [
                'id' => $id,
                'pestana' => $pestana,
                'items' => $talleresPublicados
                    ->filter(fn ($taller) => in_array($taller->publico?->value, [$id, 'todos'], true))
                    ->map(fn ($taller) => [
                        'etiqueta' => $taller->category,
                        'titulo' => $taller->title,
                        'texto' => $taller->summary,
                        'puntos' => $taller->tags ?? [],
                        'imagen' => $taller->image,
                        'imagen_variants' => $taller->image_variants,
                        'imagen_alt' => $taller->image_alt ?: $taller->title,
                    ])
                    ->values()
                    ->all(),
            ])
            ->filter(fn ($grupo) => $grupo['items'])
            ->values()
            ->all();
    }
@endphp

<section class="section services" id="talleres" aria-labelledby="talleres-titulo">
    <div class="container">

        {{-- Cabecera de la sección --}}
        <div class="section__header">
            <h2 class="section__title" id="talleres-titulo">Talleres por técnica y edad</h2>
            <p class="section__desc">Grupos de 8 personas como máximo, con seguimiento según tu nivel.</p>
        </div>

        {{-- Pestañas --}}
        <div class="services__tabs" role="tablist" aria-label="Público">
            @foreach ($grupos as $i => $grupo)
                <button class="services__tab" type="button" role="tab" id="tab-{{ $grupo['id'] }}"
                    data-tab="{{ $grupo['id'] }}" aria-controls="panel-{{ $grupo['id'] }}"
                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}">
                    {{ $grupo['pestana'] }}
                </button>
            @endforeach
        </div>

        {{-- Paneles con tarjetas --}}
        @foreach ($grupos as $i => $grupo)
            <div class="services__grid" role="tabpanel" id="panel-{{ $grupo['id'] }}" data-panel="{{ $grupo['id'] }}"
                aria-labelledby="tab-{{ $grupo['id'] }}" @if ($i !== 0) hidden @endif>
                @foreach ($grupo['items'] as $taller)
                    <article class="service-card">
                        @if ($taller['imagen'])
                            <div class="service-card__media photo">
                                @if (!empty($taller['imagen_variants']))
                                    <x-picture :src="$taller['imagen']" :variants="$taller['imagen_variants']"
                                        :alt="$taller['imagen_alt']"
                                        sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" />
                                @else
                                    <img src="{{ \App\Support\Media::url($taller['imagen']) }}" alt="{{ $taller['imagen_alt'] }}"
                                        width="800" height="600" loading="lazy" decoding="async">
                                @endif
                            </div>
                        @endif
                        <div class="service-card__body">
                            @if ($taller['etiqueta'])
                                <span class="service-card__tag">{{ $taller['etiqueta'] }}</span>
                            @endif
                            <h3 class="service-card__title">{{ $taller['titulo'] }}</h3>
                            @if ($taller['texto'])
                                <p class="service-card__text">{{ $taller['texto'] }}</p>
                            @endif
                            @if ($taller['puntos'])
                                <ul class="service-card__list">
                                    @foreach ($taller['puntos'] as $punto)
                                        <li>{{ $punto }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endforeach

    </div>
</section>
