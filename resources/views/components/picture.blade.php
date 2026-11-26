{{--
| Componente <x-picture> (lógica en app/View/Components/Picture.php).
| Los atributos extra (class, data-*) se aplican a <picture>; usa img-class para el <img>.
--}}
<picture {{ $attributes->class(['picture']) }}>
    @foreach ($sources as $source)
        <source type="{{ $source['type'] }}" srcset="{{ $source['srcset'] }}" sizes="{{ $sizes }}">
    @endforeach
    <img
        src="{{ $fallbackSrc }}"
        alt="{{ $alt }}"
        @if ($imgWidth) width="{{ $imgWidth }}" @endif
        @if ($imgHeight) height="{{ $imgHeight }}" @endif
        @if ($imgClass) class="{{ $imgClass }}" @endif
        @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        loading="{{ $loading }}"
        decoding="async">
</picture>
