{{--
|--------------------------------------------------------------------------
| SECCIÓN 2 · VISITA (horarios y ubicación)
|--------------------------------------------------------------------------
| Datos: panel → Web → Pie de página ($footer): horario, dirección y
| enlace de Google Maps. Mientras no exista, se usa el array de ejemplo.
--}}
@php
    $visita = [
        'titulo' => 'Horarios y cómo llegar',
        'intro' => 'Las clases son con reserva previa. Si quieres conocer el taller antes, ven en horario de mañana.',
        'horarios' => [
            ['dia' => 'Martes a viernes', 'hora' => '10:30 – 20:30'],
            ['dia' => 'Sábados', 'hora' => '10:00 – 14:00 (monográficos)'],
            ['dia' => 'Domingo y lunes', 'hora' => 'Cerrado'],
        ],
        'barrio' => 'Linares, Jaén',
        'mapa' => null,
        'imagen' => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=1000&q=80',
        'imagen_alt' => 'Pinceles con pintura seca apoyados en la mesa de trabajo',
    ];

    if ($footer ?? null) {
        if (filled($footer->horario)) {
            $visita['horarios'] = collect($footer->horario)
                ->map(fn ($fila) => ['dia' => $fila['dias'] ?? '', 'hora' => $fila['horas'] ?? ''])
                ->all();
        }
        $visita['barrio'] = $footer->address ?: $visita['barrio'];
        $visita['mapa'] = $footer->google_maps_url;
    }

    $visita['mapa'] ??= 'https://www.google.com/maps/search/?api=1&query=' . urlencode($visita['barrio']);
@endphp

<section class="section visit" id="visita" aria-labelledby="visita-titulo">
    <div class="container visit__grid">

        {{-- Texto + horarios --}}
        <div class="visit__content">
            <h2 class="section__title" id="visita-titulo">{{ $visita['titulo'] }}</h2>
            <p class="section__desc">{{ $visita['intro'] }}</p>

            <dl class="visit__hours">
                @foreach ($visita['horarios'] as $fila)
                    <div class="visit__row">
                        <dt>{{ $fila['dia'] }}</dt>
                        <dd>{{ $fila['hora'] }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="visit__place">
                <p><strong>Dónde:</strong> {{ $visita['barrio'] }}</p>
                <a class="btn btn--outline" href="{{ $visita['mapa'] }}" target="_blank" rel="noopener">Ver la zona</a>
            </div>
        </div>

        {{-- Foto --}}
        <div class="visit__visual photo">
            <img src="{{ $visita['imagen'] }}" alt="{{ $visita['imagen_alt'] }}" width="1000" height="1000"
                loading="lazy" decoding="async">
        </div>

    </div>
</section>
