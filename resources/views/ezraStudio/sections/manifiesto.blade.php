{{--
|--------------------------------------------------------------------------
| SECCIÓN 5 · MANIFIESTO
|--------------------------------------------------------------------------
| Una frase grande que resume la filosofía del estudio.
| - 'frase' se divide en dos partes: la segunda va en cursiva y color.
| - 'etiquetas' son palabras clave cortas debajo de la frase.
--}}

@php
    $manifiesto = [
        'titulo' => 'Por qué existimos',
        'frase' => 'Nacimos como refugio frente a la academia tradicional. Aquí el error es un hallazgo',
        'frase_destacada' => 'y el juego, una herramienta.',
        'etiquetas' => ['Pintura sin miedo', 'Infancias libres', 'Espacio queer', 'Materiales incluidos'],
    ];
@endphp

<section class="section manifesto" id="manifiesto" aria-labelledby="manifiesto-titulo">
    <div class="container manifesto__inner">

        <h2 class="manifesto__eyebrow" id="manifiesto-titulo">{{ $manifiesto['titulo'] }}</h2>

        <p class="manifesto__quote">
            {{ $manifiesto['frase'] }} <em>{{ $manifiesto['frase_destacada'] }}</em>
        </p>

        <ul class="manifesto__tags">
            @foreach ($manifiesto['etiquetas'] as $etiqueta)
                <li>{{ $etiqueta }}</li>
            @endforeach
        </ul>

    </div>
</section>
