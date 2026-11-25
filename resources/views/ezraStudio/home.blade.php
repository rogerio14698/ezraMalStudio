{{--
|==========================================================================
| PÁGINA DE INICIO (maqueta)
|==========================================================================
| Esta vista solo ORDENA las secciones. El contenido de cada una está en
| resources/views/ezraStudio/sections/ (textos e imágenes en un array
| @php al principio de cada archivo).
|
| - Para reordenar: mueve las líneas @include.
| - Para ocultar una sección: pon su @include dentro de un comentario Blade.
| - Para añadir una: crea sections/nueva.blade.php y añade su @include.
--}}

@extends('app')

@section('title', 'ExitStudio · Taller de arte y creación')
@section('meta-description', 'Estudio de artes plásticas: óleo, acuarela, acrílico y técnica mixta para adultos e infancias. Grupos de 8 personas como máximo.')

@section('content')

    @include('ezraStudio.sections.hero')        {{-- 1. Portada --}}
    @include('ezraStudio.sections.visita')      {{-- 2. Horarios y ubicación --}}
    @include('ezraStudio.sections.talleres')    {{-- 3. Talleres (adultos / infancias) --}}
    @include('ezraStudio.sections.galeria')     {{-- 4. Galería de obra y taller --}}
    @include('ezraStudio.sections.manifiesto')  {{-- 5. Manifiesto (frase grande) --}}

    {{-- 6. Contacto: está en layouts/footer.blade.php --}}

@endsection
