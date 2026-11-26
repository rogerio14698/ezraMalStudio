{{--
|==========================================================================
| PÁGINA DE INICIO
|==========================================================================
| Esta vista solo ORDENA las secciones. Los datos llegan desde
| App\Http\Controllers\Pages\HomeController (contenido del panel).
| Cada sección mantiene textos de ejemplo que se usan mientras el panel
| esté vacío.
|
| El título y la descripción salen de "SEO por página" → Portada.
| Para fijarlos aquí, descomenta:
|   @section('title', 'Mi título')
|   @section('meta-description', 'Mi descripción')
--}}
@extends('app')

@section('content')

    @include('ezraStudio.sections.hero')        {{-- 1. Portada (panel: Cabecera y portada) --}}
    @include('ezraStudio.sections.visita')      {{-- 2. Horarios y ubicación (panel: Pie de página) --}}
    @include('ezraStudio.sections.talleres')    {{-- 3. Talleres (panel: Talleres) --}}
    @include('ezraStudio.sections.galeria')     {{-- 4. Galería (panel: Colecciones) --}}
    @include('ezraStudio.sections.manifiesto')  {{-- 5. Manifiesto (texto fijo) --}}

    {{-- 6. Contacto: formulario en layouts/footer.blade.php --}}

@endsection
