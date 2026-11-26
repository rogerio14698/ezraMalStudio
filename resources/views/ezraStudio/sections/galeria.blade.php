{{--
|--------------------------------------------------------------------------
| SECCIÓN 4 · GALERÍA
|--------------------------------------------------------------------------
| Datos: panel → Galería → Colecciones publicadas ($colecciones), cada una
| con sus elementos visibles en el orden del panel.
|   - Imagen: <x-picture> (AVIF/WebP en 3 tamaños, lazy).
|   - Vídeo:  preload="none" + poster ligero; no descarga nada hasta pulsar play.
|   - PDF:    tarjeta con título y peso; se abre o descarga solo bajo demanda.
| Mientras no haya colecciones, se muestra la rejilla de ejemplo ($demo).
--}}
@php
    $demo = [
        ['src' => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?auto=format&fit=crop&w=1200&q=80', 'alt' => 'Paisaje de bosque pintado al óleo', 'ancho' => true],
        ['src' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&w=800&q=80', 'alt' => 'Bodegón de flores al óleo', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1456086272160-b28b0645b729?auto=format&fit=crop&w=800&q=80', 'alt' => 'Botes de pintura abiertos en la mesa del taller', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?auto=format&fit=crop&w=800&q=80', 'alt' => 'Composición abstracta de manchas de color', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1580136579312-94651dfd596d?auto=format&fit=crop&w=800&q=80', 'alt' => 'Pintura al óleo de una escena de batalla', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1515405295579-ba7b45403062?auto=format&fit=crop&w=800&q=80', 'alt' => 'Pinceladas azules y blancas sobre lienzo', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1452802447250-470a88ac82bc?auto=format&fit=crop&w=1200&q=80', 'alt' => 'Mezclas de color en un cuenco durante una clase', 'ancho' => true],
    ];

    $colecciones = ($colecciones ?? collect())->filter(fn ($coleccion) => $coleccion->elementosVisibles->isNotEmpty());
@endphp

<section class="section gallery" id="galeria" aria-labelledby="galeria-titulo">
    <div class="container">

        <div class="section__header">
            <h2 class="section__title" id="galeria-titulo">Obra del estudio y del taller</h2>
            <p class="section__desc">Trabajos del alumnado y sesiones en el taller.</p>
        </div>

        @forelse ($colecciones as $coleccion)
            <div class="gallery__collection">
                <h3 class="gallery__collection-title">{{ $coleccion->titulo }}</h3>
                @if ($coleccion->descripcion)
                    <p class="gallery__collection-desc">{{ $coleccion->descripcion }}</p>
                @endif

                <div class="gallery__grid">
                    @foreach ($coleccion->elementosVisibles as $elemento)
                        @switch($elemento->tipo)
                            @case(\App\Enums\TipoMedia::Video)
                                <figure class="gallery__item gallery__item--video">
                                    <video class="gallery__video" controls playsinline preload="none"
                                        poster="{{ \App\Support\Media::posterUrl($elemento->video_poster, $elemento->video_poster_variants) }}"
                                        @if ($elemento->alt_text) aria-label="{{ $elemento->alt_text }}" @endif>
                                        <source src="{{ \App\Support\Media::url($elemento->video) }}"
                                            type="{{ str_ends_with((string) $elemento->video, '.webm') ? 'video/webm' : 'video/mp4' }}">
                                    </video>
                                    @if ($elemento->title)
                                        <figcaption class="gallery__caption">{{ $elemento->title }}</figcaption>
                                    @endif
                                </figure>
                            @break

                            @case(\App\Enums\TipoMedia::Pdf)
                                <article class="gallery__item gallery__pdf">
                                    <span class="gallery__pdf-badge" aria-hidden="true">PDF</span>
                                    <h4 class="gallery__pdf-title">{{ $elemento->title }}</h4>
                                    @if ($elemento->description)
                                        <p class="gallery__pdf-desc">{{ $elemento->description }}</p>
                                    @endif
                                    @if ($elemento->pdf_size)
                                        <p class="gallery__pdf-size">{{ \App\Support\Media::size($elemento->pdf_size) }}</p>
                                    @endif
                                    <div class="gallery__pdf-actions">
                                        <a class="btn btn--outline" href="{{ \App\Support\Media::url($elemento->pdf) }}"
                                            target="_blank" rel="noopener">Ver PDF</a>
                                        <a class="gallery__pdf-download" href="{{ \App\Support\Media::url($elemento->pdf) }}"
                                            download>Descargar</a>
                                    </div>
                                </article>
                            @break

                            @default
                                <figure @class(['gallery__item', 'photo', 'gallery__item--wide' => $loop->first])>
                                    <x-picture :src="$elemento->image" :variants="$elemento->image_variants"
                                        :alt="$elemento->alt_text ?? ''"
                                        sizes="{{ $loop->first ? '(min-width: 960px) 66vw, 100vw' : '(min-width: 960px) 33vw, (min-width: 640px) 50vw, 100vw' }}" />
                                </figure>
                        @endswitch
                    @endforeach
                </div>
            </div>
        @empty
            {{-- Rejilla de ejemplo mientras el panel esté vacío --}}
            <div class="gallery__grid">
                @foreach ($demo as $foto)
                    <div @class(['gallery__item', 'photo', 'gallery__item--wide' => $foto['ancho']])>
                        <img src="{{ $foto['src'] }}" alt="{{ $foto['alt'] }}" loading="lazy" decoding="async">
                    </div>
                @endforeach
            </div>
        @endforelse

    </div>
</section>
