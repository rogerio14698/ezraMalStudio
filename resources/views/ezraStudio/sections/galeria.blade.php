{{--
|--------------------------------------------------------------------------
| SECCIÓN 4 · GALERÍA
|--------------------------------------------------------------------------
| Rejilla de fotos de obra y del taller. Sin textos debajo de las fotos:
| la descripción va en 'alt' (la leen los lectores de pantalla).
| - Añadir/quitar fotos: edita $galeria.
| - 'ancho' => true hace que la foto ocupe dos columnas.
| Fotos provisionales (Unsplash): sustituir por obra real del alumnado.
--}}

@php
    $galeria = [
        ['src' => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?auto=format&fit=crop&w=1200&q=80', 'alt' => 'Paisaje de bosque pintado al óleo', 'ancho' => true],
        ['src' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&w=800&q=80', 'alt' => 'Bodegón de flores al óleo', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1456086272160-b28b0645b729?auto=format&fit=crop&w=800&q=80', 'alt' => 'Botes de pintura abiertos en la mesa del taller', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?auto=format&fit=crop&w=800&q=80', 'alt' => 'Composición abstracta de manchas de color', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1580136579312-94651dfd596d?auto=format&fit=crop&w=800&q=80', 'alt' => 'Pintura al óleo de una escena de batalla', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1515405295579-ba7b45403062?auto=format&fit=crop&w=800&q=80', 'alt' => 'Pinceladas azules y blancas sobre lienzo', 'ancho' => false],
        ['src' => 'https://images.unsplash.com/photo-1452802447250-470a88ac82bc?auto=format&fit=crop&w=1200&q=80', 'alt' => 'Mezclas de color en un cuenco durante una clase', 'ancho' => true],
    ];
@endphp

<section class="section gallery" id="galeria" aria-labelledby="galeria-titulo">
    <div class="container">

        <div class="section__header">
            <h2 class="section__title" id="galeria-titulo">Obra del estudio y del taller</h2>
            <p class="section__desc">Trabajos del alumnado y sesiones en el taller.</p>
        </div>

        <div class="gallery__grid">
            @foreach ($galeria as $foto)
                <div @class(['gallery__item', 'photo', 'gallery__item--wide' => $foto['ancho']])>
                    <img src="{{ $foto['src'] }}" alt="{{ $foto['alt'] }}" loading="lazy">
                </div>
            @endforeach
        </div>

    </div>
</section>
